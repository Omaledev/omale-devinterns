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
}