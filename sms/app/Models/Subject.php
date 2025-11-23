<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['school_id', 'name', 'code', 'description', 'is_active'];


    // This is the global scope for school isolation
    protected static function booted()
    {
        static::addGlobalScope('school', function (Builder $builder) {
            if (session('active_school')) {
                $builder->where('school_id', session('active_school'));
            }
        });
    }

    // Subject relationship with a particular school-a particular Subject belongsTo to a  particualr School.
    public function school()
    {
        return $this->belongsTo(School::class);
    }

}
