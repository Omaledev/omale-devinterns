@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- 1. INCLUDE SIDEBAR (This fixes the missing dark column) --}}
        @include('bursar.partials.sidebar')

        {{-- 2. WRAP CONTENT IN MAIN TAG (To push it to the right of the sidebar) --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            
            {{-- Header Section --}}
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Receipts Log</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('finance.invoices.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> New Payment
                    </a>
                </div>
            </div>

            {{-- Table Section --}}
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Receipt #</th>
                                    <th>Date</th>
                                    <th>Student</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Ref Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                <tr>
                                    <td>#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</td>
                                    <td>{{ $payment->invoice->student->name ?? 'Unknown' }}</td>
                                    <td class="fw-bold text-success">₦{{ number_format($payment->amount, 2) }}</td>
                                    <td><span class="badge bg-secondary">{{ ucfirst($payment->payment_method) }}</span></td>
                                    <td>{{ $payment->reference_number ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('bursar.payments.receipt', $payment->id) }}" 
                                           class="btn btn-sm btn-outline-dark" target="_blank">
                                            <i class="fas fa-print"></i> Print
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">No payments recorded yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-3">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection