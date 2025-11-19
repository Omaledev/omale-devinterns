<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = User::role('Teacher')
            ->where('school_id', auth()->user()->school_id)
            ->get();
        
        return view('schooladmin.teachers.index', compact('teachers'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('schooladmin.teachers.create');
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

        return view('schooladmin.teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $teacher)
    {
         if ($teacher->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        return view('schooladmin.teachers.edit', compact('teacher'));
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
    
}
