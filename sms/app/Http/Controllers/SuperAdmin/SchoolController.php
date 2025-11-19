<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $schools = School::all();

        // For SuperAdmin - show schools based on active_school filter
        if (auth()->user()?->hasRole('SuperAdmin')) {
            $schools = School::when(session('active_school'), function ($query, $schoolId) {
                return $query->where('id', $schoolId);
            })->get();
        } else {
            // For other roles - show only their school
            $schools = School::where('id', auth()->user()->school_id)->get();
        }
        return view('superadmin.schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.schools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'email' => 'required|email|unique:schools',
            'phone' => 'nullable|string',
            'principal_name' => 'nullable|string|max:255'
        ]);

        School::create($validated);

        return redirect()->route('superadmin.schools.index')
            ->with('success', 'School created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        return view('superadmin.schools.show', compact('school'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        return view('superadmin.schools.edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'email' => 'required|email|unique:schools,email,' . $school->id,
            'phone' => 'nullable|string',
            'principal_name' => 'nullable|string|max:255'
        ]);

        $school->update($validated);

        return redirect()->route('superadmin.schools.index')
            ->with('success', 'School updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        $school->delete();

        return redirect()->route('superadmin.schools.index')
            ->with('success', 'School deleted successfully.');
    }


    /**
     * Form to create user for a school
     */
    public function createUser(School $school)
    {
        $availableRoles = Role::whereIn('name', ['SchoolAdmin', 'Bursar', 'Teacher'])->get();
        return view('superadmin.schools.create-user', compact('school', 'availableRoles'));
    }

    /**
     * Store new user for a school
     */
    public function storeUser(Request $request, School $school)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'school_id' => $school->id
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('superadmin.schools.show', $school)
            ->with('success', "{$validated['role']} created successfully!");
    }

    /**
     * Show school users
     */
    public function showUsers(School $school)
    {
        $users = $school->users()->with('roles')->get();
        return view('superadmin.schools.users', compact('school', 'users'));
    }
}
