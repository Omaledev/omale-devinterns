<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentTeacherMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'student_id',
        'teacher_id',
        'subject',
        'scheduled_at',
        'status',
        'notes',
        'is_virtual',
        'meeting_link',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'is_virtual' => 'boolean',
    ];

    // Relationship to the Parent (User)
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // Relationship to the Student (User)
    // This allows: $meeting->student->name
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Relationship to the Teacher (TeacherProfile)
    // This allows: $meeting->teacher->user->name
    public function teacher()
    {
        return $this->belongsTo(TeacherProfile::class, 'teacher_id');
    }
}