<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StudentProfileController extends Controller
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

        return view('schooladmin.studentProfile.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('schooladmin.studentProfile.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'admission_number' => 'required|string|unique:users,admission_number',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'class' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'admission_date' => 'nullable|date',
            'address' => 'nullable|string',
            'state' => 'nullable|string|max:255',
            'emergency_contact' => 'required|string|max:255',
        ]);

        // Creating the user
        $user = User::create([
            'name' => $validated['full_name'], 
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'admission_number' => $validated['admission_number'],
            'phone' => $validated['phone'],
            'school_id' => auth()->user()->school_id,
            'is_approved' => true
        ]);

        // Assigning Student role
        $user->assignRole('Student');

        // Creating student profile
        StudentProfile::create([
            'user_id' => $user->id,
            'school_id' => auth()->user()->school_id,
            'student_id' => $validated['admission_number'], 
            'admission_date' => $validated['admission_date'],
            'date_of_birth' => $validated['date_of_birth'],
            'address' => $validated['address'],
        ]);

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

        $student->load('studentProfile');
        return view('schooladmin.studentProfile.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $student)
    {
        if ($student->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $student->load('studentProfile');
        return view('schooladmin.studentProfile.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $student)
    {
        if ($student->school_id !== auth()->user()->school_id) {
            abort(403);
        }

    //   dd([
    //     'request_data' => $request->all(),
    //     'current_student' => $student->toArray(),
    //     'old_data' => [
    //         'name' => $student->name,
    //         'email' => $student->email,
    //         'class' => $student->class,
    //         'section' => $student->section,
    //     ]
    // ]);


        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'admission_number' => 'required|string|unique:users,admission_number,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'class' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'admission_date' => 'nullable|date',
            'address' => 'nullable|string',
            'state' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'is_approved' => 'nullable|boolean',
            
        ]);

          \Log::info('Validated data:', $validated);

        // Updating user
        $student->update([
            'name' => $validated['full_name'], 
            'email' => $validated['email'],
            'admission_number' => $validated['admission_number'],
            'phone' => $validated['phone'],
            'class' => $validated['class'] ?? null, 
            'section' => $validated['section'] ?? null, 
            'is_approved' => $request->boolean('is_approved'),
        ]);

        // Update or create the student profile
        if ($student->studentProfile) {
            $student->studentProfile->update([
                'student_id' => $validated['admission_number'],
                'admission_date' => $validated['admission_date'],
                'date_of_birth' => $validated['date_of_birth'],
                'address' => $validated['address'],
                'state' => $validated['state'] ?? null, 
                'emergency_contact' => $validated['emergency_contact'] ?? null, 
            ]);
        } else {
            StudentProfile::create([
                'user_id' => $student->id,
                'school_id' => auth()->user()->school_id,
                'student_id' => $validated['admission_number'],
                'admission_date' => $validated['admission_date'],
                'date_of_birth' => $validated['date_of_birth'],
                'address' => $validated['address'],
                'state' => $validated['state'] ?? null, 
                'emergency_contact' => $validated['emergency_contact'] ?? null, 
            ]);
        }

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

        // Delete student profile if exists
        if ($student->studentProfile) {
            $student->studentProfile->delete();
        }

        $student->delete();

        return redirect()->route('schooladmin.students.index')
            ->with('success', 'Student deleted successfully!');
    }
}