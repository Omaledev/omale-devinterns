<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\ClassLevel;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Getting the Teacher Profile
        $teacherProfile = TeacherProfile::where('user_id', $user->id)->first();

        // Safety check: if no profile exists yet
        if (!$teacherProfile) {
            $stats = [
                'total_students' => 0,
                'pending_assessments' => 0,
                'todays_classes' => 0,
                'unread_messages' => 0,
                'subjects' => []
            ];
            return view('teacher.dashboard', compact('stats'));
        }

        // Get the Classes assigned to this teacher
        $classIds = $teacherProfile->assignedClasses()->pluck('class_levels.id');

        // COUNT STUDENTS
        // i look for users with 'Student' role 
        // AND check their 'studentProfile' to see if they are in the teacher's classes
        $totalStudents = User::role('Student')
            ->whereHas('studentProfile', function($q) use ($classIds) {
                $q->whereIn('class_level_id', $classIds);
            })
            ->count();

        // Prepare the Stats Array
        $stats = [
            'total_students' => $totalStudents, 
            
            // Implement the logic for these later:
            'pending_assessments' => 0, 
            'todays_classes' => 0,      
            'unread_messages' => 0,     
            'subjects' => []            
        ];

        // Passing data to the view
        return view('teacher.dashboard', compact('stats'));
    }
}