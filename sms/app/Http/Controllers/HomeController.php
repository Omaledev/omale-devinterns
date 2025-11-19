<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (auth()->check()) {
            $user = auth()->user();

            \Log::info('User roles on login:', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'roles' => $user->getRoleNames()->toArray()
            ]);

            // Redirecting users based on their role
            if ($user->hasRole('SuperAdmin')) {
                return redirect()->route('superadmin.dashboard');
            } elseif ($user->hasRole('SchoolAdmin')) {
                return redirect()->route('schooladmin.dashboard'); 
            } elseif ($user->hasRole('Teacher')) {
                return redirect()->route('teacher.dashboard');
            } elseif ($user->hasRole('Student')) {
                return redirect()->route('student.dashboard');
            } elseif ($user->hasRole('Parent')) {
                return redirect()->route('parent.dashboard');
            } elseif ($user->hasRole('Bursar')) {
                return redirect()->route('bursar.dashboard');
            }

            \Log::warning('User has no recognized role:', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'all_roles' => $user->getRoleNames()->toArray()
            ]);

            return view('home')->with('error', 'No recognized role assigned. Please contact administrator.');
        }

        return redirect('/login');
    }
}