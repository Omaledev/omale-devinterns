<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Term;
use App\Models\AcademicSession;


class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $terms = Term::whereHas('academicSession', function($query) {
                $query->where('school_id', auth()->user()->school_id);
            })
            ->with('academicSession')
            ->orderBy('start_date', 'desc')
            ->get();
            
        return view('schooladmin.terms.index', compact('terms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sessions = AcademicSession::where('school_id', auth()->user()->school_id)
            ->orderBy('start_date', 'desc')
            ->get();
            
        return view('schooladmin.terms.create', compact('sessions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validated = $request->validate([
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Verifying academic session belongs to current school
        $session = AcademicSession::findOrFail($validated['academic_session_id']);
        if ($session->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        Term::create([
            'academic_session_id' => $validated['academic_session_id'],
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => false, 
        ]);

        return redirect()->route('schooladmin.terms.index')
            ->with('success', 'Term created successfully!');
    }

    // Activate a term (DEACTIVATING PREVIOUS TERMS FIRST)
    public function activate(Term $term)
    {
        // Ensuring term belongs to current school
        if ($term->academicSession->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        // Deactivate all other terms in the same school
        Term::whereHas('academicSession', function($query) {
                $query->where('school_id', auth()->user()->school_id);
            })
            ->where('id', '!=', $term->id)
            ->update(['is_active' => false]);
        
        // Activate this term
        $term->update(['is_active' => true]);
        
        return back()->with('success', 'Term activated!');

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
    public function edit(Term $term)
    {
         if ($term->academicSession->school_id !== auth()->user()->school_id) {
            abort(403);
        }
        
        $sessions = AcademicSession::where('school_id', auth()->user()->school_id)
            ->orderBy('start_date', 'desc')
            ->get();
            
        return view('schooladmin.terms.edit', compact('term', 'sessions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Term $term)
    {
           if ($term->academicSession->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $validated = $request->validate([
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Verifying new academic session belongs to current school
        $session = AcademicSession::findOrFail($validated['academic_session_id']);
        if ($session->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $term->update($validated);
        
        return redirect()->route('schooladmin.terms.index')
            ->with('success', 'Term updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Term $term)
    {
         if ($term->academicSession->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        // Prevent deleting active term
        if ($term->is_active) {
            return back()->with('error', 'Cannot delete active term!');
        }

        $term->delete();
        
        return redirect()->route('schooladmin.terms.index')
            ->with('success', 'Term deleted successfully!');
    
    }
}
