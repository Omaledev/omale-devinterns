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
        'phone',
        'principal_name'
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'address' => 'required|string',
        'email' => 'required|email|unique:schools',
        'phone' => 'nullable|string',
        'principal_name' => 'nullable|string|max:255'
    ];

    public function users() {
        return $this->hasMany(User::class);
    }


}
