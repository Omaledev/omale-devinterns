<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;

class SuperAdminController extends Controller
{
    public function index()
    {
         $stats = [
            'total_schools' => School::count(),
            'total_users' => User::count(),
            'total_students' => User::role('Student')->count(),
            'total_teachers' => User::role('Teacher')->count(),
            'total_parents' => User::role('Parent')->count(),
            'active_sessions' => 0, // this is for implementing session tracking
            'system_alerts' => 2, // alerts
            'school_growth' => 15, // Percentage growth
            'revenue' => 2500000, // revenue
        ];

        $recentSchools = School::latest()->take(5)->get();

        return view('superadmin.dashboard', compact('stats', 'recentSchools'));
    }

}
