<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;


class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'school_id', 'class_level_id', 'section_id', 'student_id', 'admission_date', 'date_of_birth', 'gender', 'address', 'contact', 'is_active'];

    protected $casts = [
        'admission_date' => 'date',
        'date_of_birth' => 'date',
    ];

    protected static function booted()
    {
        static::addGlobalScope('school', function (Builder $builder) {
            if (session('active_school')) {
                $builder->where('school_id', session('active_school'));
            }
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function parents()
    {
        return $this->belongsToMany(ParentProfile::class, 'parent_student', 'student_id', 'parent_id')
                    ->withPivot('relationship', 'is_primary')
                    ->withTimestamps();
    }
}
