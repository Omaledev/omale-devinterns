<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BursarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bursars = User::role('Bursar')
            ->where('school_id', auth()->user()->school_id)
            ->get();
        
        return view('schooladmin.bursars.index', compact('bursars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('schooladmin.bursars.create');
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
            'employee_id' => 'required|string|unique:users,employee_id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'employee_id' => $validated['employee_id'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'school_id' => auth()->user()->school_id,
            'is_approved' => true
        ]);

        $user->assignRole('Bursar');

        return redirect()->route('schooladmin.bursars.index')
            ->with('success', 'Bursar created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $bursar)
    {
        if ($bursar->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        return view('schooladmin.bursars.show', compact('bursar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $bursar)
    {
        if ($bursar->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        return view('schooladmin.bursars.edit', compact('bursar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $bursar)
    {
          if ($bursar->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $bursar->id,
            'employee_id' => 'required|string|unique:users,employee_id,' . $bursar->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $bursar->update($validated);

        return redirect()->route('schooladmin.bursars.index')
            ->with('success', 'Bursar updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $bursar)
    {
        if ($bursar->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $bursar->delete();

        return redirect()->route('schooladmin.bursars.index')
            ->with('success', 'Bursar deleted successfully!');
    }

    
}
