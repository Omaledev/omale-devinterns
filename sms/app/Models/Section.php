<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;


class Section extends Model
{
     use HasFactory;

    protected $fillable = ['school_id', 'class_level_id', 'name', 'capacity', 'is_active'];

    protected static function booted()
    {
        static::addGlobalScope('school', function (Builder $builder) {
            if (session('active_school')) {
                $builder->where('school_id', session('active_school'));
            }
        });
    }

    // School relationship with the Section-a particular Section belongsTo to a  particualr school.
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // ClassLvel relationship with the Section-a particular Section belongsTo to a  particualr ClassLevel.
    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class);
    }

}
