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
        'bank_name',      
        'account_name',   
        'account_number',
        'phone',
        'principal_name',
        'is_active',
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'address' => 'required|string',
        'email' => 'required|email|unique:schools',
        'phone' => 'nullable|string',
        'principal_name' => 'nullable|string|max:255'
    ];

    // Helper to check if school is active
    public function isActive()
    {
        return $this->is_active === 1 || $this->is_active === true;
    }

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

    public function feeStructures()
    {
        return $this->hasMany(FeeStructure::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function parentProfiles()
    {
        return $this->hasMany(ParentProfile::class);
    }


}
