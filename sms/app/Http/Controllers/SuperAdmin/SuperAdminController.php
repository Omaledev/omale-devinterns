<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon; 

class SuperAdminController extends Controller
{
    public function index()
    {

        // Helper function to calculate growth %
        $getGrowth = function($model, $role = null) {
            $now = now();
            $lastMonth = now()->subMonth();

            // Query Builder
            $query = $model::query();
            if ($role) $query->role($role);
            
            $currentCount = $query->count();
            $previousCount = $query->where('created_at', '<', $lastMonth)->count();

            if ($previousCount == 0) return $currentCount > 0 ? 100 : 0;
            
            return round((($currentCount - $previousCount) / $previousCount) * 100, 1);
        };

        // Calculate Revenue Growth (Sum of payments this month vs last month)
        $currentRevenue = \App\Models\Payment::sum('amount');
        $lastMonthRevenue = \App\Models\Payment::where('payment_date', '<', now()->subMonth())->sum('amount');
        
        $revenueGrowth = 0;
        if ($lastMonthRevenue > 0) {
            $revenueGrowth = round((($currentRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1);
        } elseif ($currentRevenue > 0) {
            $revenueGrowth = 100;
        }

        // Stats
        $stats = [

            // Basic Counts (Required for Progress Bars)
            'total_users'    => \App\Models\User::count(),
            'total_schools'  => \App\Models\School::count(),
            
            'total_students' => \App\Models\User::role('Student')->count(),
            'total_teachers' => \App\Models\User::role('Teacher')->count(),
            'total_parents'  => \App\Models\User::role('Parent')->count(),
            'revenue'        => $currentRevenue,

            // Growth Percentages (Calculated dynamically)
            'student_growth' => $getGrowth(\App\Models\User::class, 'Student'),
            'teacher_growth' => $getGrowth(\App\Models\User::class, 'Teacher'),
            'parent_growth'  => $getGrowth(\App\Models\User::class, 'Parent'),
            'revenue_growth' => $revenueGrowth,
        ];

        $recentSchools = School::latest()->take(5)->get();

        // CHART DATA CALCULATION (Last 7 Months)
        $chartLabels = [];
        $schoolGrowthData = [];
        $userGrowthData = [];

        // Date range (7 months ago to now)
        $startDate = now()->subMonths(6)->startOfMonth();

        // Fetch raw creation dates 
        $rawSchools = School::select('created_at')->where('created_at', '>=', $startDate)->get();
        $rawUsers = User::select('created_at')->where('created_at', '>=', $startDate)->get();

        // Loop through the last 7 months
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M'); 

            // Adding Label
            $chartLabels[] = $monthName;

            // Counting Schools created in this specific month
            $schoolCount = $rawSchools->filter(function ($item) use ($date) {
                return $item->created_at->format('Y-m') === $date->format('Y-m');
            })->count();

            // Count Users created in this specific month
            $userCount = $rawUsers->filter(function ($item) use ($date) {
                return $item->created_at->format('Y-m') === $date->format('Y-m');
            })->count();

            $schoolGrowthData[] = $schoolCount;
            $userGrowthData[] = $userCount;
        }

        // Inactive Schools (Schools with 0 students)
        $inactiveSchoolsCount = School::doesntHave('users')->count();

        // New Schools Today
        $newSchoolsToday = School::whereDate('created_at', Carbon::today())->count();

        // Server Storage (Real functionality)
        $diskTotal = disk_total_space('/'); 
        $diskFree = disk_free_space('/');
        $diskUsed = $diskTotal - $diskFree;
        $diskPercentage = round(($diskUsed / $diskTotal) * 100);

        // Growth Targets (Gamification)
        $userGoal = 1000; // Set a goal (e.g., 1000 users)
        $userProgress = min(round(($stats['total_users'] / $userGoal) * 100), 100);

        $schoolGoal = 50; // Setting a goal (50 schools)
        $schoolProgress = min(round(($stats['total_schools'] / $schoolGoal) * 100), 100);

        $platformHealth = [
            'inactive_schools' => $inactiveSchoolsCount,
            'new_today' => $newSchoolsToday,
            'disk_usage' => $diskPercentage,
            'user_progress' => $userProgress,
            'school_progress' => $schoolProgress
        ];

        return view('superadmin.dashboard', compact(
            'stats', 
            'recentSchools', 
            'chartLabels',      
            'schoolGrowthData', 
            'userGrowthData' ,
            'platformHealth'   
        ));
    }
}