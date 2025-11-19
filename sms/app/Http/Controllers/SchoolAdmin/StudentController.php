<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = User::role('Student')
            ->where('school_id', auth()->user()->school_id)
            ->with('school')
            ->get();

        return view('schooladmin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('schooladmin.students.create');
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
            'admission_number' => 'required|string|unique:users,admission_number'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'admission_number' => $validated['admission_number'],
            'school_id' => auth()->user()->school_id,
            'is_approved' => true
        ]);

        $user->assignRole('Student');

        return redirect()->route('schooladmin.students.index')
            ->with('success', 'Student created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $student)
    {
        // Ensuring the student belongs to the same school
        if ($student->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        return view('schooladmin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $student)
    {
        if ($student->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        return view('schooladmin.students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $student)
    {
        if ($student->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'admission_number' => 'required|string|unique:users,admission_number,' . $student->id
        ]);

        $student->update($validated);

        return redirect()->route('schooladmin.students.index')
            ->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $student)
    {
        if ($student->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $student->delete();

        return redirect()->route('schooladmin.students.index')
            ->with('success', 'Student deleted successfully!');
    }
}
