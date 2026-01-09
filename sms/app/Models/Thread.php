<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{

    protected $fillable = [ 'subject'];
        
    public function messages() {
        return $this->hasMany(Message::class);
   }
// Helper to get latest message for the inbox view
   public function latestMessage() {
    return $this->hasOne(Message::class)->latestOfMany();
   }

   public function participants()
    {
        return $this->belongsToMany(User::class, 'thread_user')
                    ->withPivot('last_read_at') 
                    ->withTimestamps();
    }
}
