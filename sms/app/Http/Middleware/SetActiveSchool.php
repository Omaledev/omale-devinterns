<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SetActiveSchool
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Getting the authenticated user
        $user = Auth::user();

        // If user is logged in and has a school_id, set active school
        if ($user && $user->school_id) {
            session(['active_school' => $user->school_id]);
        }

        // If SuperAdmin, allow them to switch schools
        if ($user && $user->hasRole('SuperAdmin') && $request->has('school_id')) {
            session(['active_school' => $request->school_id]);
        }

        return $next($request);

    }

}
