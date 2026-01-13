<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;


class Section extends Model
{
     use HasFactory;

    protected $fillable = ['school_id', 'class_level_id', 'name', 'capacity', 'is_active'];

    protected static function booted()
    {
        static::addGlobalScope('school', function (Builder $builder) {
            if (session('active_school')) {
                $builder->where($builder->getModel()->getTable() . '.school_id', session('active_school'));
            }
        });
    }

    // School relationship with the Section-a particular Section belongsTo to a  particualr school.
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // ClassLvel relationship with the Section-a particular Section belongsTo to a  particualr ClassLevel.
    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class);
    }


    // Section to students relationship- a Section has many StudentProfiles
    public function studentProfiles()
    {
        return $this->hasMany(StudentProfile::class, 'section_id');
    }


    public function classroomAssignments()
    {
        return $this->hasMany(ClassroomAssignment::class);
    }

    /**
     * Get the attendance records for this section.
     * The inverse of Attendance::section()
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

}
