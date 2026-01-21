@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- SIDEBAR --}}
        @include('bursar.partials.sidebar')

        {{-- MAIN CONTENT WRAPPER --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Record Payment</h1>
                <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left"></i> Back to Invoices
                </a>
            </div>

            {{-- Form Container --}}
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow mb-4 border-left-primary">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Payment Processing</h6>
                        </div>
                        
                        <div class="card-body">
                            {{-- INVOICE SUMMARY --}}
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="font-weight-bold text-gray-800">Invoice Details</h5>
                                    <p class="mb-1"><strong>Student:</strong> {{ $invoice->student?->name }}</p>
                                    <p class="mb-1"><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
                                    <p class="mb-0"><strong>Term:</strong> {{ $invoice->term?->name }}</p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <h5 class="text-gray-600">Total Due: <span class="text-dark">₦{{ number_format($invoice->total_amount, 2) }}</span></h5>
                                    <h5 class="text-success">Paid So Far: ₦{{ number_format($invoice->paid_amount, 2) }}</h5>
                                    <div class="h4 text-danger font-weight-bold mt-2">
                                        Balance: ₦{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}
                                    </div>
                                </div>
                            </div>

                            {{-- DIVIDER LINE --}}
                            <hr class="my-4">

                            {{-- PAYMENT FORM --}}
                            <h5 class="font-weight-bold text-gray-800 mb-3">Enter Payment Details</h5>
                            
                            <form action="{{ route('bursar.payments.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Amount to Pay (₦)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₦</span>
                                        <input type="number" name="amount" class="form-control form-control-lg" 
                                            max="{{ $invoice->total_amount - $invoice->paid_amount }}" 
                                            step="0.01" required placeholder="0.00">
                                    </div>
                                    <small class="text-muted">Cannot exceed the balance of ₦{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-bold">Payment Method</label>
                                        <select name="payment_method" class="form-select form-control" required>
                                            <option value="Cash">Cash</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="POS">POS</option>
                                            <option value="Cheque">Cheque</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-bold">Date</label>
                                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label font-weight-bold">Reference / Teller Number</label>
                                    <input type="text" name="reference_number" class="form-control" placeholder="Optional (e.g. Bank Ref, Receipt No)">
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-check-circle me-1"></i> Confirm Payment
                                </button>
                            </form>
                        </div> 
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>
@endsection