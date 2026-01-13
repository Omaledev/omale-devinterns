<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class ClassLevel extends Model
{
    use HasFactory;

    protected $fillable = ['school_id', 'name', 'description', 'order', 'is_active'];

    // This is the global scope for school isolation
    protected static function booted()
    {
        static::addGlobalScope('school', function (Builder $builder) {
            if (session('active_school')) {
                $builder->where($builder->getModel()->getTable() . '.school_id', session('active_school'));
            }
        });
    
    }

    // School relationship with ClassLevel-a particular Class_Level belong to a school-this relate to the foreignId in Class_Level table
     public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Section relationship with ClassLevel-a particular Class_Level can have many sections-this relate to the foreignId in Sections table
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function classroomAssignments()
    {
        return $this->hasMany(ClassroomAssignment::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_level_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the fee structures for this class level.
     */
    public function feeStructures()
    {
        return $this->hasMany(FeeStructure::class);
    }

    public function studentProfiles()
    {
        return $this->hasMany(StudentProfile::class);
    }
}
