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

        // --- Get Submitted Assignment IDs ---
        $submittedAssignmentIds = AssignmentSubmission::where('student_id', $user->id)
            ->pluck('assignment_id')
            ->toArray();
        
        // Pending Assignments Count
        // (Assignments for my class + Deadline is in future + NOT in my submitted list)
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

        // Fee Balance
        $totalInvoiced = Invoice::where('student_id', $user->id)->sum('total_amount');
        $totalPaid = Invoice::where('student_id', $user->id)->sum('paid_amount');
        $feeBalance = $totalInvoiced - $totalPaid;

        $stats = [
            'attendance_rate' => $attendanceRate,
            'average_grade' => $averageGrade,
            'pending_assignments' => $pendingAssignmentsCount, 
            'fee_balance' => $feeBalance,
        ];

        // --- Today's Schedule ---
        $today = now()->format('l'); 
        $todaysSchedule = Timetable::where('class_level_id', $studentProfile->class_level_id)
            ->where('weekday', $today)
            ->orderBy('start_time')
            ->with(['subject', 'teacher'])
            ->get();

        // --- Recent Assignments (With Status) ---
        $recentAssignments = Assignment::where('class_level_id', $studentProfile->class_level_id)
            ->latest()
            ->take(5)
            ->with('subject')
            ->get()
            ->map(function($assignment) use ($submittedAssignmentIds) {
                // Check if this specific ID is in our submitted list
                $assignment->is_submitted = in_array($assignment->id, $submittedAssignmentIds);
                $assignment->due_date = Carbon::parse($assignment->deadline);
                return $assignment;
            });

        // --- Upcoming Events  ---
        $upcomingEvents = []; 

        return view('student.dashboard', compact('stats', 'todaysSchedule', 'recentAssignments', 'upcomingEvents'));
    }
}