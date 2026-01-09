<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['thread_id', 'user_id', 'body', 'attachment_path', 'read_at'];

    // Protected casts for dates
    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function thread() {
        return $this->belongsTo(Thread::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}