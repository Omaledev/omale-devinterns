<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    protected $fillable = ['assignment_id', 'student_id', 'comments', 'file_path'];

    public function assignment() {
        return $this->belongsTo(Assignment::class);
    }
}
