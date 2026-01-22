<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timetable; 
use App\Models\ClassLevel;

class TimetableController extends Controller
{
    public function index(Request $request)
    {   
        if (!auth()->user()->teacherProfile || !auth()->user()->is_approved) {
            return redirect()->route('teacher.dashboard')->with('error', 'Account pending approval.');
        }
        
        $schoolId = session('active_school');
        
        // Getting all classes for the dropdown
        $classes = ClassLevel::where('school_id', $schoolId)->get();

        $selectedClassId = $request->get('class_level_id');
        $weeklyTimetable = [];

        if ($selectedClassId) {
            // Fetching ALL entries for this class
            $timetables = Timetable::with('subject', 'teacher', 'section')
                ->where('school_id', $schoolId)
                ->where('class_level_id', $selectedClassId)
                ->orderBy('start_time')
                ->get();

            // Grouping them exactly like the Admin Controller does
            $weeklyTimetable = $timetables->groupBy('weekday')->map(function ($dayEntries) {
                return $dayEntries->keyBy(function ($entry) {
                    return $entry->start_time . '-' . $entry->end_time;
                });
            });
        }

        return view('teacher.timetable.index', compact('classes', 'weeklyTimetable', 'selectedClassId'));
    }
}