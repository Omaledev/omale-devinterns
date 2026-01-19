<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'score' => $this->score,
            'grade' => $this->calculateGrade(),       
            'percentage' => $this->calculatePercentage(), 
            'is_locked' => (bool)$this->is_locked,
            
            'assessment_type' => $this->whenLoaded('assessmentType', function () {
                return [
                    'id' => $this->assessmentType->id,
                    'name' => $this->assessmentType->name,
                    'max_score' => $this->assessmentType->max_score,
                    'weight' => $this->assessmentType->weight,
                ];
            }),

            'subject' => $this->whenLoaded('subject', function () {
                return [
                    'id' => $this->subject->id,
                    'name' => $this->subject->name,
                    'code' => $this->subject->code,
                ];
            }),

            'teacher' => $this->whenLoaded('teacher', function () {
                return [
                    'id' => $this->teacher->id,
                    'name' => $this->teacher->name,
                ];
            }),

            'academic_session' => $this->whenLoaded('academicSession', function () {
                return [
                    'id' => $this->academicSession->id,
                    'name' => $this->academicSession->name,

                ];
            }),

            'term' => $this->whenLoaded('term', function () {
                return [
                    'id' => $this->term->id,
                    'name' => $this->term->name, 
                ];
            }),

            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }

    /**
     * Custom Helper: Calculate Letter Grade
     */
    private function calculateGrade()
    {
        // Safety check: ensuring relationship is loaded to avoid crash
        if (!$this->relationLoaded('assessmentType')) {
            return 'N/A'; 
        }

        $score = $this->score;
        $max = $this->assessmentType->max_score ?? 100;
        
        if ($max == 0) return 'N/A'; // Prevent division by zero

        $percentage = ($score / $max) * 100;

        if ($percentage >= 70) return 'A'; 
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C';
        if ($percentage >= 45) return 'D';
        return 'F';
    }

    /**
     * Custom Helper: Calculate Percentage
     */
    private function calculatePercentage()
    {
        if (!$this->relationLoaded('assessmentType')) {
            return 0;
        }

        $max = $this->assessmentType->max_score ?? 100;
        
        if ($max == 0) return 0;

        return round(($this->score / $max) * 100, 2);
    }
}