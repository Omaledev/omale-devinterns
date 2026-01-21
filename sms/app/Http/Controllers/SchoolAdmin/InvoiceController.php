<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FeeStructure;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\ClassLevel;
use App\Models\Term;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    // Show form to generate invoices
    public function create()
    {
        $schoolId = auth()->user()->school_id;
        $classes = ClassLevel::where('school_id', $schoolId)->get();
        $terms = Term::whereHas('academicSession', fn($q) => $q->where('school_id', $schoolId))
                     ->where('is_active', true)
                     ->get();

        return view('schooladmin.fees.invoices.generate', compact('classes', 'terms'));
    }

    // Bulk Generate Logic
    public function store(Request $request)
    {
        $request->validate([
            'class_level_id' => 'required',
            'term_id' => 'required',
        ]);

        $schoolId = auth()->user()->school_id;
        $term = Term::findOrFail($request->term_id);

        //  Get all fees for this class/term
        $fees = FeeStructure::where('class_level_id', $request->class_level_id)
                            ->where('term_id', $request->term_id)
                            ->get();

        if ($fees->isEmpty()) {
            return back()->with('error', 'No fee structure found for this class and term. Please define fees first.');
        }

        // Get all students in this class
        $students = User::role('Student')
            ->whereHas('studentProfile', fn($q) => $q->where('class_level_id', $request->class_level_id))
            ->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'No students found in this class.');
        }

        $count = 0;

        foreach ($students as $student) {
            // Checking if invoice already exists to prevent duplicates
            $exists = Invoice::where('student_id', $student->id)
                ->where('term_id', $request->term_id)
                ->exists();
            
            if ($exists) continue;

            // Create Invoice
            $invoice = Invoice::create([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'term_id' => $request->term_id,
                'academic_session_id' => $term->academic_session_id,
                'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                'total_amount' => $fees->sum('amount'),
                'status' => 'UNPAID'
            ]);

            // Create Items
            foreach ($fees as $fee) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $fee->name,
                    'amount' => $fee->amount
                ]);
            }
            $count++;
        }

        return redirect()->route('finance.invoices.index')
            ->with('success', "$count invoices generated successfully!");
    }

    // List all invoices
    public function index()
    {
        $invoices = Invoice::where('school_id', auth()->user()->school_id)
            ->with(['student', 'term'])
            ->latest()
            ->paginate(20);

        return view('schooladmin.fees.invoices.index', compact('invoices'));
    }
    
    // Show single invoice details
    public function show(Invoice $invoice)
    {
         if($invoice->school_id !== auth()->user()->school_id) abort(403);
    
        $invoice->load(['items', 'student.studentProfile.classLevel', 'school']);
    
        return view('schooladmin.fees.invoices.show', compact('invoice'));
    }

    public function destroy($id)
    {
        // Find the invoice (ensure it belongs to the logged-in school)
        $invoice = \App\Models\Invoice::where('school_id', auth()->user()->school_id)
            ->findOrFail($id);

        // Delete it
        $invoice->delete();

        // Redirect back
        return back()->with('success', 'Invoice deleted successfully.');
    }
}