<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        // Log to see which data is coming through
    \Log::info('Registration data:', $data);
        return Validator::make($data, [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'role' => ['required', 'string', 'in:Student,Parent,Teacher'],
        'admission_number' => ['nullable', 'string', 'max:255'],
        'employee_id' => ['nullable', 'string', 'max:255'],

        // return Validator::make($data, [
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'password' => ['required', 'string', 'min:8', 'confirmed'],
        //     'role' => ['required', 'string', 'in:Student,Parent,Teacher'],
        //     'admission_number' => ['required_if:role,Student', 'string', 'max:255'],
        //     'employee_id' => ['required_if:role,Teacher', 'string', 'max:255'],
        // ]);
    ]);
    }

    protected function create(array $data)
    {

        //  dd($data);
        // Create the user first
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'admission_number' => $data['admission_number'] ?? null,
            'employee_id' => $data['employee_id'] ?? null,
        ]);

         // Assigning the selected role
        $user->assignRole($data['role']);

        return $user;
    }
}
