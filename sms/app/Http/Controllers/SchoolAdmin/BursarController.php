<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use Illuminate\Support\Facades\Hash;

class BursarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::role('Bursar')
            ->where('school_id', auth()->user()->school_id);

        // Search Logic
        if ($request->has('search') && $request->search != '') {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }
        
        $bursars = $query->latest()->paginate(10);
        return view('schooladmin.bursars.index', compact('bursars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        // Generating Sequential ID 
        $nextEmployeeId = $this->generateEmployeeId();
        
        return view('schooladmin.bursars.create', compact('nextEmployeeId'));
    }

    /**
     * Generate ID Format: SCHOOL + ROLE + YEAR + NUMBER
     * Example: VICBUR2026001
     */
    private function generateEmployeeId()
    {
        $schoolId = auth()->user()->school_id;
        $school = School::find($schoolId);
        
        // Prefix: 
        $cleanName = preg_replace('/[^a-zA-Z]/', '', $school->name ?? 'SCH');
        $prefix = strtoupper(substr($cleanName, 0, 3));
        
        // Role (BUR) and Year
        $role = 'BUR';
        $year = date('Y');
        
        // Search Pattern: VICBUR2026%
        $searchPattern = "{$prefix}{$role}{$year}%";

        // Find the last bursar
        $lastBursar = User::role('Bursar')
            ->where('school_id', $schoolId)
            ->where('employee_id', 'like', $searchPattern)
            ->orderByRaw('LENGTH(employee_id) DESC') 
            ->orderBy('employee_id', 'desc')
            ->first();

        if (!$lastBursar) {
            // Start at 001
            return "{$prefix}{$role}{$year}001";
        }

        // Extract Number
        $lastNumber = substr($lastBursar->employee_id, 10); 
        
        $newNumber = intval($lastNumber) + 1;
        $paddedNumber = str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return "{$prefix}{$role}{$year}{$paddedNumber}";
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
            'is_approved' => 'nullable|boolean',
        ]);

        $bursar->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'employee_id' => $validated['employee_id'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'is_approved' => $request->boolean('is_approved'), 
        ]);

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