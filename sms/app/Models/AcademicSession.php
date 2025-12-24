<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicSession extends Model
{

    use HasFactory;

    protected $fillable = [
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

    public function school()
    {
        return $this->belongsTo(School::class);
    }
    
    public function terms()
    {
        return $this->hasMany(Term::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the invoices generated in this academic session.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    
}


