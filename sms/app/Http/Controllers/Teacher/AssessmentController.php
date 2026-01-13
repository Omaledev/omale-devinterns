<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AssessmentType; 

class AssessmentController extends Controller
{
    public function index()
    {
        $schoolId = session('active_school');

        // Fetch Assessment Types set by School Admin
        $assessmentTypes = AssessmentType::where('school_id', $schoolId)
            ->orderBy('name')
            ->get();

        return view('teacher.assessments.index', compact('assessmentTypes'));
    }
}