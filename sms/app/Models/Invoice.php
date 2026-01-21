<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id', 'student_id', 'term_id', 'academic_session_id', 
        'invoice_number', 'total_amount', 'paid_amount', 'status'
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
    
    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    /**
     * Get the payments for the invoice.
     */
    public function payments() {
        return $this->hasMany(Payment::class);
    }

    public function getBalanceAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }  

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}