@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        
        @if(auth()->user()->hasRole('Bursar'))
            @include('bursar.partials.sidebar')
        @else
            @include('schooladmin.partials.sidebar')
        @endif

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

            <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
                <h1 class="h3 mb-0 text-gray-800">Invoice Details</h1>
                <div>
                    <button onclick="window.print()" class="btn btn-secondary me-1 shadow-sm">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                    <a href="{{ route('finance.invoices.index') }}" class="btn btn-primary shadow-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow mb-4 invoice-card">
                        {{-- Invoice Header --}}
                        <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center bg-white border-bottom-0">
                            <h5 class="m-0 font-weight-bold text-primary">Invoice #{{ $invoice->invoice_number }}</h5>
                            <span class="badge bg-{{ $invoice->status == 'PAID' ? 'success' : ($invoice->status == 'PARTIAL' ? 'warning' : 'danger') }} fs-6">
                                Status: {{ $invoice->status }}
                            </span>
                        </div>
                        
                        <div class="card-body">
                            {{-- From/To Section --}}
                            <div class="row mb-5">
                                <div class="col-6">
                                    <h6 class="text-uppercase text-secondary fw-bold small mb-2">From:</h6>
                                    {{-- Accessing School Details Dynamically --}}
                                    <div class="h5 fw-bold text-dark mb-0">{{ $invoice->school->name }}</div>
                                    <div class="text-muted">{{ $invoice->school->address }}</div>
                                    <div class="text-muted">{{ $invoice->school->email }}</div>
                                </div>
                                <div class="col-6 text-end">
                                    <h6 class="text-uppercase text-secondary fw-bold small mb-2">Bill To:</h6>
                                    <div class="h5 fw-bold text-dark mb-0">{{ $invoice->student?->name ?? 'Unknown Student' }}</div>
                                    <div class="text-muted">Class: {{ $invoice->student?->studentProfile?->classLevel?->name ?? 'N/A' }}</div>
                                    <div class="small text-muted">ID: {{ $invoice->student?->studentProfile?->student_id ?? 'N/A' }}</div>
                                </div>
                            </div>

                            {{-- Items Table --}}
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Description</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($invoice->items as $item)
                                        <tr>
                                            <td>{{ $item->description }}</td>
                                            <td class="text-end">₦{{ number_format($item->amount, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2" class="text-center">No items found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-end border-top-2">Subtotal</th>
                                            <th class="text-end border-top-2">₦{{ number_format($invoice->total_amount, 2) }}</th>
                                        </tr>
                                        <tr>
                                            <th class="text-end text-success">Paid Amount</th>
                                            <th class="text-end text-success">- ₦{{ number_format($invoice->paid_amount, 2) }}</th>
                                        </tr>
                                        <tr class="bg-light">
                                            <th class="text-end text-danger h5 pt-3">Balance Due</th>
                                            <th class="text-end text-danger h5 pt-3">₦{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            {{-- Payment Instructions Section --}}
                            <div class="mt-5 p-3 bg-light border rounded">
                                <h6 class="font-weight-bold text-dark border-bottom pb-2">Payment Instructions</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1 small text-muted text-uppercase">Bank Name</p>
                                        <p class="font-weight-bold">{{ $invoice->school->bank_name ?? 'Not Set' }}</p>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <p class="mb-1 small text-muted text-uppercase">Account Number</p>
                                        <p class="font-weight-bold text-primary h5">{{ $invoice->school->account_number ?? 'Not Set' }}</p>
                                        <small>{{ $invoice->school->account_name ?? '' }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-none d-print-block mt-5 text-center text-muted small fixed-bottom">
                                <hr>
                                <p>Thank you. Generated on {{ now()->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection