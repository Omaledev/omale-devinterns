<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['title', 'message', 'target_role', 'school_id', 'publish_at', 'expires_at', 'created_by'];

    protected $casts = [
        'publish_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Scope to handle "Schedule and expire" requirements 
    public function scopeActive($query)
    {
        return $query->where('publish_at', '<=', now())
                     ->where(function($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>=', now());
                     });
    }

    // Scope for Role Targeting 
    public function scopeForUser($query, $user)
    {
        return $query->where(function($q) use ($user) {
            $q->whereNull('target_role') 
              ->orWhere('target_role', $user->role); 
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
