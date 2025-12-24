<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassLevel;
use App\Models\AcademicSession;
use App\Models\Term;
use App\Models\Grade;
use Barryvdh\DomPDF\Facade\Pdf;
class ReportCardController extends Controller
{
   public function index(Request $request)
    {
        $teacher = auth()->user();
        $schoolId = $teacher->school_id;

        // Fetch dropdown data
        $classes = \App\Models\ClassLevel::where('school_id', $schoolId)->get();
        $sessions = \App\Models\AcademicSession::where('school_id', $schoolId)->orderByDesc('start_date')->get();
        
        // If user submitted the form (search)
        $students = [];
        $selectedClass = null;
        $selectedSession = null;
        $selectedTerm = null;

        if ($request->has(['class_level_id', 'session_id', 'term_id'])) {
            $selectedClass = $request->class_level_id;
            $selectedSession = $request->session_id;
            $selectedTerm = $request->term_id;

            // Fetch students in the class
            $students = \App\Models\User::role('Student')
                ->whereHas('studentProfile', fn($q) => $q->where('class_level_id', $selectedClass))
                ->get();
        }

        return view('teacher.reports.index', compact('classes', 'sessions', 'students', 'selectedClass', 'selectedSession', 'selectedTerm'));
    }

    // Generate Report Card for a Single Student
    public function download(Request $request, User $student)
    {
        $session = AcademicSession::find($request->session_id);
        $term = Term::find($request->term_id);

        if (!$session || !$term) {
            return back()->with('error', 'Session and Term are required.');
        }

        // Fetch Grades for this student, session, and term
        $grades = Grade::where('student_id', $student->id)
            ->where('academic_session_id', $session->id)
            ->where('term_id', $term->id)
            ->with('subject', 'assessmentType') // Eager load relationships
            ->get();

        // Group grades by Subject
        $reportData = [];
        foreach ($grades as $grade) {
            $subjectId = $grade->subject->id;
            $subjectName = $grade->subject->name;

            if (!isset($reportData[$subjectId])) {
                $reportData[$subjectId] = [
                    'name' => $subjectName,
                    'scores' => [],
                    'total' => 0
                ];
            }

            $reportData[$subjectId]['scores'][$grade->assessmentType->name] = $grade->score;
            $reportData[$subjectId]['total'] += $grade->score;
        }

        // Calculate Remarks and Letter Grades (Helper logic)
        foreach ($reportData as $key => $data) {
            $reportData[$key]['grade'] = $this->getLetterGrade($data['total']);
            $reportData[$key]['remark'] = $this->getRemark($data['total']);
        }

        $assessmentTypes = \App\Models\AssessmentType::where('school_id', $session->school_id)->get();
        // Generate PDF
        $pdf = Pdf::loadView('teacher.reports.pdf', compact('student', 'session', 'term', 'reportData', 'assessmentTypes'));
        
        // Download the file
        return $pdf->download($student->admission_number . '_ReportCard.pdf');
    }

    // Helper: Get Letter Grade
    private function getLetterGrade($score) {
        if ($score >= 70) return 'A';
        if ($score >= 60) return 'B';
        if ($score >= 50) return 'C';
        if ($score >= 45) return 'D';
        return 'F';
    }

    // Helper: Get Remark
    private function getRemark($score) {
        if ($score >= 70) return 'Excellent';
        if ($score >= 60) return 'Very Good';
        if ($score >= 50) return 'Credit';
        if ($score >= 45) return 'Pass';
        return 'Fail';
    }
}
