<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{

    use HasFactory;

    // Mass assignable attributes
    protected $fillable = [
        'invoice_id', 
        'amount', 
        'payment_method', 
        'reference_number', 
        'payment_date', 
        'recorded_by',
        'proof_file_path',  
        'status',           
        'bursar_remarks'
    ];

    /**
     * Get the invoice that owns the payment.
     */
    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function isVerified() {
        return $this->status === 'approved';
    }
}
