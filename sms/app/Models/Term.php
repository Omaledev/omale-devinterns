<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AcademicSession;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Term extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'academic_session_id',  
        'school_id',
        'name',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];
    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
