<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Shows subjects for the logged-in user's school
        $subjects = Subject::where('school_id', session('active_school'))->get();
        return view('schooladmin.subjects.index', compact('subjects'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('schooladmin.subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // Get the current school ID
    $schoolId = session('active_school');

    // Validate using Rules 
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'code' => ['required','string','max:20',

            // Rule: Code must be unique, BUT only inside this specific school
            Rule::unique('subjects')->where(function ($query) use ($schoolId) {
                return $query->where('school_id', $schoolId);
            })
        ],
    ]);

    // Create the subject
    Subject::create([
        'school_id' => $schoolId,
        'name' => $request->name,
        'code' => strtoupper($request->code),
        'description' => $request->description,
    ]);

    return redirect()->route('schooladmin.subjects.index')
        ->with('success', 'Subject created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
       $subject->load(['classroomAssignments.teacher.user', 'classroomAssignments.classLevel']);
        return view('schooladmin.subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
          return view('schooladmin.subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $schoolId = session('active_school');

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'code' => ['required','string','max:20',
            
                // Rule: Check unique in 'subjects' table...
                // ...IGNORE the subject we are currently editing...
                // ...and ONLY check rows belonging to this school.
                Rule::unique('subjects')->ignore($subject->id)->where(function ($query) use ($schoolId) {
                    return $query->where('school_id', $schoolId);
                })
            ],
        ]);

        // Update the subject
        $subject->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('schooladmin.subjects.index')
            ->with('success', 'Subject updated successfully.');

        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy(Subject $subject)
        {
            // Checking if the subject has classroom assignments
            if ($subject->classroomAssignments()->count() > 0) {
                return redirect()->route('schooladmin.subjects.index')
                    ->with('error', 'Cannot delete subject. There are teachers assigned to this subject.');
            }

            $subject->delete();
            return redirect()->route('schooladmin.subjects.index')
                ->with('success', 'Subject deleted successfully.');
    }
}
