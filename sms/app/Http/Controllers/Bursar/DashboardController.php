<?php

namespace App\Http\Controllers\Bursar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use APP\Models\User;

class DashboardController extends Controller
{
    public function index()
{
    $school = auth()->user()->school;

    $stats = [
        'total_students' => User::role('Student')->where('school_id', $school->id)->count(),
        'total_teachers' => User::role('Teacher')->where('school_id', $school->id)->count(),
        'total_parents' => User::role('Parent')->where('school_id', $school->id)->count(),
        'total_bursars' => User::role('Bursar')->where('school_id', $school->id)->count(),
        'total_classes' => 0, 
        'pending_approvals' => User::where('school_id', $school->id)->count(),
    ];

    return view('schooladmin.dashboard', compact('stats'));
}
}
