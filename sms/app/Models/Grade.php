<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_level_id',
        'subject_id',
        'assessment_type_id',
        'teacher_id',
        'academic_session_id',
        'term_id',
        'score',
        'is_locked'
    ];

    /**
     * Relationship: A grade belongs to a specific Student.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Relationship: A grade belongs to a specific Teacher (who entered it).
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Relationship: A grade belongs to a Subject.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relationship: A grade belongs to a Class Level .
     */
    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class);
    }

    /**
     * Relationship: A grade belongs to an Assessment Type .
     */
    public function assessmentType()
    {
        return $this->belongsTo(AssessmentType::class);
    }

    /**
     * Relationship: A grade belongs to an Academic Session.
     */
    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    /**
     * Relationship: A grade belongs to a Term.
     */
    public function term()
    {
        return $this->belongsTo(Term::class);
    }
}
