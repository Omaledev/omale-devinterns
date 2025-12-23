<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
class AssessmentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'weight',
        'max_score'
    ];

    public function school(){
       return $this->belongsTo(School::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('school', function (Builder $builder) {
            if (session('active_school')) {
                $builder->where($builder->getModel()->getTable() . '.school_id', session('active_school'));
            }
        });
    }
}
