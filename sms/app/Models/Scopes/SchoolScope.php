<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class SchoolScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
         // Only apply scope if user is logged in and not SuperAdmin
        if (Auth::check() && !Auth::user()->hasRole('SuperAdmin')) {
            $schoolId = session('active_school', Auth::user()->school_id);

            if ($schoolId) {
                $builder->where('school_id', $schoolId);
            }
        }
    }
}
