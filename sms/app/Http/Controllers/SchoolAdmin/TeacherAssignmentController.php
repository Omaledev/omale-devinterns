<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassroomAssignment;
use App\Models\ClassLevel;
use App\Models\Subject;
use App\Models\Section;
use App\Models\TeacherProfile;
use Illuminate\Support\Facades\Log;

class TeacherAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assignments = ClassroomAssignment::with([
            'teacher.user', // Load teacher profile and then user
            'subject', 
            'classLevel', 
            'section'
        ])
        ->where('school_id', auth()->user()->school_id)
        ->latest()
        ->get();

        return view('schooladmin.teacher-assignments.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get all teachers for this school
        $teachers = User::role('Teacher')
            ->where('school_id', auth()->user()->school_id)
            ->get();

        // Create teacher profiles for any missing ones
        foreach ($teachers as $teacher) {
            if (!$teacher->teacherProfile) {
                // Generate employee_id if not exists
                $employeeId = $teacher->employee_id ?? 'EMP' . str_pad($teacher->id, 4, '0', STR_PAD_LEFT);
                
                TeacherProfile::create([
                    'user_id' => $teacher->id,
                    'school_id' => auth()->user()->school_id,
                    'employee_id' => $employeeId,
                    'is_active' => true
                ]);
            }
        }

        // Reload teachers with profiles
        $teachers = User::role('Teacher')
            ->where('school_id', auth()->user()->school_id)
            ->with('teacherProfile')
            ->get();
        
        $subjects = Subject::where('school_id', auth()->user()->school_id)
            ->where('is_active', true)
            ->get();
        
        $classLevels = ClassLevel::where('school_id', auth()->user()->school_id)
            ->where('is_active', true)
            ->get();
        
        $sections = Section::where('school_id', auth()->user()->school_id)
            ->where('is_active', true)
            ->get();

        return view('schooladmin.teacher-assignments.create', compact('teachers', 'subjects', 'classLevels', 'sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teacher_profiles,id', 
            'subject_id' => 'required|exists:subjects,id',
            'class_level_id' => 'required|exists:class_levels,id',
            'section_id' => 'nullable|exists:sections,id',
        ]);

        // Check if assignment already exists
        $existingAssignment = ClassroomAssignment::where([
            'teacher_id' => $request->teacher_id, // This is teacher_profile ID
            'subject_id' => $request->subject_id,
            'class_level_id' => $request->class_level_id,
            'school_id' => auth()->user()->school_id,
        ])->first();

        if ($existingAssignment) {
            return redirect()->back()
                ->with('error', 'This teacher is already assigned to this subject and class.');
        }

        ClassroomAssignment::create([
            'school_id' => auth()->user()->school_id,
            'teacher_id' => $request->teacher_id, // This is teacher_profile ID
            'subject_id' => $request->subject_id,
            'class_level_id' => $request->class_level_id,
            'section_id' => $request->section_id,
            'is_active' => true,
        ]);

        return redirect()->route('schooladmin.teacher-assignments.index')
            ->with('success', 'Teacher assigned successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassroomAssignment $teacherAssignment)
    {
        // Verify the assignment belongs to the same school
        if ($teacherAssignment->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $teacherAssignment->load(['teacher.user', 'subject', 'classLevel', 'section']);

        return view('schooladmin.teacher-assignments.show', compact('teacherAssignment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassroomAssignment $teacherAssignment)
    {
        if ($teacherAssignment->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        // Get teachers with their profiles
        $teachers = User::role('Teacher')
            ->where('school_id', auth()->user()->school_id)
            ->with('teacherProfile')
            ->get();
        
        $subjects = Subject::where('school_id', auth()->user()->school_id)
            ->where('is_active', true)
            ->get();
        
        $classLevels = ClassLevel::where('school_id', auth()->user()->school_id)
            ->where('is_active', true)
            ->get();
        
        $sections = Section::where('school_id', auth()->user()->school_id)
            ->where('is_active', true)
            ->get();

        return view('schooladmin.teacher-assignments.edit', compact('teacherAssignment', 'teachers', 'subjects', 'classLevels', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassroomAssignment $teacherAssignment)
    {
        if ($teacherAssignment->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $request->validate([
            'teacher_id' => 'required|exists:teacher_profiles,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_level_id' => 'required|exists:class_levels,id',
            'section_id' => 'nullable|exists:sections,id',
        ]);

        $teacherAssignment->update([
            'teacher_id' => $request->teacher_id,
            'subject_id' => $request->subject_id,
            'class_level_id' => $request->class_level_id,
            'section_id' => $request->section_id,
        ]);

        return redirect()->route('schooladmin.teacher-assignments.index')
            ->with('success', 'Teacher assignment updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassroomAssignment $teacherAssignment)
    {
        if ($teacherAssignment->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $teacherAssignment->delete();

        return redirect()->route('schooladmin.teacher-assignments.index')
            ->with('success', 'Teacher assignment deleted successfully!');
    }
}