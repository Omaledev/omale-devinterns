@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Financial Reports</h1>
            <p class="text-muted mb-0 small">Overview of school revenue</p>
        </div>
        
        <a href="{{ route('bursar.dashboard') }}" class="btn btn-outline-secondary btn-sm shadow-sm rounded-pill px-3">
            <i class="fas fa-arrow-left"></i>
            <span class="d-none d-md-inline ms-1">Back</span>
        </a>
    </div>

    {{-- Summary Card--}}
    <div class="card shadow-sm border-0 mb-4 rounded-3">
        <div class="card-body p-4">
            <h5 class="card-title fw-bold text-gray-800 mb-4">Revenue Overview</h5>
            <div class="row g-3">
                {{-- Expected --}}
                <div class="col-md-4">
                    <div class="p-4 rounded-3 bg-primary bg-opacity-10 h-100">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                <i class="fas fa-file-invoice fa-sm"></i>
                            </div>
                            <span class="text-primary fw-bold text-uppercase small">Expected</span>
                        </div>
                        <div class="h3 fw-bold text-gray-800 mb-0">₦{{ number_format($totalExpected, 2) }}</div>
                    </div>
                </div>

                {{-- Collected --}}
                <div class="col-md-4">
                    <div class="p-4 rounded-3 bg-success bg-opacity-10 h-100">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                <i class="fas fa-wallet fa-sm"></i>
                            </div>
                            <span class="text-success fw-bold text-uppercase small">Collected</span>
                        </div>
                        <div class="h3 fw-bold text-gray-800 mb-0">₦{{ number_format($totalCollected, 2) }}</div>
                    </div>
                </div>

                {{-- Outstanding --}}
                <div class="col-md-4">
                    <div class="p-4 rounded-3 bg-danger bg-opacity-10 h-100">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                <i class="fas fa-exclamation-triangle fa-sm"></i>
                            </div>
                            <span class="text-danger fw-bold text-uppercase small">Outstanding</span>
                        </div>
                        <div class="h3 fw-bold text-gray-800 mb-0">₦{{ number_format($totalOutstanding, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
    {{-- Daily Collection Report --}}
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100 rounded-3">
            <div class="card-header bg-transparent py-3 d-flex align-items-center justify-content-between border-0">
                <h6 class="m-0 fw-bold text-gray-800">Daily Collections</h6>
                <span class="badge bg-light text-primary border rounded-pill px-3">Recent Activity</span>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4 py-3 fw-bold">Date</th>
                                <th class="py-3 fw-bold text-end pe-4">Amount Collected</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailyCollections as $day)
                            <tr class="border-bottom border-light">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded px-2 py-1 me-2 text-center" style="min-width: 45px;">
                                            <div class="small fw-bold">{{ \Carbon\Carbon::parse($day->date)->format('M') }}</div>
                                            <div class="h5 m-0 fw-bold line-height-1">{{ \Carbon\Carbon::parse($day->date)->format('d') }}</div>
                                        </div>
                                        <div class="text-muted small">{{ \Carbon\Carbon::parse($day->date)->format('l') }}</div>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="fw-bold text-success h6 mb-0">+ ₦{{ number_format($day->total, 2) }}</div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-5 text-muted">
                                    <i class="fas fa-calendar-times fa-2x mb-2 opacity-50"></i>
                                    <p class="mb-0 small">No collections recorded yet.</p>
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
            <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center border-0">
                <h6 class="m-0 fw-bold text-gray-800">Outstanding Balances</h6>
                <a href="{{ route('bursar.reports.export_debtors') }}" class="btn btn-sm btn-light text-success border fw-bold rounded-pill px-3 shadow-sm">
                    <i class="fas fa-file-csv me-1"></i> Export
                </a>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4 py-3 fw-bold">Student</th>
                                <th class="py-3 fw-bold">Balance Due</th>
                                <th class="py-3 fw-bold text-end pe-4">Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($debtors as $debtor)
                            <tr class="border-bottom border-light">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        {{-- Avatar Circle --}}
                                        <div class="avatar-circle bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px; min-width: 40px;">
                                            <span class="fw-bold">{{ substr($debtor->student->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $debtor->student->name }}</div>
                                            <div class="small text-muted">{{ $debtor->invoice_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-danger">₦{{ number_format($debtor->total_amount - $debtor->paid_amount, 2) }}</div>
                                    <small class="text-xs text-muted">Term: {{ $debtor->term->name ?? 'N/A' }}</small>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('bursar.payments.create', $debtor->id) }}" 
                                       class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                        Pay
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <i class="fas fa-check-circle fa-2x mb-2 text-success opacity-50"></i>
                                    <p class="mb-0 small">Great job! No outstanding debts.</p>
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
</div>

<style>
    @media (min-width: 768px) {
        .border-end-md {
            border-right: 1px solid #e3e6f0;
        }
    }
</style>
@endsection