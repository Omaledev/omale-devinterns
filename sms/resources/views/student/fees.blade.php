@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('student.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            {{-- Page Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-gray-800">My Tuition & Fees</h3>
                    <p class="text-muted small">Manage your school fees and view payment history.</p>
                </div>
                {{-- Global Payment Instruction Button --}}
                <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#bankDetailsCard">
                    <i class="fas fa-info-circle me-1"></i> View Bank Details
                </button>
            </div>


            {{-- Financial Summary Cards --}}
            <div class="row mb-4">
                {{-- Outstanding --}}
                <div class="col-md-4 mb-3">
                    <div class="card bg-danger text-white h-100 shadow border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 text-uppercase small fw-bold mb-1">Total Outstanding</h6>
                                    <h3 class="fw-bold mb-0">₦{{ number_format($totalOutstanding, 2) }}</h3>
                                </div>
                                <i class="fas fa-exclamation-circle fa-2x opacity-50"></i>
                            </div>
                            <div class="small mt-2 text-white-50">Due immediately</div>
                        </div>
                    </div>
                </div>
                
                {{-- Paid --}}
                <div class="col-md-4 mb-3">
                    <div class="card bg-success text-white h-100 shadow border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 text-uppercase small fw-bold mb-1">Total Paid</h6>
                                    <h3 class="fw-bold mb-0">₦{{ number_format($totalPaid, 2) }}</h3>
                                </div>
                                <i class="fas fa-check-circle fa-2x opacity-50"></i>
                            </div>
                            <div class="small mt-2 text-white-50">Verified payments</div>
                        </div>
                    </div>
                </div>

                {{-- Billed --}}
                <div class="col-md-4 mb-3">
                    <div class="card bg-primary text-white h-100 shadow border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 text-uppercase small fw-bold mb-1">Total Billed</h6>
                                    <h3 class="fw-bold mb-0">₦{{ number_format($totalBilled, 2) }}</h3>
                                </div>
                                <i class="fas fa-file-invoice fa-2x opacity-50"></i>
                            </div>
                            <div class="small mt-2 text-white-50">Lifetime total</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bank Details Section (Collapsible) --}}
            <div class="collapse show mb-4" id="bankDetailsCard">
                <div class="card bg-light shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <h6 class="fw-bold text-primary mb-1">How to Pay</h6>
                            <p class="mb-0 small text-muted">
                                Please make a transfer to <strong>{{ $school->bank_name ?? 'Your School' }}</strong><br>
                                
                                Account Number: <strong class="text-dark">{{ $school->account_number ?? 'Not Available' }}</strong> | 
                                
                                Name: <strong>{{ $school->account_name ?? '' }}</strong>
                            </p>
                            <small class="fst-italic text-danger">Note: After transfer, click "Upload Proof" on the specific invoice below.</small>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold text-gray-800 mb-3">Invoice History</h5>

            {{-- Invoices Grid --}}
            @if($invoices->isEmpty())
                <div class="text-center py-5 bg-white rounded shadow-sm">
                    <i class="fas fa-file-invoice-dollar fa-3x text-gray-300 mb-3"></i>
                    <h5>No Invoices Found</h5>
                    <p class="text-muted">You have no fee records at the moment.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($invoices as $invoice)
                        <div class="col-lg-6">
                            <div class="card shadow-sm h-100{{ $invoice->status === 'PAID' ? 'success' : ($invoice->status === 'PARTIAL' ? 'warning' : 'danger') }}">
                                <div class="card-body">
                                    {{-- Invoice Header --}}
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="fw-bold mb-0 text-dark">Invoice #{{ $invoice->invoice_number }}</h5>
                                            <span class="badge bg-light text-dark border mt-1">
                                                {{ $invoice->term->name ?? 'Term' }} - {{ $invoice->academicSession->name ?? 'Session' }}
                                            </span>
                                        </div>
                                        <span class="badge bg-{{ $invoice->status === 'PAID' ? 'success' : ($invoice->status === 'PARTIAL' ? 'warning' : 'danger') }} px-3 py-2 rounded-pill">
                                            {{ $invoice->status }}
                                        </span>
                                    </div>

                                    <hr class="text-muted opacity-25">

                                    {{-- Financial Details --}}
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem;">Amount Billed</small>
                                            <span class="fw-bold fs-5">₦{{ number_format($invoice->total_amount, 2) }}</span>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem;">Verified Paid</small>
                                            <span class="fw-bold fs-5 text-success">₦{{ number_format($invoice->paid_amount, 2) }}</span>
                                        </div>
                                    </div>

                                    {{-- Progress Bar --}}
                                    <div class="mb-4">
                                        @php
                                            $percentage = $invoice->total_amount > 0 ? ($invoice->paid_amount / $invoice->total_amount) * 100 : 100;
                                            $balance = $invoice->total_amount - $invoice->paid_amount;
                                            // Checking for Pending Payments
                                            $hasPendingPayment = $invoice->payments->where('status', 'pending')->count() > 0;
                                        @endphp
                                        
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: {{ $percentage }}%" 
                                                 aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <small class="text-muted">{{ round($percentage) }}% Paid</small>
                                            <small class="text-danger fw-bold">Bal: ₦{{ number_format($balance, 2) }}</small>
                                        </div>
                                    </div>
                                    
                                    {{-- Actions --}}
                                    <div class="d-grid gap-2">
                                        @if($invoice->status !== 'PAID')
                                            @if($hasPendingPayment)
                                                <button class="btn btn-warning text-white" disabled>
                                                    <i class="fas fa-clock me-2"></i> Verification Pending
                                                </button>
                                                <small class="text-center text-muted fst-italic" style="font-size: 0.8rem;">Bursar is reviewing your proof of payment.</small>
                                            @else
                                                <button type="button" 
                                                        class="btn btn-primary shadow-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#paymentModal"
                                                        data-id="{{ $invoice->id }}"
                                                        data-number="{{ $invoice->invoice_number }}"
                                                        data-balance="{{ $balance }}">
                                                    <i class="fas fa-upload me-2"></i> Upload Proof of Payment
                                                </button>
                                            @endif
                                        @else
                                            <button class="btn btn-success text-white" disabled>
                                                <i class="fas fa-check-circle me-2"></i> Fully Paid
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('student.invoices.print', $invoice->id) }}" class="btn btn-outline-secondary btn-sm mt-1">
                                            <i class="fas fa-download me-1"></i> Download Invoice PDF
                                        </a>
                                    </div>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-receipt me-2"></i>Upload Proof of Payment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('student.payments.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    {{-- Hidden Invoice ID --}}
                    <input type="hidden" name="invoice_id" id="modalInvoiceId">
                    
                    <div class="alert alert-light border border-primary d-flex align-items-center mb-3">
                        <i class="fas fa-info-circle text-primary me-3 fa-2x"></i>
                        <small class="text-dark">
                            You are uploading a receipt for Invoice: <strong id="modalInvoiceNumber" class="text-primary"></strong>.
                            Make sure the image is clear.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Amount Paid (₦)</label>
                        <div class="input-group">
                            <span class="input-group-text">₦</span>
                            <input type="number" name="amount" id="modalAmount" class="form-control" step="0.01" min="1" required>
                        </div>
                        <div class="form-text">Enter the exact amount on your receipt.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Teller Number / Reference ID</label>
                        <input type="text" name="reference_number" class="form-control" placeholder="e.g., TN-2026-X892" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Upload Receipt</label>
                        <input type="file" name="proof" class="form-control" accept="image/jpeg,image/png,application/pdf" required>
                        <div class="form-text">Accepted: JPG, PNG, PDF (Max 2MB)</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Submit for Verification</button>
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
                
                // Auto-fill amount with the outstanding balance (convenience)
                if (modalAmountInput) modalAmountInput.value = balance; 
            });
        }
    });
</script>
@endpush

@endsection