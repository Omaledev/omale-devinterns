<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassroomAssignment;
use App\Models\ClassLevel;
use App\Models\Subject;
use Illuminate\Support\Facades\Hash;


class TeacherProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = User::role('Teacher')
            ->where('school_id', auth()->user()->school_id)
            ->get();
        
        return view('schooladmin.teacherProfile.index', compact('teachers'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('schooladmin.teacherProfile.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'employee_id' => 'required|string|unique:users,employee_id'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'employee_id' => $validated['employee_id'],
            'school_id' => auth()->user()->school_id,
            'is_approved' => true
        ]);

        $user->assignRole('Teacher');

        return redirect()->route('schooladmin.teachers.index')
            ->with('success', 'Teacher created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $teacher)
    {
        if ($teacher->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        return view('schooladmin.teacherProfile.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $teacher)
    {
         if ($teacher->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        return view('schooladmin.teacherProfile.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $teacher)
    {
         if ($teacher->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'employee_id' => 'required|string|unique:users,employee_id,' . $teacher->id
        ]);

        $teacher->update($validated);

        return redirect()->route('schooladmin.teachers.index')
            ->with('success', 'Teacher updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $teacher)
    {
        if ($teacher->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $teacher->delete();

        return redirect()->route('schooladmin.teachers.index')
            ->with('success', 'Teacher deleted successfully!');
    }
    

    // public function assignments()
    // {
    //     $assignments = ClassroomAssignment::with(['teacher', 'classLevel', 'section', 'subject'])
    //         ->whereHas('teacher', function($query) {
    //             $query->where('school_id', auth()->user()->school_id);
    //         })
    //         ->get();
            
    //     return view('schooladmin.teacher.assignments', compact('assignments'));
    // }
    
    // public function createAssignment()
    // {
    //     $teachers = User::role('Teacher')
    //         ->where('school_id', auth()->user()->school_id)
    //         ->get();
            
    //     $classLevels = ClassLevel::where('school_id', auth()->user()->school_id)
    //         ->where('is_active', true)
    //         ->get();
            
    //     $subjects = Subject::where('school_id', auth()->user()->school_id)
    //         ->where('is_active', true)
    //         ->get();
            
    //     return view('schooladmin.teachers.create-assignment', compact('teachers', 'classLevels', 'subjects'));
    // }

    // public function storeAssignment(Request $request)
    // {
    //     $validated = $request->validate([
    //         'teacher_id' => 'required|exists:users,id',
    //         'class_level_id' => 'required|exists:class_levels,id',
    //         'section_id' => 'nullable|exists:sections,id',
    //         'subject_id' => 'required|exists:subjects,id',
    //     ]);

    //     // Checking if assignment already exists
    //     $existing = ClassroomAssignment::where($validated)->first();
        
    //     if ($existing) {
    //         return redirect()->back()
    //             ->withInput()
    //             ->with('error', 'This assignment already exists!');
    //     }

    //     ClassroomAssignment::create($validated);

    //     return redirect()->route('schooladmin.teacher-assignments.index')
    //         ->with('success', 'Teacher assigned successfully!');
    //     }
    
}
