<?php

namespace App\Http\Controllers\Bursar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\ClassLevel;

class DashboardController extends Controller
{
  public function index()
    {
        $schoolId = auth()->user()->school_id;

        // General statis
        $totalExpected = Invoice::where('school_id', $schoolId)->sum('total_amount');
        $totalCollected = Invoice::where('school_id', $schoolId)->sum('paid_amount');
        $outstanding = $totalExpected - $totalCollected;
        
        $collectionRate = $totalExpected > 0 ? round(($totalCollected / $totalExpected) * 100) : 0;
        
        $pendingInvoicesCount = Invoice::where('school_id', $schoolId)
            ->where('status', '!=', 'PAID')
            ->count();

        // 2. Chat data(Last 6 Months Revenue)
        
        // Fetching raw payments first to avoid running 6 separate queries inside the loop
        $rawPayments = Payment::whereHas('invoice', fn($q) => $q->where('school_id', $schoolId))
            ->where('payment_date', '>=', now()->subMonths(5)->startOfMonth())
            ->get();

        $chartLabels = [];
        $chartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthLabel = $date->format('M'); // "Jan", "Feb"
            
            // Filter the raw collection for this specific month
            $monthlySum = $rawPayments->filter(function ($payment) use ($date) {
                return \Carbon\Carbon::parse($payment->payment_date)->isSameMonth($date);
            })->sum('amount');

            $chartLabels[] = $monthLabel;
            $chartData[] = $monthlySum;
        }

        // Table Data (Recent & Outstanding)
        // Recent Payments 
        $recentPayments = Payment::with('invoice.student')
            ->whereHas('invoice', fn($q) => $q->where('school_id', $schoolId))
            ->latest('payment_date')
            ->take(5)
            ->get();

        // Outstanding Fees (Top Debtors)
        $outstandingFees = Invoice::where('school_id', $schoolId)
            ->whereColumn('paid_amount', '<', 'total_amount')
            ->with(['student.studentProfile.classLevel'])
            ->orderByRaw('(total_amount - paid_amount) DESC')
            ->take(5)
            ->get();

        // Class Summary (Bottom Table)
        $classSummary = ClassLevel::where('school_id', $schoolId)
            ->with(['studentProfiles.user.invoices'])
            ->get()
            ->map(function ($class) {
                $studentIds = $class->studentProfiles->pluck('user_id');
                $invoices = Invoice::whereIn('student_id', $studentIds)->get();
                
                $expected = $invoices->sum('total_amount');
                $collected = $invoices->sum('paid_amount');
                
                return (object) [
                    'class_name' => $class->name,
                    'total_students' => $class->studentProfiles->count(),
                    'expected_amount' => $expected,
                    'collected_amount' => $collected,
                    'outstanding_amount' => $expected - $collected,
                    'collection_rate' => $expected > 0 ? round(($collected / $expected) * 100) : 0,
                ];
            });

        //  Return
        $stats = [
            'total_collection' => $totalCollected,
            'pending_invoices' => $pendingInvoicesCount,
            'outstanding_balance' => $outstanding,
            'collection_rate' => $collectionRate,
            'recent_payments' => $recentPayments->count() 
        ];

        return view('bursar.dashboard', compact(
            'stats', 
            'recentPayments', 
            'outstandingFees', 
            'classSummary',
            'chartLabels',  
            'chartData'     
        ));
    }

    public function students(\Illuminate\Http\Request $request)
    {
        $schoolId = auth()->user()->school_id;

        $query = \App\Models\User::role('Student')
            ->where('school_id', $schoolId)
            ->with(['studentProfile.classLevel', 'studentProfile.parents']);

        // CHECKING FOR SEARCH INPUT
        // If the user typed something in the search box:
        if ($request->filled('search')) {
            $term = $request->search;
            
            // Filter by Name OR Email
            $query->where(function($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%");
            });
        }

        // Order and Paginate the results
        $students = $query->orderBy('name')
            ->paginate(10)
            ->withQueryString(); 

        // Sidebar stats
        $stats = [
            'recent_payments' => \App\Models\Payment::whereHas('invoice', fn($q) => $q->where('school_id', $schoolId))->count(),
        ];

        return view('bursar.students.index', compact('students', 'stats'));
    }
}
