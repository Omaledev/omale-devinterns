@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('parent.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h3 class="fw-bold mb-4">Fee Payments</h3>

            {{-- Child Tabs --}}
            <ul class="nav nav-tabs mb-4" id="feesTabs" role="tablist">
                @foreach($children as $index => $child)
                    <li class="nav-item">
                        <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                id="tab-{{ $child->id }}" 
                                data-bs-toggle="tab" 
                                data-bs-target="#content-{{ $child->id }}" 
                                type="button">
                            {{ $child->name }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach($children as $index => $child)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="content-{{ $child->id }}">
                        
                        @php
                            $invoices = $child->invoices ?? collect();
                            $totalBilled = $invoices->sum('total_amount');
                            $totalPaid = $invoices->sum('paid_amount');
                            $balance = $totalBilled - $totalPaid;
                        @endphp

                        {{-- Financial Summary --}}
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-danger text-white border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="text-uppercase small fw-bold opacity-75">Outstanding Balance</h6>
                                        <h3 class="mb-0 fw-bold">₦{{ number_format($balance, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="text-uppercase small fw-bold opacity-75">Total Paid</h6>
                                        <h3 class="mb-0 fw-bold">₦{{ number_format($totalPaid, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Invoice Table --}}
                        @if($invoices->isEmpty())
                            <div class="alert alert-info">No invoices found for {{ $child->name }}.</div>
                        @else
                            <div class="card shadow-sm border-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Invoice #</th>
                                                <th>Session / Term</th>
                                                <th>Total</th>
                                                <th>Paid</th>
                                                <th>Balance</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoices as $invoice)
                                                <tr>
                                                    <td class="fw-bold">{{ $invoice->invoice_number }}</td>
                                                    <td class="small">
                                                        {{ $invoice->academicSession->name ?? '-' }}<br>
                                                        <span class="text-muted">{{ $invoice->term->name ?? '-' }}</span>
                                                    </td>
                                                    <td>₦{{ number_format($invoice->total_amount, 2) }}</td>
                                                    <td class="text-success">₦{{ number_format($invoice->paid_amount, 2) }}</td>
                                                    <td class="fw-bold text-danger">₦{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</td>
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
                                                            {{-- Data Attributes for JS --}}
                                                            <button class="btn btn-sm btn-primary" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#paymentModal"
                                                                    data-invoice-id="{{ $invoice->id }}"
                                                                    data-invoice-number="{{ $invoice->invoice_number }}"
                                                                    data-balance="{{ $invoice->total_amount - $invoice->paid_amount }}"
                                                                    data-bank-name="{{ $invoice->school->bank_name ?? 'Contact School' }}"
                                                                    data-account-number="{{ $invoice->school->account_number ?? 'Unavailable' }}"
                                                                    data-account-name="{{ $invoice->school->account_name ?? '' }}">
                                                                Pay / Upload
                                                            </button>
                                                        @else
                                                            <button class="btn btn-sm btn-outline-secondary" disabled>Paid</button>
                                                        @endif
                                                        <a href="{{ route('student.invoices.print', $invoice->id) }}" target="_blank" class="btn btn-sm btn-light border">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </main>
    </div>
</div>

{{-- PAYMENT UPLOAD MODAL --}}
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Upload Proof of Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('student.payments.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="invoice_id" id="modalInvoiceId">
                    
                    {{-- Bank Details Section --}}
                    <div class="alert alert-info small">
                        <strong>Bank Details:</strong><br>
                        Bank: <span id="modalBankName" class="fw-bold">...</span><br>
                        Acct: <span id="modalAccountNumber" class="fw-bold text-dark">...</span> <span id="modalAccountName"></span>
                    </div>

                    <p class="mb-3">Invoice: <strong id="modalInvoiceNumber" class="text-primary"></strong></p>

                    <div class="mb-3">
                        <label class="form-label">Amount Paid (₦)</label>
                        <input type="number" name="amount" id="modalAmount" class="form-control" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Teller / Reference Number</label>
                        <input type="text" name="reference_number" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Receipt (Image/PDF)</label>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var paymentModal = document.getElementById('paymentModal');
        
        // Only run if modal exists
        if (paymentModal) {
            paymentModal.addEventListener('show.bs.modal', function (event) {
                // Button that triggered the modal
                var button = event.relatedTarget;
                
                // Extracting info from data-* attributes
                var invoiceId = button.getAttribute('data-invoice-id');
                var invoiceNumber = button.getAttribute('data-invoice-number');
                var balance = button.getAttribute('data-balance');
                var bankName = button.getAttribute('data-bank-name');
                var accountNumber = button.getAttribute('data-account-number');
                var accountName = button.getAttribute('data-account-name');
                
                // Updating the modal's content
                document.getElementById('modalInvoiceId').value = invoiceId;
                document.getElementById('modalInvoiceNumber').textContent = invoiceNumber;
                document.getElementById('modalAmount').value = balance;
                
                // Updating Bank Details Text
                document.getElementById('modalBankName').textContent = bankName;
                document.getElementById('modalAccountNumber').textContent = accountNumber;
                document.getElementById('modalAccountName').textContent = accountName ? '(' + accountName + ')' : '';
            });
        }
    });
</script>

@endsection