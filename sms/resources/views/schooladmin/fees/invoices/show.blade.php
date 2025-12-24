@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Invoice Details</h1>
        <div>
            <button onclick="window.print()" class="btn btn-secondary me-1">
                <i class="fas fa-print"></i> Print
            </button>
            <a href="{{ route('finance.invoices.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-primary">Invoice #{{ $invoice->invoice_number }}</h5>
                    <span class="badge bg-{{ $invoice->status == 'PAID' ? 'success' : ($invoice->status == 'PARTIAL' ? 'warning' : 'danger') }} fs-6 mt-2 mt-md-0">
                        Status: {{ $invoice->status }}
                    </span>
                </div>
                
                <div class="card-body">
                    {{-- Responsive Grid for From/To --}}
                    <div class="row mb-5">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="text-uppercase text-gray-500 font-weight-bold small">From:</h6>
                            <div class="h5 font-weight-bold mb-0">{{ auth()->user()->school->name }}</div>
                            <div>Admin/Bursary Office</div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-uppercase text-gray-500 font-weight-bold small">Bill To:</h6>
                            <div class="h5 font-weight-bold mb-0">{{ $invoice->student->name }}</div>
                            <div>Class: {{ $invoice->student->studentProfile->classLevel->name ?? 'N/A' }}</div>
                            <div class="small text-muted">ID: {{ $invoice->student->admission_number }}</div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Description</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $item)
                                <tr>
                                    <td>{{ $item->description }}</td>
                                    <td class="text-end">₦{{ number_format($item->amount, 2) }}</td>
                                </tr>
                                @endforeach
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
                                    <th class="text-end text-danger h5">Balance Due</th>
                                    <th class="text-end text-danger h5">₦{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection