<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassLevel;
use App\Models\Subject;
use App\Models\AssessmentType;
use App\Models\Grade;
use App\Models\TeacherProfile;
use App\Models\AcademicSession;
use App\Models\Term;            

class GradeController extends Controller
{
    /**
     * Step 1: Select Class and Subject
     */
    public function index()
    {
        $teacher = auth()->user();
        $teacherProfile = TeacherProfile::where('user_id', $teacher->id)->firstOrFail();

        $classes = $teacherProfile->assignedClasses()->get();
        
        $subjects = Subject::where('school_id', $teacher->school_id)->get(); 

        return view('teacher.grades.index', compact('classes', 'subjects'));
    }

    /**
     * Show the Grading Sheet
     */
    public function create(Request $request)
    {
        $request->validate([
            'class_level_id' => 'required|exists:class_levels,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $schoolId = auth()->user()->school_id;
        $classId = $request->class_level_id;
        $subjectId = $request->subject_id;

       
        // Get the explicitly ACTIVE Session
        $session = \App\Models\AcademicSession::where('school_id', $schoolId)
                    ->where('is_active', true)
                    ->first();

        // Get the explicitly ACTIVE Term
        $term = \App\Models\Term::where('is_active', true)
                    ->whereHas('academicSession', function($q) use ($schoolId) {
                        $q->where('school_id', $schoolId);
                    })
                    ->first();

        if (!$session || !$term) {
            return back()->with('error', 'No active Academic Session or Term found. Please contact Admin to activate the current session and term.');
        }
        
        // Fetch Students in the class
        $students = User::role('Student')
            ->whereHas('studentProfile', function($q) use ($classId) {
                $q->where('class_level_id', $classId);
            })
            ->with('studentProfile')
            ->orderBy('name')
            ->get();

        // Fetch Assessment Types
        $assessmentTypes = AssessmentType::where('school_id', $schoolId)->get();

        // Fetch Existing Grades
        $existingGrades = Grade::where([
            'class_level_id' => $classId,
            'subject_id' => $subjectId,
            'academic_session_id' => $session->id,
            'term_id' => $term->id
        ])->get();

        $isLocked = $existingGrades->first()->is_locked ?? false;

        $class = ClassLevel::find($classId);
        $subject = Subject::find($subjectId);

        return view('teacher.grades.create', compact(
            'students', 
            'assessmentTypes', 
            'existingGrades', 
            'class', 
            'subject',
            'session',
            'term',
            'isLocked'
        ));
    }

    /**
     * Save Grades
     */
    public function store(Request $request)
    {
        $request->validate([
            'grades' => 'required|array',
            'class_level_id' => 'required',
            'subject_id' => 'required',
            'academic_session_id' => 'required',
            'term_id' => 'required'
        ]);

        $teacherId = auth()->id();

        // Loop through Students
        foreach ($request->grades as $studentId => $assessments) {
            
            // Loop through Assessment Types for that student
            foreach ($assessments as $assessmentTypeId => $score) {
                
                // Skip empty scores 
                if ($score === null) continue;

                Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $request->subject_id,
                        'class_level_id' => $request->class_level_id,
                        'assessment_type_id' => $assessmentTypeId,
                        'academic_session_id' => $request->academic_session_id,
                        'term_id' => $request->term_id,
                    ],
                    [
                        'teacher_id' => $teacherId,
                        'score' => $score,
                        'is_locked' => false // Default is false
                    ]
                );
            }
        }

        // Handle Locking if the "Lock" button was pressed
        if ($request->has('action') && $request->action === 'lock') {
             Grade::where([
                'class_level_id' => $request->class_level_id,
                'subject_id' => $request->subject_id,
                'academic_session_id' => $request->academic_session_id,
                'term_id' => $request->term_id,
            ])->update(['is_locked' => true]);
            
            return redirect()->route('teacher.dashboard')->with('success', 'Grades published and locked successfully.');
        }

        return back()->with('success', 'Grades saved successfully.');
    }
}