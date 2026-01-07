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

        // General Stats
        $totalExpected = Invoice::where('school_id', $schoolId)->sum('total_amount');
        $totalCollected = Invoice::where('school_id', $schoolId)->sum('paid_amount');
        $outstanding = $totalExpected - $totalCollected;
        
        $collectionRate = $totalExpected > 0 ? round(($totalCollected / $totalExpected) * 100) : 0;
        
        // Pending invoices 
        $pendingInvoicesCount = Invoice::where('school_id', $schoolId)
            ->where('status', '!=', 'PAID')
            ->count();

        // Recent Payments 
        $recentPayments = Payment::with('invoice.student')
            ->whereHas('invoice', fn($q) => $q->where('school_id', $schoolId))
            ->latest('payment_date')
            ->take(5)
            ->get();

        // Outstanding Fees 
        $outstandingFees = Invoice::where('school_id', $schoolId)
            ->whereColumn('paid_amount', '<', 'total_amount')
            ->with(['student.studentProfile.classLevel'])
            ->orderByRaw('(total_amount - paid_amount) DESC')
            ->take(5)
            ->get();

        // Fee Collection Summary By Class 
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

        $stats = [
            'total_collection' => $totalCollected,
            'pending_invoices' => $pendingInvoicesCount,
            'outstanding_balance' => $outstanding,
            'collection_rate' => $collectionRate,
            'recent_payments' => $recentPayments->count() 
        ];

        return view('bursar.dashboard', compact('stats', 'recentPayments', 'outstandingFees', 'classSummary'));
    }
}
