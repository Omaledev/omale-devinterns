<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AssessmentType; 

class AssessmentController extends Controller
{
   public function index()
    {
        if (!auth()->user()->teacherProfile || !auth()->user()->is_approved) {
            return redirect()->route('teacher.dashboard')->with('error', 'Account pending approval.');
        }
        
        $schoolId = session('active_school') ?? auth()->user()->school_id;

        $assessmentTypes = AssessmentType::where('school_id', $schoolId)
            ->orderBy('name')
            ->get();

        return view('teacher.assessments.index', compact('assessmentTypes'));
    }
}