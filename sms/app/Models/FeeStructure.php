<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;

    protected $fillable = ['school_id', 'class_level_id', 'term_id', 'name', 'amount'];

    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
}