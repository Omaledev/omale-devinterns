<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['school_id', 'name', 'code', 'description', 'is_active'];


    // This is the global scope for school isolation
    protected static function booted()
    {
        static::addGlobalScope('school', function (Builder $builder) {
            if (session('active_school')) {
                $builder->where('subjects.school_id', session('active_school'));
            }
        });
    }


    // Subject relationship with a particular school-a particular Subject belongsTo to a  particualr School.
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Relationship with classroom assignments (teachers teaching this subject)
    public function classroomAssignments()
    {
        return $this->hasMany(ClassroomAssignment::class, 'subject_id');
    }


    public function teachers()
    {
        return $this->belongsToMany(User::class, 'classroom_assignments', 'subject_id', 'teacher_id')
                    ->withTimestamps();
    }
    

    public function classLevels()
    {
        return $this->belongsToMany(ClassLevel::class, 'classroom_assignments', 'subject_id', 'class_level_id')
                    ->withTimestamps();
    }
}
