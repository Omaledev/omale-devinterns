<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\InvoiceResource;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Get invoices for authenticated student (or their parent)
     */
    public function forStudent(Request $request)
    {
        $user = $request->user();
        
        if (!$user->hasRole('Student')) {
            
            // Checking if parent is accessing child's invoices
            if ($user->hasRole('Parent')) {
                $studentId = $request->input('student_id');
                
                if (!$studentId) {
                    return response()->json(['message' => 'Student ID is required for parents.'], 400);
                }
                
                // Verifying parent-student relationship using safe navigation
                $isParentOf = $user->parentProfile?->students()->where('users.id', $studentId)->exists();
                
                if (!$isParentOf) {
                    return response()->json(['message' => 'Unauthorized: Not your child.'], 403);
                }
                
                $student = User::findOrFail($studentId);
            } else {
                return response()->json(['message' => 'Unauthorized access.'], 403);
            }
        } else {
            $student = $user;
        }

        // Ensuring relationships exist in Invoice model
        $invoices = Invoice::with(['student', 'term', 'academicSession', 'items', 'payments'])
            ->where('student_id', $student->id)
            ->where('school_id', $student->school_id)
            ->latest() 
            ->paginate(20);

        return response()->json([
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'admission_number' => $student->studentProfile?->admission_number,
            ],
            'invoices' => InvoiceResource::collection($invoices),
            'financial_summary' => $this->calculateFinancialSummary($invoices->getCollection()),
            'pagination' => [
                'current_page' => $invoices->currentPage(),
                'last_page' => $invoices->lastPage(),
                'per_page' => $invoices->perPage(),
                'total' => $invoices->total(),
            ],
        ]);
    }

    /**
     * Get specific invoice details
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        
        $invoice = Invoice::with(['student', 'term', 'academicSession', 'items', 'payments'])
            ->where('id', $id)
            ->where('school_id', $user->school_id)
            ->firstOrFail();

        // Permission Logic
        if ($user->hasRole('Student')) {
            if ($invoice->student_id != $user->id) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
        } elseif ($user->hasRole('Parent')) {
            $isParentOf = $user->parentProfile?->students()->where('users.id', $invoice->student_id)->exists();
            if (!$isParentOf) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
        } elseif (!$user->hasAnyRole(['Bursar', 'SchoolAdmin', 'SuperAdmin'])) {
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        return new InvoiceResource($invoice);
    }

    /**
     * Get invoice summary (For Bursars/Admins)
     */
    public function summary(Request $request)
    {
        $user = $request->user();
        
        if (!$user->hasAnyRole(['Bursar', 'SchoolAdmin', 'SuperAdmin'])) {
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        $summary = Invoice::where('school_id', $user->school_id)
            ->selectRaw('
                COUNT(*) as total_invoices,
                SUM(total_amount) as total_amount,
                SUM(paid_amount) as total_paid,
                SUM(total_amount - paid_amount) as total_balance,
                COUNT(CASE WHEN status = "PAID" THEN 1 END) as paid_count,
                COUNT(CASE WHEN status = "PARTIAL" THEN 1 END) as partial_count,
                COUNT(CASE WHEN status = "UNPAID" THEN 1 END) as unpaid_count
            ')
            ->first();

        return response()->json([
            'summary' => $summary,
            'by_term' => $this->getInvoicesByTerm($user->school_id),
            'by_status' => [
                'PAID' => (int)$summary->paid_count,
                'PARTIAL' => (int)$summary->partial_count,
                'UNPAID' => (int)$summary->unpaid_count,
            ],
        ]);
    }

    private function calculateFinancialSummary($invoices)
    {
        return [
            'total_invoices' => $invoices->count(),
            'total_amount' => $invoices->sum('total_amount'),
            'total_paid' => $invoices->sum('paid_amount'),
            'total_balance' => $invoices->sum(fn($inv) => $inv->total_amount - $inv->paid_amount),
            'paid_invoices' => $invoices->where('status', 'PAID')->count(),
            'partial_invoices' => $invoices->where('status', 'PARTIAL')->count(),
            'unpaid_invoices' => $invoices->where('status', 'UNPAID')->count(),
        ];
    }

    private function getInvoicesByTerm($schoolId)
    {
        return Invoice::with('term')
            ->where('school_id', $schoolId)
            ->selectRaw('term_id, COUNT(*) as count, SUM(total_amount) as total, SUM(paid_amount) as paid')
            ->groupBy('term_id')
            ->get()
            ->map(function($item) {
                return [
                    'term_id' => $item->term_id,
                    'term_name' => $item->term->name ?? 'Unknown',
                    'count' => (int)$item->count,
                    'total_amount' => (float)$item->total,
                    'paid_amount' => (float)$item->paid,
                    'balance' => (float)($item->total - $item->paid),
                ];
            });
    }
}