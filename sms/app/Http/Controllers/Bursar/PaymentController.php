<?php

namespace App\Http\Controllers\Bursar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;

        $payments = Payment::with('invoice.student')
            ->whereHas('invoice', fn($q) => $q->where('school_id', $schoolId))
            // Sorting by Pending first, then by date
            ->orderByRaw("CASE WHEN status = 'pending' THEN 1 ELSE 2 END")
            ->latest('payment_date')
            ->paginate(15);

        return view('bursar.payments.index', compact('payments'));
    }

    /* Show the form to record a payment.
     */
    public function create($invoiceId)
    {
        // Find invoice or fail
        $invoice = Invoice::findOrFail($invoiceId);
        
        return view('bursar.payments.create', compact('invoice'));
    }
    
    /**
     * Store the payment and update Invoice status.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
        ]);

        // DB Transaction to ensure both Payment and Invoice update happen together
        DB::transaction(function () use ($request) {
            
            $payment = Payment::create([
                'invoice_id' => $request->invoice_id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'payment_date' => $request->payment_date,
                'recorded_by' => auth()->id(), // Track who entered it
            ]);

            //  Update the Invoice Totals
            $invoice = Invoice::findOrFail($request->invoice_id);
            
            // Add new payment to existing paid amount
            $invoice->paid_amount += $request->amount;

            // Determine Status
            if ($invoice->paid_amount >= $invoice->total_amount) {
                $invoice->status = 'PAID';
            } elseif ($invoice->paid_amount > 0) {
                $invoice->status = 'PARTIAL';
            } else {
                $invoice->status = 'UNPAID';
            }

            $invoice->save();
        });

        return redirect()->route('bursar.dashboard')->with('success', 'Payment recorded successfully!');
    }

    /**
     * Show the verification page with the uploaded receipt.
     */
    public function verify($id)
    {
        $payment = Payment::with('invoice.student')->findOrFail($id);
        return view('bursar.payments.verify', compact('payment'));
    }

    /**
     * Approve the payment and update the invoice.
     */
    public function approve($id)
    {
        DB::transaction(function () use ($id) {
            $payment = Payment::with('invoice')->findOrFail($id);

            if($payment->status === 'approved') {
                return; // Prevent double approval
            }

            // Marking Payment as Approved
            $payment->update(['status' => 'approved']);

            // Updating Invoice Balance
            $invoice = $payment->invoice;
            $invoice->paid_amount += $payment->amount;

            // Updating Invoice Status
            if ($invoice->paid_amount >= $invoice->total_amount) {
                $invoice->status = 'PAID';
            } else {
                $invoice->status = 'PARTIAL';
            }
            $invoice->save();
        });

        return redirect()->route('bursar.payments.index')->with('success', 'Payment verified and Invoice updated!');
    }
    
    public function decline(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => 'declined']); 
        // Note: We do NOT update the invoice balance if declined.
        
        return redirect()->route('bursar.payments.index')->with('error', 'Payment marked as declined.');
    }

    /**
     * Display the receipt.
     */
    public function show($id)
    {
        $payment = Payment::with('invoice.student')->findOrFail($id);
        
        return view('bursar.payments.receipt', compact('payment'));
    }
}
