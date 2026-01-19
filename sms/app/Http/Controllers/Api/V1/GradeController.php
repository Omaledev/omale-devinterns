<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\GradeResource;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Get grades for authenticated student (or their parent)
     */
    public function forStudent(Request $request, $termId = null)
    {
        $user = $request->user();
        
        if (!$user->hasRole('Student')) {
            
            // Check if parent is accessing child's grades
            if ($user->hasRole('Parent')) {
                $studentId = $request->input('student_id');
                
                if (!$studentId) {
                    return response()->json(['message' => 'Student ID is required for parents.'], 400);
                }
                
                // Ensuring parentProfile relation exists in User model
                // And students() relation exists in ParentProfile model
                $isParentOf = $user->parentProfile?->students()->where('users.id', $studentId)->exists();
                
                if (!$isParentOf) {
                    return response()->json(['message' => 'Unauthorized: Not your child.'], 403);
                }
                
                $student = User::findOrFail($studentId);
            } else {
                return response()->json(['message' => 'Unauthorized access.'], 403);
            }
        } else {
            $student = $user;
        }

        $query = Grade::with(['subject', 'assessmentType', 'teacher', 'academicSession', 'term'])
            ->where('student_id', $student->id);
            // ->where('school_id', $student->school_id);

        if ($termId) {
            $query->where('term_id', $termId);
        }

        $grades = $query->orderBy('term_id')
            ->orderBy('subject_id')
            ->latest()
            ->get();

        return response()->json([
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'admission_number' => $student->studentProfile?->admission_number, 
            ],
            'grades' => GradeResource::collection($grades),
            'summary_by_subject' => $this->calculateSubjectSummary($grades),
            'term_summary' => $this->calculateTermSummary($grades),
        ]);
    }

    /**
     * Get grades for teacher's subjects
     */
    public function forTeacher(Request $request)
    {
        $user = $request->user();
        
        if (!$user->hasRole('Teacher')) {
            return response()->json(['message' => 'Only teachers can access grades.'], 403);
        }

        $classId = $request->input('class_id');
        $subjectId = $request->input('subject_id');
        $termId = $request->input('term_id');

        $query = Grade::with(['student', 'subject', 'assessmentType', 'term'])
            ->where('teacher_id', $user->id);
            // ->where('school_id', $user->school_id);

        if ($classId) $query->where('class_level_id', $classId);
        if ($subjectId) $query->where('subject_id', $subjectId);
        if ($termId) $query->where('term_id', $termId);

        $grades = $query->orderBy('student_id')
            ->orderBy('subject_id')
            ->get();

        // Using Resource Collection to ensure data privacy/formatting
        return GradeResource::collection($grades);
    }

    /**
     * Helper: Calculate Subject Averages Correctly
     */
    private function calculateSubjectSummary($grades)
    {
        $summary = [];
        
        foreach ($grades->groupBy('subject_id') as $subjectId => $subjectGrades) {
            $subject = $subjectGrades->first()->subject;
            
            // Weighted Average Calculation
            // Formula: (Score / MaxScore) * Weight
            $totalWeightedScore = 0;
            $totalPossibleWeight = 0;

            foreach ($subjectGrades as $grade) {
                $maxScore = $grade->assessmentType->max_score ?? 100;
                $weight = $grade->assessmentType->weight ?? 0;

                if ($maxScore > 0) {
                    $normalizedScore = ($grade->score / $maxScore) * 100; 
                    $totalWeightedScore += ($normalizedScore * ($weight / 100)); 
                    $totalPossibleWeight += $weight;
                }
            }

            // Calculate final percentage based on weights attempting so far
            $currentAverage = $totalPossibleWeight > 0 
                ? ($totalWeightedScore / ($totalPossibleWeight/100)) 
                : 0;

            $summary[] = [
                'subject_id' => $subjectId,
                'subject_name' => $subject->name,
                'subject_code' => $subject->code,
                'average_score' => round($subjectGrades->avg('score'), 2), 
                'weighted_percentage' => round($currentAverage, 2), 
                'total_assessments' => $subjectGrades->count(),
            ];
        }

        return $summary;
    }

    private function calculateTermSummary($grades)
    {
        return $grades->groupBy('term_id')->map(function($termGrades, $termId) {
            $term = $termGrades->first()->term;
            return [
                'term_id' => $termId,
                'term_name' => $term->name ?? 'Unknown Term',
                'total_subjects' => $termGrades->unique('subject_id')->count(),
                'total_assessments' => $termGrades->count(),
            ];
        })->values();
    }
}