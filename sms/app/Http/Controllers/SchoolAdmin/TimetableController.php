<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timetable;
use App\Models\ClassLevel;
use App\Models\User; 
use App\Models\Subject;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class TimetableController extends Controller
{

    private function getValidationRules($timetableId = null)
    {
        return [
            'class_level_id' => 'required|exists:class_levels,id',
            'section_id'     => 'required|exists:sections,id',
            'subject_id'     => 'required|exists:subjects,id',
            'teacher_id'     => 'required|exists:users,id',
            'weekday'        => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday',
            'start_time'     => 'required|date_format:H:i',
            'end_time'       => 'required|date_format:H:i|after:start_time',
        ];
    }
    
    // Time Overlap Logic
    private function validateOverlap(Request $request, $ignoreId = null)
    {
        $validatedData = $request->validate($this->getValidationRules($ignoreId));
        $schoolId = session('active_school');
        
        $baseQuery = Timetable::where('school_id', $schoolId)
            ->where('weekday', $validatedData['weekday'])
            // Ignore the current record when updating
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            });

        // The universal overlap condition check
        $overlapCondition = function ($query) use ($validatedData) {
            // Checks for records where the time slots intersect
            $query->where(function ($q) use ($validatedData) {
                $q->whereBetween('start_time', [$validatedData['start_time'], $validatedData['end_time']])
                  ->orWhereBetween('end_time', [$validatedData['start_time'], $validatedData['end_time']])
                  // Also checks if existing slot fully surrounds the new slot
                  ->orWhere(function ($qq) use ($validatedData) {
                      $qq->where('start_time', '<', $validatedData['start_time'])
                         ->where('end_time', '>', $validatedData['end_time']);
                  });
            });
        };

        // Class/Section Overlap Check
        $classOverlap = (clone $baseQuery)
            ->where('class_level_id', $validatedData['class_level_id'])
            ->where('section_id', $validatedData['section_id'])
            ->where($overlapCondition)
            ->exists();

        if ($classOverlap) {
            return ['error' => 'This class/section already has a subject scheduled at this time on ' . $validatedData['weekday'] . '.'];
        }

        // Teacher Overlap Check
        $teacherOverlap = (clone $baseQuery)
            ->where('teacher_id', $validatedData['teacher_id'])
            ->where($overlapCondition)
            ->exists();

        if ($teacherOverlap) {
            return ['error' => 'The selected teacher is already scheduled during this time slot on ' . $validatedData['weekday'] . '.'];
        }

        return $validatedData;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $schoolId = session('active_school');
        $filterBy = $request->input('filter_by', 'class');
        $filterId = $request->input('filter_id');

        $query = Timetable::with('classLevel', 'section', 'subject', 'teacher')
            ->where('school_id', $schoolId)
            ->orderBy('start_time');

        if ($filterId) {
            if ($filterBy === 'class') {
                $query->where('class_level_id', $filterId);
            } elseif ($filterBy === 'teacher') {
                $query->where('teacher_id', $filterId);
            }
        }

        $timetables = $query->get();

        // Grouping the data for the grid view
        $weeklyTimetable = $timetables->groupBy('weekday')->map(function ($dayEntries) {
            return $dayEntries->keyBy(function ($entry) {
                return $entry->start_time . '-' . $entry->end_time;
            });
        });

        // Lists for filter dropdowns
        $classes = ClassLevel::where('school_id', $schoolId)->get();
        $teachers = User::role('Teacher')->where('school_id', $schoolId)->get();

        return view('schooladmin.timetables.index', compact('weeklyTimetable', 'classes', 'teachers', 'filterBy', 'filterId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schoolId = session('active_school');
        $classes = ClassLevel::where('school_id', $schoolId)->with('sections')->get();
        $subjects = Subject::where('school_id', $schoolId)->get();
        $teachers = User::role('Teacher')->where('school_id', $schoolId)->get();
        $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        return view('schooladmin.timetables.create', compact('classes', 'subjects', 'teachers', 'weekdays'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->validateOverlap($request);

        if (isset($result['error'])) {
            return back()->withInput()->withErrors(['time_conflict' => $result['error']]);
        }

        Timetable::create(array_merge($result, ['school_id' => session('active_school')]));

        return redirect()->route('schooladmin.timetables.index')->with('success', 'Timetable entry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Timetable $timetable)
    {
        return view('schooladmin.timetables.show', compact('timetable'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Timetable $timetable)
    {
        // $this->authorize('update', $timetable); 

        $schoolId = session('active_school');
        $classes = ClassLevel::where('school_id', $schoolId)->with('sections')->get();
        $subjects = Subject::where('school_id', $schoolId)->get();
        $teachers = User::role('Teacher')->where('school_id', $schoolId)->get();
        $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        return view('schooladmin.timetables.edit', compact('timetable', 'classes', 'subjects', 'teachers', 'weekdays'));
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Timetable $timetable)
    {
            $result = $this->validateOverlap($request, $timetable->id);

            if (isset($result['error'])) {
                return back()->withInput()->withErrors(['time_conflict' => $result['error']]);
            }

            $timetable->update($result);

            return redirect()->route('schooladmin.timetables.index')->with('success', 'Timetable entry updated successfully.');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timetable $timetable)
    {
        $timetable->delete();
        
        return redirect()->route('schooladmin.timetables.index')->with('success', 'Timetable entry deleted successfully.');
    }

    public function export()
    {
        $schoolId = session('active_school');
        
        // Fetch all timetable entries for school
        $timetables = Timetable::with('classLevel', 'section', 'subject', 'teacher')
            ->where('school_id', $schoolId)
            ->orderBy('weekday')
            ->orderBy('start_time')
            ->get();

        // Group data for the view
        $weeklyTimetable = $timetables->groupBy('weekday')->map(function ($dayEntries) {
            return $dayEntries->keyBy(function ($entry) {
                return $entry->start_time . '-' . $entry->end_time;
            });
        });

        // Load the view and pass data
        $pdf = Pdf::loadView('schooladmin.timetables.pdf', compact('weeklyTimetable'));
        
        // Download the file
        return $pdf->download('school_timetable.pdf');
    }
}
