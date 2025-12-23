<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssessmentType;

class AssessmentTypeController extends Controller
{
    public function index() {
        $schoolId = auth()->user()->school_id;

        $assessments = AssessmentType::where('school_id', $schoolId)->get();

        $totalWeight = $assessments->sum('weight');

        return view('schooladmin.assessments.index', compact('assessments', 'totalWeight'));
    }

    public function store(Request $request) {
       $request->validate([
         'name' => 'required|string|max:255',
         'weight' => 'required|integer|min:1|max:100',
         'max_score' => 'required|integer|min:100'
       ]);

       $schoolId = auth()->user()->school_id;

       $currentTotal = AssessmentType::where('school_id', $schoolId)->sum('weight');

       if(($currentTotal + $request->weight) > 100){
           return back()->with('error', "Cannot add assessment. Total weight will exceed 100%. Current:({$currentTotal}%)");
       }

       AssessmentType::create([
        'school_id' => $schoolId,
        'name' => $request->name,
        'weight' => $request->weight,
        'max_score' => $request->max_score
       ]);

       return back()->with('success', 'Assessment type added successfully.');
    }

    public function destroy(AssessmentType $assessmentType) {
       if($assessmentType->school_id !== auth()->user()->school_id) {
          abort(403);
       }

       $assessmentType->delete();

       return back()->with('success', 'Assessment Type deleted successfully.');
    }
}
