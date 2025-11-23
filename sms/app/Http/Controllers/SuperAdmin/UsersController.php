<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;

class UsersController extends Controller
{
    public function index()
    {
        // Getting all users with their relationships
        $users = User::with(['school', 'roles'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Getting all schools for the filter dropdown
        $schools = School::all();

        return view('superadmin.users.index', compact('users', 'schools'));
    }
}
