<?php

namespace App\Http\Controllers\Bursar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;

        // Summary Cards Data
        $totalExpected = Invoice::where('school_id', $schoolId)->sum('total_amount');
        $totalCollected = Invoice::where('school_id', $schoolId)->sum('paid_amount');
        $totalOutstanding = $totalExpected - $totalCollected;

        // Collection Report (Group payments by Date)
        $dailyCollections = Payment::whereHas('invoice', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->selectRaw('DATE(payment_date) as date, sum(amount) as total')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // Outstanding Balances List (Debtors)
        $debtors = Invoice::where('school_id', $schoolId)
            ->whereColumn('paid_amount', '<', 'total_amount') 
            ->with('student')
            ->get();

        return view('bursar.reports.index', compact(
            'totalExpected', 
            'totalCollected', 
            'totalOutstanding', 
            'dailyCollections', 
            'debtors'
        ));
    }

    // Export Feature (CSV)
    public function exportDebtors()
    {
        $schoolId = auth()->user()->school_id;
        $debtors = Invoice::where('school_id', $schoolId)
            ->whereColumn('paid_amount', '<', 'total_amount')
            ->with('student')
            ->get();

        $filename = "outstanding_balances_" . date('Y-m-d') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($debtors) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Student Name', 'Invoice No', 'Total Amount', 'Paid Amount', 'Balance Due']);

            foreach ($debtors as $debtor) {
                fputcsv($file, [
                    $debtor->student->name,
                    $debtor->invoice_number,
                    $debtor->total_amount,
                    $debtor->paid_amount,
                    $debtor->total_amount - $debtor->paid_amount
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function outstanding()
    {
        $schoolId = auth()->user()->school_id;

        // Should get invoices where paid < total
        $debtors = Invoice::where('school_id', $schoolId)
            ->whereColumn('paid_amount', '<', 'total_amount')
            ->with(['student.studentProfile.classLevel'])
            ->orderByRaw('(total_amount - paid_amount) DESC') // Highest debt first
            ->paginate(20);

        return view('bursar.reports.outstanding', compact('debtors'));
    }

    public function collections(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $filter = $request->get('filter', 'this_year'); // default filter

        $query = Payment::whereHas('invoice', fn($q) => $q->where('school_id', $schoolId));

        // Simple date filtering logic
        if ($filter == 'this_month') {
            $query->whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year);
        } elseif ($filter == 'last_month') {
            $query->whereMonth('payment_date', now()->subMonth()->month)
                ->whereYear('payment_date', now()->subMonth()->year);
        } elseif ($filter == 'this_year') {
            $query->whereYear('payment_date', now()->year);
        }

        $payments = $query->orderBy('payment_date')->get();

        // Preparing Chart Data (Group by Month for Yearly view, or Day for Monthly view)
        $chartData = $payments->groupBy(function($val) use ($filter) {
            return $filter == 'this_year' 
                ? Carbon::parse($val->payment_date)->format('M Y') 
                : Carbon::parse($val->payment_date)->format('d M');
        })->map(fn($row) => $row->sum('amount'));

        $totalCollected = $payments->sum('amount');
        $transactionCount = $payments->count();

        return view('bursar.reports.collections', compact('chartData', 'totalCollected', 'transactionCount', 'filter'));
    }
}