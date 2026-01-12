@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- 1. INCLUDE SIDEBAR --}}
        @include('bursar.partials.sidebar')

        {{-- 2. MAIN CONTENT WRAPPER --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
            
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <div>
                    <h1 class="h2 text-gray-800 fw-bold">Financial Reports</h1>
                    <p class="text-muted mb-0 small">Overview of school revenue & outstanding debts</p>
                </div>
                
                {{-- Optional: Date Filter or Back Button --}}
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                    </div>
                </div>
            </div>

            {{-- Summary Cards (Revenue Overview) --}}
            <div class="card shadow-sm border-0 mb-4 rounded-3">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold text-gray-800 mb-4">Revenue Overview</h5>
                    <div class="row g-3">
                        {{-- Expected --}}
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-primary bg-opacity-10 border border-primary border-opacity-10 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                        <i class="fas fa-file-invoice"></i>
                                    </div>
                                    <span class="text-primary fw-bold text-uppercase small">Expected Revenue</span>
                                </div>
                                <div class="h3 fw-bold text-dark mb-0">₦{{ number_format($totalExpected, 2) }}</div>
                            </div>
                        </div>

                        {{-- Collected --}}
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-10 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <span class="text-success fw-bold text-uppercase small">Collected Amount</span>
                                </div>
                                <div class="h3 fw-bold text-dark mb-0">₦{{ number_format($totalCollected, 2) }}</div>
                            </div>
                        </div>

                        {{-- Outstanding --}}
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-10 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <span class="text-danger fw-bold text-uppercase small">Outstanding Balance</span>
                                </div>
                                <div class="h3 fw-bold text-dark mb-0">₦{{ number_format($totalOutstanding, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Daily Collection Report --}}
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0 h-100 rounded-3">
                        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-gray-800">Daily Collections</h6>
                            <span class="badge bg-light text-dark border">Last 7 Days</span>
                        </div>
                        
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-muted small text-uppercase">
                                        <tr>
                                            <th class="ps-4 py-3 fw-bold">Date</th>
                                            <th class="py-3 fw-bold text-end pe-4">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($dailyCollections as $day)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light border rounded px-2 py-1 me-2 text-center" style="min-width: 45px;">
                                                        <div class="small fw-bold text-muted">{{ \Carbon\Carbon::parse($day->date)->format('M') }}</div>
                                                        <div class="h6 m-0 fw-bold">{{ \Carbon\Carbon::parse($day->date)->format('d') }}</div>
                                                    </div>
                                                    <div class="text-dark fw-bold small">{{ \Carbon\Carbon::parse($day->date)->format('l') }}</div>
                                                </div>
                                            </td>
                                            <td class="text-end pe-4">
                                                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 rounded-pill">
                                                    + ₦{{ number_format($day->total, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-5 text-muted">
                                                <i class="fas fa-calendar-times fa-2x mb-2 opacity-50"></i>
                                                <p class="mb-0 small">No recent collections.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Debtors List --}}
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0 h-100 rounded-3">
                        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-gray-800">Outstanding Balances</h6>
                            <a href="{{ route('bursar.reports.export_debtors') }}" class="btn btn-sm btn-outline-danger rounded-pill">
                                <i class="fas fa-file-csv me-1"></i> Export
                            </a>
                        </div>
                        
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-muted small text-uppercase">
                                        <tr>
                                            <th class="ps-4 py-3 fw-bold">Student</th>
                                            <th class="py-3 fw-bold">Due</th>
                                            <th class="py-3 fw-bold text-end pe-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($debtors as $debtor)
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center me-2 shadow-sm" 
                                                         style="width: 35px; height: 35px; font-size: 0.8rem;">
                                                        {{ substr($debtor->student->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark small">{{ $debtor->student->name }}</div>
                                                        <div class="text-muted" style="font-size: 0.7rem;">{{ $debtor->invoice_number }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-danger">₦{{ number_format($debtor->total_amount - $debtor->paid_amount, 2) }}</div>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route('bursar.payments.create', $debtor->id) }}" 
                                                   class="btn btn-xs btn-primary rounded-pill px-3">
                                                    Pay
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-5 text-muted">
                                                <i class="fas fa-check-circle fa-2x mb-2 text-success opacity-50"></i>
                                                <p class="mb-0 small">No outstanding debts.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection