<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class TeacherProfile extends Model
{
     use HasFactory;

    protected $fillable = ['user_id', 'school_id', 'employee_id', 'qualification', 'specialization', 'joining_date', 'subjects', 'is_active'];

    protected $casts = [
        'subjects' => 'array',
        'joining_date' => 'date',
    ];

    protected static function booted()
    {
        static::addGlobalScope('school', function (Builder $builder) {
            if (session('active_school')) {
                $builder->where('school_id', session('active_school'));
            }
        });
    }
    // Realtionship-a particuler teacher belongs a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Realtionship-a particuler teacher belongs a school
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classroomAssignments()
    {
        return $this->hasMany(ClassroomAssignment::class, 'teacher_id');
    }

}
