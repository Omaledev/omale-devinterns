<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Assignment;
use App\Models\AssignmentSubmission; 
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Book;
use App\Models\Invoice;
use App\Models\Timetable;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $studentProfile = $user->studentProfile;

        // Calculating fees
        // This first because invoices are linked to the User
        $totalInvoiced = Invoice::where('student_id', $user->id)->sum('total_amount');
        $totalPaid = Invoice::where('student_id', $user->id)->sum('paid_amount');
        $feeBalance = $totalInvoiced - $totalPaid;

        // Checking if profile exists
        if (!$studentProfile) {
            // If no profile exists, return specific "Empty" stats 
            $stats = [
                'attendance_rate' => 0,
                'average_grade' => 'N/A',
                'pending_assignments' => 0, 
                'fee_balance' => $feeBalance,
            ];
            
            $todaysSchedule = collect();
            $recentAssignments = collect();
            $upcomingEvents = [];

            // Return view with a warning message
            return view('student.dashboard', compact('stats', 'todaysSchedule', 'recentAssignments', 'upcomingEvents'))
                ->with('error', 'Student profile not found. Please contact the administrator to assign you to a class.');
        }

        // PROFILE EXISTS: Run normal logic 
        // Get Submitted Assignment IDs
        $submittedAssignmentIds = AssignmentSubmission::where('student_id', $user->id)
            ->pluck('assignment_id')
            ->toArray();
        
        // Pending Assignments Count
        $pendingAssignmentsCount = Assignment::where('class_level_id', $studentProfile->class_level_id)
            ->where('deadline', '>=', now())
            ->whereNotIn('id', $submittedAssignmentIds) 
            ->count();

        // Attendance
        $totalAttendance = Attendance::where('student_id', $studentProfile->id)->count();
        $presentCount = Attendance::where('student_id', $studentProfile->id)->where('status', 'present')->count();
        $attendanceRate = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100) : 0;

        // Average Grade
        $averageGrade = Grade::where('student_id', $studentProfile->id)->avg('score');
        $averageGrade = $averageGrade ? number_format($averageGrade, 1) : 'N/A';

        $stats = [
            'attendance_rate' => $attendanceRate,
            'average_grade' => $averageGrade,
            'pending_assignments' => $pendingAssignmentsCount, 
            'fee_balance' => $feeBalance,
        ];

        // Today's Schedule
        $today = now()->format('l'); 
        $todaysSchedule = Timetable::where('class_level_id', $studentProfile->class_level_id)
            ->where('weekday', $today)
            ->orderBy('start_time')
            ->with(['subject', 'teacher'])
            ->get();

        // Recent Assignments
        $recentAssignments = Assignment::where('class_level_id', $studentProfile->class_level_id)
            ->latest()
            ->take(5)
            ->with('subject')
            ->get()
            ->map(function($assignment) use ($submittedAssignmentIds) {
                $assignment->is_submitted = in_array($assignment->id, $submittedAssignmentIds);
                $assignment->due_date = Carbon::parse($assignment->deadline);
                return $assignment;
            });

        $upcomingEvents = []; 

        return view('student.dashboard', compact('stats', 'todaysSchedule', 'recentAssignments', 'upcomingEvents'));
    
    }
}