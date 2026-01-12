@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        
        @if(auth()->user()->hasRole('Bursar'))
            @include('bursar.partials.sidebar')
        @else
            @include('schooladmin.partials.sidebar')
        @endif

        {{-- Main Content Area --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

            {{-- Header (Hidden when printing) --}}
            <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
                <h1 class="h3 mb-0 text-gray-800">Invoice Details</h1>
                <div>
                    <button onclick="window.print()" class="btn btn-secondary me-1 shadow-sm">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                    {{-- Dynamic Back Button based on Role --}}
                    <a href="{{ auth()->user()->hasRole('Bursar') ? route('finance.invoices.index') : route('finance.invoices.index') }}" 
                       class="btn btn-primary shadow-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow mb-4 invoice-card">
                        {{-- Card Header --}}
                        <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center bg-white border-bottom-0">
                            <h5 class="m-0 font-weight-bold text-primary">Invoice #{{ $invoice->invoice_number }}</h5>
                            <span class="badge bg-{{ $invoice->status == 'PAID' ? 'success' : ($invoice->status == 'PARTIAL' ? 'warning' : 'danger') }} fs-6 mt-2 mt-md-0">
                                Status: {{ $invoice->status }}
                            </span>
                        </div>
                        
                        <div class="card-body">
                            {{-- From/To Section --}}
                            <div class="row mb-5">
                                <div class="col-6">
                                    <h6 class="text-uppercase text-secondary fw-bold small mb-2">From:</h6>
                                    <div class="h5 fw-bold text-dark mb-0">{{ auth()->user()->school->name ?? 'School Name' }}</div>
                                    <div class="text-muted">Admin/Bursary Office</div>
                                    <div class="text-muted">{{ now()->format('d M, Y') }}</div>
                                </div>
                                <div class="col-6 text-end">
                                    <h6 class="text-uppercase text-secondary fw-bold small mb-2">Bill To:</h6>
                                    <div class="h5 fw-bold text-dark mb-0">{{ $invoice->student?->name ?? 'Unknown Student' }}</div>
                                    <div class="text-muted">Class: {{ $invoice->student?->studentProfile?->classLevel?->name ?? 'N/A' }}</div>
                                    <div class="small text-muted">ID: {{ $invoice->student?->studentProfile?->admission_number ?? 'N/A' }}</div>
                                </div>
                            </div>

                            {{-- Invoice Items Table --}}
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
                                            <td colspan="2" class="text-center">No items found for this invoice.</td>
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
                            
                            {{-- Print Footer Message --}}
                            <div class="d-none d-print-block mt-5 text-center text-muted small fixed-bottom">
                                <hr>
                                <p>Thank you for your business. Generated on {{ now()->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        /* 1. Hide Sidebar using ID or Class */
        .sidebar, #sidebarMenu {
            display: none !important;
        }

        /* 2. Hide Buttons/Links with d-print-none class */
        .d-print-none {
            display: none !important;
        }

        /* 3. Force Main Content to Full Width */
        main {
            flex: 0 0 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        /* Table Headers print dark */
        .table-dark {
            background-color: #212529 !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>
@endpush