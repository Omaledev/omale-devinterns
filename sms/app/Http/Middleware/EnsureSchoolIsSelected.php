<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSchoolIsSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Skiping for non-authenticated requests
        if (!auth()->check()) {
            return $next($request);
        }

        // ensuring user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'error_code' => 'UNAUTHENTICATED',
            ], 401); // 401 Unauthorized
        }
        
        $user = $request->user();
        
        // Check if user has a school_id
        if (empty($user->school_id)) {
            return response()->json([
                'success' => false,
                'message' => 'No school assigned to user. Please contact administrator.',
                'error_code' => 'NO_SCHOOL_ASSIGNED',
            ], 403);
        }

        return $next($request);
    
    }
}
