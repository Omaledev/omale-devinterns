<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::all();
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
         $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20',
            'description' => 'nullable|string',
        ]);

        $schoolId = session('active_school');

        // Check if subject with same name already exists in the same school
        $existingSubjectByName = Subject::where('name', $request->name)
            ->where('school_id', $schoolId)
            ->first();

        if ($existingSubjectByName) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A subject with this name already exists in your school.');
        }

        // Check if subject with same code already exists in the same school
        $existingSubjectByCode = Subject::where('code', strtoupper($request->code))
            ->where('school_id', $schoolId)
            ->first();

        if ($existingSubjectByCode) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A subject with this code already exists in your school.');
        }

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
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        // Checking if the subject with same name already exists in the same school (excluding current subject)
        $existingSubject = Subject::where('name', $request->name)
            ->where('school_id', session('active_school'))
            ->where('id', '!=', $subject->id)
            ->first();

        if ($existingSubject) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A subject with this name already exists.');
        }

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
