<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User; 
use App\Models\ClassLevel;
use App\Models\Section;
use App\Models\Subject;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'class_level_id',
        'section_id',
        'subject_id',
        'teacher_id',
        'weekday',
        'start_time',
        'end_time',
    ];

    /**
     * A timetable entry belongs to one school.
     */
    public function school()
    {
        return $this->belongsTo(School::class); 
    }

    /**
     * A timetable entry belongs to one class level.
     */
    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class);
    }

    /**
     * A timetable entry belongs to one section.
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * A timetable entry belongs to one subject.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * A timetable entry belongs to one teacher (User).
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
