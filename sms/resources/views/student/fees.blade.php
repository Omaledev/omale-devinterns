@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('student.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">My Tuition & Fees</h3>
            </div>

            {{-- --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <strong>There were issues with your submission:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Financial Summary Cards --}}
            @php
                $totalInvoiced = $invoices->sum('total_amount');
                $totalPaid = $invoices->sum('paid_amount');
                $outstanding = $totalInvoiced - $totalPaid;
            @endphp

            <div class="row mb-4">
                {{-- Outstanding Balance --}}
                <div class="col-md-4 mb-3">
                    <div class="card bg-danger text-white h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-white-50 text-uppercase small fw-bold mb-2">Total Outstanding</h6>
                            <h3 class="fw-bold mb-0">₦{{ number_format($outstanding, 2) }}</h3>
                            <small>Due immediately</small>
                        </div>
                    </div>
                </div>
                
                {{-- Total Paid --}}
                <div class="col-md-4 mb-3">
                    <div class="card bg-success text-white h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-white-50 text-uppercase small fw-bold mb-2">Total Paid</h6>
                            <h3 class="fw-bold mb-0">₦{{ number_format($totalPaid, 2) }}</h3>
                            <small>Verified Payments</small>
                        </div>
                    </div>
                </div>

                {{-- Total Invoiced --}}
                <div class="col-md-4 mb-3">
                    <div class="card bg-primary text-white h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-white-50 text-uppercase small fw-bold mb-2">Total Billed</h6>
                            <h3 class="fw-bold mb-0">₦{{ number_format($totalInvoiced, 2) }}</h3>
                            <small>Lifetime total</small>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold text-muted mb-3">Invoice History</h5>

            {{-- Invoices List --}}
            @if($invoices->isEmpty())
                <div class="alert alert-info py-4 text-center">
                    <i class="fas fa-file-invoice-dollar fa-3x mb-3 opacity-50"></i>
                    <h5>No Invoices Found</h5>
                    <p class="mb-0">You have no fee records at the moment.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($invoices as $invoice)
                        <div class="col-lg-6">
                            <div class="card shadow-sm h-100 border-start border-4 border-{{ $invoice->status === 'PAID' ? 'success' : ($invoice->status === 'PARTIAL' ? 'warning' : 'danger') }}">
                                <div class="card-body">
                                    {{-- Header Row --}}
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="fw-bold mb-0">Invoice #{{ $invoice->invoice_number }}</h5>
                                            <small class="text-muted">
                                                {{ $invoice->term->name ?? 'Term' }} • {{ $invoice->academicSession->name ?? 'Session' }}
                                            </small>
                                        </div>
                                        <span class="badge bg-{{ $invoice->status === 'PAID' ? 'success' : ($invoice->status === 'PARTIAL' ? 'warning' : 'danger') }}">
                                            {{ $invoice->status }}
                                        </span>
                                    </div>

                                    <hr class="text-muted">

                                    {{-- Amount Details --}}
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Amount Billed</small>
                                            <span class="fw-bold fs-5">₦{{ number_format($invoice->total_amount, 2) }}</span>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted d-block">Verified Paid</small>
                                            <span class="fw-bold fs-5 text-success">₦{{ number_format($invoice->paid_amount, 2) }}</span>
                                        </div>
                                    </div>

                                    {{-- Progress Bar --}}
                                    <div class="mb-3">
                                        @php
                                            $percentage = $invoice->total_amount > 0 ? ($invoice->paid_amount / $invoice->total_amount) * 100 : 100;
                                            $balance = $invoice->total_amount - $invoice->paid_amount;
                                            
                                            // Checking for Pending Payments
                                            $hasPendingPayment = $invoice->payments->where('status', 'pending')->count() > 0;
                                        @endphp
                                        
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: {{ $percentage }}%" 
                                                 aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <small class="text-muted">Paid: {{ round($percentage) }}%</small>
                                            <small class="text-danger fw-bold">Balance: ₦{{ number_format($balance, 2) }}</small>
                                        </div>
                                    </div>
                                    
                                    {{-- Actions --}}
                                    <div class="d-grid gap-2">
                                        @if($invoice->status !== 'PAID')
                                            
                                            @if($hasPendingPayment)
                                                <button class="btn btn-warning text-white" disabled>
                                                    <i class="fas fa-clock me-2"></i> Verification Pending
                                                </button>
                                                <small class="text-center text-muted fst-italic">Bursar is reviewing your proof of payment.</small>
                                            @else
                                                <button type="button" 
                                                        class="btn btn-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#paymentModal"
                                                        data-id="{{ $invoice->id }}"
                                                        data-number="{{ $invoice->invoice_number }}"
                                                        data-balance="{{ $balance }}">
                                                    <i class="fas fa-upload me-2"></i> Upload Proof of Payment
                                                </button>
                                            @endif

                                        @else
                                            <button class="btn btn-outline-secondary" disabled>
                                                <i class="fas fa-check-circle me-2"></i> Fully Paid
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('student.invoices.print', $invoice->id) }}" target="_blank" class="btn btn-sm btn-link text-decoration-none text-muted">
                                            <i class="fas fa-print me-1"></i> Print / Download Invoice
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer bg-light border-top-0 text-muted small">
                                    Date Issued: {{ $invoice->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
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
                    {{-- Hidden Invoice ID --}}
                    <input type="hidden" name="invoice_id" id="modalInvoiceId">
                    
                    <div class="alert alert-info small">
                        <strong><i class="fas fa-university me-1"></i> Bank Details:</strong><br>
                        Bank: First Bank of Nigeria<br>
                        Account No: 1234567890<br>
                        Account Name: University of Jos Bursary
                    </div>

                    <p class="mb-3">Paying for Invoice: <strong id="modalInvoiceNumber" class="text-primary"></strong></p>

                    <div class="mb-3">
                        <label class="form-label">Amount Paid (₦)</label>
                        <input type="number" name="amount" id="modalAmount" class="form-control" step="0.01" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Teller Number / Reference ID</label>
                        <input type="text" name="reference_number" class="form-control" placeholder="e.g., TEL-23098234" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Receipt (Image/PDF)</label>
                        <input type="file" name="proof" class="form-control" accept="image/jpeg,image/png,application/pdf" required>
                        <div class="form-text">Max size: 2MB. Format: JPG, PNG, or PDF</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit for Verification</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JAVASCRIPT FOR MODAL --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var paymentModal = document.getElementById('paymentModal');
        if (paymentModal) {
            paymentModal.addEventListener('show.bs.modal', function (event) {
                // Button that triggered the modal
                var button = event.relatedTarget;
                
                // Extract info from data attributes
                var invoiceId = button.getAttribute('data-id');
                var invoiceNumber = button.getAttribute('data-number');
                var balance = button.getAttribute('data-balance');
                
                // Update the modal's content
                var modalIdInput = paymentModal.querySelector('#modalInvoiceId');
                var modalNumberText = paymentModal.querySelector('#modalInvoiceNumber');
                var modalAmountInput = paymentModal.querySelector('#modalAmount');
                
                if (modalIdInput) modalIdInput.value = invoiceId;
                if (modalNumberText) modalNumberText.textContent = invoiceNumber;
                if (modalAmountInput) modalAmountInput.value = balance; // Auto-fill balance
            });
        }
    });
</script>
@endpush

@endsection