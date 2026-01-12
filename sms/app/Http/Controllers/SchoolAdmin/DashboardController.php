<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Attendance;
use App\Models\Grade;        
use App\Models\Announcement; 

class DashboardController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;

        // Stats(top card)
        $stats = [
            'total_students'    => User::role('Student')->where('school_id', $schoolId)->count(),
            'total_teachers'    => User::role('Teacher')->where('school_id', $schoolId)->count(),
            'total_parents'     => User::role('Parent')->where('school_id', $schoolId)->count(),
            'pending_approvals' => 0, 
        ];

        // CHART DATA: Student Growth (Last 6 Months)
        $chartLabels = [];
        $studentGrowthData = [];

        // Fetching creation dates for students in this school (Last 6 months)
        $rawStudents = User::role('Student')
            ->where('school_id', $schoolId)
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->select('created_at')
            ->get();

        // Looping backwards from 5 months ago to today
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M'); 

            $chartLabels[] = $monthName;

            // Counting how many students were created in this specific month
            $count = $rawStudents->filter(function ($student) use ($date) {
                return $student->created_at->format('Y-m') === $date->format('Y-m');
            })->count();

            $studentGrowthData[] = $count;
        }

        // PERFORMANCE METRICS (Middle Cards)
        
        // Academic Excellence (Average Grade Score)
        // Checking if 'Grade' model exists and has 'score' column
        $avgScore = 0;
        if (class_exists(Grade::class)) {
            $avgScore = Grade::whereHas('student', fn($q) => $q->where('school_id', $schoolId))
                ->avg('score') ?? 0;
        }

        // Attendance Rate (Present vs Total)
        $totalAttendance = Attendance::where('school_id', $schoolId)->count();
        $presentAttendance = Attendance::where('school_id', $schoolId)->where('status', 'present')->count();
        $attendanceRate = $totalAttendance > 0 ? ($presentAttendance / $totalAttendance) * 100 : 0;

        // Fee Collection Rate (Paid vs Expected)
        $totalExpected = Invoice::where('school_id', $schoolId)->sum('total_amount');
        $totalPaid = Invoice::where('school_id', $schoolId)->sum('paid_amount');
        $feeRate = $totalExpected > 0 ? ($totalPaid / $totalExpected) * 100 : 0;

        $performance = [
            'academic'   => round($avgScore, 1),
            'attendance' => round($attendanceRate, 1),
            'fees'       => round($feeRate, 1)
        ];

        // RECENT ACTIVITY FEED (Merged Data)
        // Combining latest Users, Payments, and Announcements into one feed
        
        // Latest Users
        $recentUsers = User::where('school_id', $schoolId)
            ->latest()
            ->take(3)
            ->get()
            ->map(function($user) {
                return [
                    'icon'    => 'fas fa-plus text-primary',
                    'message' => "New {$user->getRoleNames()->first()} registered: <strong>{$user->name}</strong>",
                    'time'    => $user->created_at
                ];
            });

        // Latest Payments
        $recentPayments = Payment::whereHas('invoice', fn($q) => $q->where('school_id', $schoolId))
            ->latest('payment_date')
            ->take(3)
            ->get()
            ->map(function($payment) {
                return [
                    'icon'    => 'fas fa-plus text-success',
                    'message' => "Payment received: <strong>₦" . number_format($payment->amount) . "</strong>",
                    'time'    => Carbon::parse($payment->payment_date)
                ];
            });

        // Latest Announcements
        $recentAnnouncements = Announcement::where('school_id', $schoolId)
            ->latest()
            ->take(3)
            ->get()
            ->map(function($announcement) {
                return [
                    'icon'    => 'fas fa-plus text-warning',
                    'message' => "Announcement posted: <strong>" . \Illuminate\Support\Str::limit($announcement->title, 20) . "</strong>",
                    'time'    => $announcement->created_at
                ];
            });

        // Merging all, sort by time (newest first), take top 5
        $recentActivities = $recentUsers
            ->concat($recentPayments)
            ->concat($recentAnnouncements)
            ->sortByDesc('time')
            ->take(5);

        return view('schooladmin.dashboard', compact(
            'stats', 
            'chartLabels', 
            'studentGrowthData', 
            'performance', 
            'recentActivities'
        ));
    }
}