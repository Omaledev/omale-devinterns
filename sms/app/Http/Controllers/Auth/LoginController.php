<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // Overriding the authenticated method to redirect based on role
    protected function authenticated(Request $request, $user)
    {
        return $this->redirectToRoleBasedDashboard($user);
    }

    // Custom method to handle the redirection based on role
    protected function redirectToRoleBasedDashboard($user)
    {
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

        return redirect('/home');
    }

}
