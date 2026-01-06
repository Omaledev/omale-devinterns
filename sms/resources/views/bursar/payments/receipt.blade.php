@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0" id="printArea">
                <div class="card-body p-5">
                    
                    {{-- Header --}}
                    <div class="text-center mb-5">
                        <h2 class="font-weight-bold text-uppercase">{{ auth()->user()->school->name ?? 'SCHOOL RECEIPT' }}</h2>
                        <p class="text-muted mb-0">Official Payment Receipt</p>
                    </div>

                    {{-- Receipt Meta Data --}}
                    <div class="d-flex justify-content-between border-bottom pb-3 mb-4">
                        <div>
                            <small class="text-muted text-uppercase">Receipt No.</small>
                            <div class="font-weight-bold">RCP-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        <div class="text-end">
                            <small class="text-muted text-uppercase">Date Paid</small>
                            <div class="font-weight-bold">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</div>
                        </div>
                    </div>

                    {{-- Student & Payment Info --}}
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h6 class="text-muted text-uppercase small">Received From:</h6>
                            <h5 class="font-weight-bold">{{ $payment->invoice->student?->name }}</h5>
                            <p class="mb-0">Class: {{ $payment->invoice->student?->studentProfile?->classLevel?->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                            <h6 class="text-muted text-uppercase small">Payment Method:</h6>
                            <span class="badge bg-light text-dark border">{{ $payment->payment_method }}</span>
                            @if($payment->reference_number)
                                <div class="small mt-1">Ref: {{ $payment->reference_number }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- Amount Box --}}
                    <div class="alert alert-success d-flex justify-content-between align-items-center p-4 mb-4">
                        <span class="h5 mb-0">Amount Paid:</span>
                        <span class="h3 mb-0 font-weight-bold">₦{{ number_format($payment->amount, 2) }}</span>
                    </div>

                    {{-- Invoice Summary Footer --}}
                    <div class="row text-muted small mt-5">
                        <div class="col-6">
                            Payment for: {{ $payment->invoice->invoice_number }} ({{ $payment->invoice->term?->name }})
                        </div>
                        <div class="col-6 text-end">
                            Remaining Balance: ₦{{ number_format($payment->invoice->total_amount - $payment->invoice->paid_amount, 2) }}
                        </div>
                    </div>
                </div>
                
                {{-- Print Button (Hidden when printing) --}}
                <div class="card-footer bg-white border-0 text-center pb-4 d-print-none">
                    <button onclick="window.print()" class="btn btn-primary btn-lg shadow-sm">
                        <i class="fas fa-print me-2"></i> Print Receipt
                    </button>
                    <a href="{{ route('bursar.dashboard') }}" class="btn btn-light btn-lg text-muted">Close</a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Print CSS Styles --}}
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printArea, #printArea * {
            visibility: visible;
        }
        #printArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none !important;
        }
        .d-print-none {
            display: none !important;
        }
    }
</style>
@endsection