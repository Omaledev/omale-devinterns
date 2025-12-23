<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'email',
        'phone',
        'principal_name'
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'address' => 'required|string',
        'email' => 'required|email|unique:schools',
        'phone' => 'nullable|string',
        'principal_name' => 'nullable|string|max:255'
    ];

    public function users() {
        return $this->hasMany(User::class);
    }

    public function academicSessions()
    {
        return $this->hasMany(AcademicSession::class);
    }

    public function terms()
    {
        return $this->hasMany(Term::class);
    }

    public function studentProfiles()
    {
        return $this->hasMany(StudentProfile::class);
    }

    public function classLevels()
    {
        return $this->hasMany(ClassLevel::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function AssessmentType() {
        return $this->hasMany(AssessmentType::class);
    }


}
