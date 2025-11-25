<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// class RegisterController extends Controller
// {
//     use RegistersUsers;

//     protected $redirectTo = '/home';

//     public function __construct()
//     {
//         $this->middleware('guest');
//     }

//     protected function validator(array $data)

//     {
//         // Log to see which data is coming through
//     \Log::info('Registration data:', $data);
//         return Validator::make($data, [
//         'name' => ['required', 'string', 'max:255'],
//         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//         'password' => ['required', 'string', 'min:8', 'confirmed'],
//         'role' => ['required', 'string', 'in:Student,Parent,Teacher'],
//         'admission_number' => ['nullable', 'string', 'max:255'],
//         'employee_id' => ['nullable', 'string', 'max:255'],

//         // return Validator::make($data, [
//         //     'name' => ['required', 'string', 'max:255'],
//         //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//         //     'password' => ['required', 'string', 'min:8', 'confirmed'],
//         //     'role' => ['required', 'string', 'in:Student,Parent,Teacher'],
//         //     'admission_number' => ['required_if:role,Student', 'string', 'max:255'],
//         //     'employee_id' => ['required_if:role,Teacher', 'string', 'max:255'],
//         // ]);
//     ]);
//     }

//     protected function create(array $data)
//     {

//         //  dd($data);
//         // Create the user first
//         $user = User::create([
//             'name' => $data['name'],
//             'email' => $data['email'],
//             'password' => Hash::make($data['password']),
//             'admission_number' => $data['admission_number'] ?? null,
//             'employee_id' => $data['employee_id'] ?? null,
//         ]);

//          // Assigning the selected role
//         $user->assignRole($data['role']);

//         return $user;
//     }
// }


class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        \Log::info('Registration data:', $data);
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:Student,Parent,Teacher'],
            'school_id' => ['required', 'exists:schools,id'], 
        ];

        // Role-specific fields
        if (isset($data['role']) && $data['role'] === 'Student') {
            $rules['admission_number'] = ['required', 'string', 'max:255', 'unique:users'];
        }

        if (isset($data['role']) && $data['role'] === 'Teacher') {
            $rules['employee_id'] = ['required', 'string', 'max:255', 'unique:users'];
        }

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'admission_number' => $data['admission_number'] ?? null,
            'employee_id' => $data['employee_id'] ?? null,
            'school_id' => $data['school_id'], 
        ];

        if ($data['role'] === 'Student') {
            $userData['admission_number'] = $data['admission_number'];
        }

        if ($data['role'] === 'Teacher') {
            $userData['employee_id'] = $data['employee_id'];
        }

        $user = User::create($userData);
        $user->assignRole($data['role']);

        return $user;
    }

    public function showRegistrationForm()
    {
        $schools = School::all(); 
        return view('auth.register', compact('schools'));
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        \Log::info('User created after registration:', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'roles' => $user->getRoleNames()->toArray(),
            'school_id' => $user->school_id
        ]);

        $this->guard()->login($user);

        // Store school_id in session
        if ($user->school_id) {
            session(['current_school_id' => $user->school_id]);
        }

        // Redirect based on role 
        return $this->redirectToRoleBasedDashboard($user);
    }

    // Based on role redirect
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