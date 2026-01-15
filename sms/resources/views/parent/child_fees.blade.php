@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('parent.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            {{-- Header & Back Button --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Fee History: {{ $child->name }}</h3>
                    <p class="text-muted mb-0">
                        {{ $child->studentProfile->classLevel->name ?? '' }} 
                        {{ $child->studentProfile->section->name ?? '' }}
                    </p>
                </div>
                <a href="{{ route('parent.children') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Children
                </a>
            </div>

            {{-- Financial Summary Cards --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase small fw-bold opacity-75">Total Billed</h6>
                            <h3 class="mb-0 fw-bold">₦{{ number_format($totalBilled, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase small fw-bold opacity-75">Total Paid</h6>
                            <h3 class="mb-0 fw-bold">₦{{ number_format($totalPaid, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card {{ $balance > 0 ? 'bg-danger' : 'bg-secondary' }} text-white border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase small fw-bold opacity-75">Outstanding Balance</h6>
                            <h3 class="mb-0 fw-bold">₦{{ number_format($balance, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Invoices Table --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Invoice Records</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>Invoice #</th>
                                <th>Session / Term</th>
                                <th>Amount</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->created_at->format('M d, Y') }}</td>
                                    <td class="fw-bold text-primary">{{ $invoice->invoice_number }}</td>
                                    <td class="small">
                                        {{ $invoice->academicSession->name ?? '-' }}<br>
                                        <span class="text-muted">{{ $invoice->term->name ?? '-' }}</span>
                                    </td>
                                    <td>₦{{ number_format($invoice->total_amount, 2) }}</td>
                                    <td class="text-success">₦{{ number_format($invoice->paid_amount, 2) }}</td>
                                    <td class="fw-bold text-danger">
                                        ₦{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}
                                    </td>
                                    <td>
                                        @php
                                            $statusColor = match($invoice->status) {
                                                'PAID' => 'success',
                                                'PARTIAL' => 'warning',
                                                default => 'danger'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }}">{{ $invoice->status }}</span>
                                    </td>
                                    <td>
                                        @if($invoice->status !== 'PAID')
                                            <button class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#paymentModal"
                                                    data-invoice-id="{{ $invoice->id }}"
                                                    data-invoice-number="{{ $invoice->invoice_number }}"
                                                    data-balance="{{ $invoice->total_amount - $invoice->paid_amount }}">
                                                Pay Now
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                                <i class="fas fa-check me-1"></i> Paid
                                            </button>
                                        @endif
                                        
                                        {{-- Print Invoice Button --}}
                                        <a href="{{ route('student.invoices.print', $invoice->id) }}" 
                                           class="btn btn-sm btn-light border" 
                                           target="_blank" 
                                           title="Print Invoice">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        No invoices found for this student.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>

{{-- REUSING PAYMENT MODAL --}}
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Make Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('student.payments.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="invoice_id" id="modalInvoiceId">
                    
                    <div class="alert alert-info small">
                        <strong>Bank Details:</strong><br>
                        Bank: First Bank | Acct: 1234567890
                    </div>

                    <p class="mb-3">Payment for Invoice: <strong id="modalInvoiceNumber" class="text-primary"></strong></p>

                    <div class="mb-3">
                        <label class="form-label">Amount (₦)</label>
                        <input type="number" name="amount" id="modalAmount" class="form-control" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Teller / Reference Number</label>
                        <input type="text" name="reference_number" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Proof (Image/PDF)</label>
                        <input type="file" name="proof" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    var paymentModal = document.getElementById('paymentModal');
    paymentModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var invoiceId = button.getAttribute('data-invoice-id');
        var invoiceNumber = button.getAttribute('data-invoice-number');
        var balance = button.getAttribute('data-balance');
        
        document.getElementById('modalInvoiceId').value = invoiceId;
        document.getElementById('modalInvoiceNumber').textContent = invoiceNumber;
        document.getElementById('modalAmount').value = balance;
    });
</script>
@endsection