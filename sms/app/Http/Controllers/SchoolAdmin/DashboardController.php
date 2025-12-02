<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
         $school = auth()->user()->school;

        $stats = [
            'total_students' => User::role('Student')->where('school_id', $school->id)->count(),
            'total_teachers' => User::role('Teacher')->where('school_id', $school->id)->count(),
            'total_parents' => User::role('Parent')->where('school_id', $school->id)->count(),
            'total_classes' => 0, 
            'pending_approvals' => User::where('school_id', $school->id)->count(),
        ];

        // Debug: Log to see if this is being called
        \Log::info('SchoolAdmin Dashboard accessed', [
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'stats' => $stats
        ]);


        return view('schooladmin.dashboard', compact('stats'));
    }

}
