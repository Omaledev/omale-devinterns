<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeeStructure;
use App\Models\ClassLevel;
use App\Models\Term;

class FeeStructureController extends Controller
{
    public function index()
    {
        $fees = FeeStructure::where('school_id', auth()->user()->school_id)
            ->with(['classLevel', 'term'])
            ->get();
            
        return view('schooladmin.fees.structure.index', compact('fees'));
    }

    public function create()
    {
        $schoolId = auth()->user()->school_id;
        $classes = ClassLevel::where('school_id', $schoolId)->get();
        // Get active terms via this session relationship
        $terms = Term::whereHas('academicSession', fn($q) => $q->where('school_id', $schoolId))
                     ->where('is_active', true)
                     ->get();

        return view('schooladmin.fees.structure.create', compact('classes', 'terms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_level_id' => 'required',
            'term_id' => 'required',
            'name' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        FeeStructure::create([
            'school_id' => auth()->user()->school_id,
            'class_level_id' => $request->class_level_id,
            'term_id' => $request->term_id,
            'name' => $request->name,
            'amount' => $request->amount,
        ]);

        return redirect()->route('finance.fee-structures.index')
            ->with('success', 'Fee added successfully.');
    }

    public function edit($id)
    {
        $feeStructure = \App\Models\FeeStructure::findOrFail($id);
        
        // Ensure user can only edit their own school's fees
        if($feeStructure->school_id != auth()->user()->school_id){
             abort(403);
        }

        $classes = \App\Models\ClassLevel::where('school_id', auth()->user()->school_id)->get();
        $terms = \App\Models\Term::where('school_id', auth()->user()->school_id)->get();

        return view('schooladmin.fees.structure.edit', compact('feeStructure', 'classes', 'terms'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'amount' => 'required|numeric',
            'class_level_id' => 'required',
            'term_id' => 'required',
        ]);

        $feeStructure = \App\Models\FeeStructure::findOrFail($id);
        
        // Security check
        if($feeStructure->school_id != auth()->user()->school_id){
             abort(403);
        }

        $feeStructure->update([
            'name' => $request->name,
            'amount' => $request->amount,
            'class_level_id' => $request->class_level_id,
            'term_id' => $request->term_id,
        ]);

        return redirect()->route('finance.fee-structures.index')->with('success', 'Fee Structure updated successfully');
    }
    
    public function destroy(FeeStructure $feeStructure)
    {
        if($feeStructure->school_id !== auth()->user()->school_id) abort(403);
        $feeStructure->delete();
        return back()->with('success', 'Fee structure deleted.');
    }
}