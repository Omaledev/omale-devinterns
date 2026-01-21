@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('bursar.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Payment Logs</h1>
                <a href="{{ route('finance.invoices.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Record Cash Payment
                </a>
            </div>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Student</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Proof</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('d M, Y') }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $payment->invoice->student->name ?? 'Unknown' }}</div>
                                        <small class="text-muted">{{ $payment->invoice->invoice_number }}</small>
                                    </td>
                                    <td class="fw-bold">₦{{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->payment_method }}</td>
                                    <td>
                                        @if($payment->proof_file_path)
                                            <i class="fas fa-paperclip text-primary"></i> Uploaded
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->status === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($payment->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Declined</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->status === 'pending')
                                            <a href="{{ route('bursar.payments.verify', $payment->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-eye me-1"></i> Verify
                                            </a>
                                        @else
                                            <a href="{{ route('bursar.payments.receipt', $payment->id) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-print"></i> Receipt
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">No payments found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $payments->links() }}</div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection