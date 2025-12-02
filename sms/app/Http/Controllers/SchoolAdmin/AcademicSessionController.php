<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicSession;
use App\Models\User;

class AcademicSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sessions = AcademicSession::where('school_id', auth()->user()->school_id)
            ->orderBy('start_date', 'desc')
            ->get();
            
        return view('schooladmin.academic-sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('schooladmin.academic-sessions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        AcademicSession::create([
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'school_id' => auth()->user()->school_id,
            'is_active' => false, 
        ]);

        return redirect()->route('schooladmin.academic-sessions.index')
            ->with('success', 'Academic session created successfully!');
    }

    // Activate a session (ACTIVATE METHOD)
    public function activate(AcademicSession $session)
    {
        // Ensuring session belongs to current school
        if ($session->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        // Deactivate all other sessions
        AcademicSession::where('school_id', auth()->user()->school_id)
            ->where('id', '!=', $session->id)
            ->update(['is_active' => false]);
        
        // Activate this session
        $session->update(['is_active' => true]);
        
        return back()->with('success', 'Academic session activated!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicSession $session)
    {
         if ($session->school_id !== auth()->user()->school_id) {
            abort(403);
        }
        
        return view('schooladmin.academic-sessions.edit', compact('session'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcademicSession $session)
    {
        if ($session->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $session->update($validated);
        
        return redirect()->route('schooladmin.academic-sessions.index')
            ->with('success', 'Academic session updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicSession $session)
    {
                if ($session->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        // Prevent deleting active session
        if ($session->is_active) {
            return back()->with('error', 'Cannot delete active academic session!');
        }

        $session->delete();
        
        return redirect()->route('schooladmin.academic-sessions.index')
            ->with('success', 'Academic session deleted successfully!');
    }

    
}
