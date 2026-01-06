<?php

namespace App\Http\Controllers\Bursar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;

class DashboardController extends Controller
{
public function index()
    {
        $school = auth()->user()->school;

        // Basic Stats
        $stats = [
            'total_students' => \App\Models\User::role('Student')->whereHas('studentProfile', fn($q) => $q->where('school_id', $school->id))->count(),
            'total_teachers' => \App\Models\User::role('Teacher')->whereHas('teacherProfile', fn($q) => $q->where('school_id', $school->id))->count(),
            'total_parents' => \App\Models\User::role('Parent')->whereHas('parentProfile', fn($q) => $q->where('school_id', $school->id))->count(),
            'pending_invoices' => \App\Models\Invoice::where('school_id', $school->id)->where('status', 'UNPAID')->count(),
            
            // To be calculated properly for my Day 16 & 17 task
            'total_collection' => 0, 
            'outstanding_balance' => \App\Models\Invoice::where('school_id', $school->id)->sum('total_amount') - \App\Models\Invoice::where('school_id', $school->id)->sum('paid_amount'),
            'collection_rate' => 0,
        ];

        $recentPayments = Payment::with('invoice.student')
            ->whereHas('invoice', function($q) use ($school) {
                $q->where('school_id', $school->id);
            })
            ->latest('payment_date')
            ->take(5)
            ->get();

        // Outstanding Fees-fetching this from the Invoices
        $outstandingFees = \App\Models\Invoice::where('school_id', $school->id)
            ->where('status', '!=', 'PAID')
            ->with(['student.studentProfile.classLevel'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function($invoice) {
                // Temporary 'balance' property since the view expects it
                $invoice->balance = $invoice->total_amount - $invoice->paid_amount;
                return $invoice;
            });

        // Class Summary (Empty for now)
        $classSummary = [];

        return view('bursar.dashboard', compact('stats', 'recentPayments', 'outstandingFees', 'classSummary'));
    }
}
