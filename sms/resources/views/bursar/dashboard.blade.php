@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('bursar.partials.sidebar')

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <div>
                        <h1 class="h2">Bursar Dashboard</h1>
                        <p class="text-muted mb-0">Financial Management Overview</p>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-warning">
                                Export
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning">
                                Print
                            </button>
                        </div>
                        <button type="button" class="btn btn-sm btn-warning ">
                           <a href="{{ route('finance.invoices.index') }}" class="text-decoration-none"> Generate Invoices</a>
                        </button>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card bg-success text-white shadow h-100 border-0">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">
                                            Total Collection</div>
                                        <div class="h2 mb-0 fw-bold">${{ number_format($stats['total_collection'] ?? 0, 2) }}</div>
                                        <div class="mt-2 small">
                                            <span>This month</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card bg-primary text-white shadow h-100 border-0">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">
                                            Pending Invoices</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['pending_invoices'] ?? 0 }}</div>
                                        <div class="mt-2 small">
                                            <span>To be processed</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card bg-danger text-white shadow h-100 border-0">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">
                                            Outstanding Balance</div>
                                        <div class="h2 mb-0 fw-bold">${{ number_format($stats['outstanding_balance'] ?? 0, 2) }}</div>
                                        <div class="mt-2 small">
                                            <span>Total due</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card bg-info text-white shadow h-100 border-0">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">
                                            Collection Rate</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['collection_rate'] ?? 0 }}%</div>
                                        <div class="mt-2 small">
                                            <span>This term</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    Fee Collection Trend
                                </h6>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        This Month
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">This Week</a></li>
                                        <li><a class="dropdown-item" href="#">This Month</a></li>
                                        <li><a class="dropdown-item" href="#">This Term</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="p-3">
                                    <div style="height: 300px;">
                                        <canvas id="collectionChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    Recent Payments
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <th>Amount</th>
                                                <th>Method</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentPayments as $payment)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                            style="width: 30px; height: 30px;">
                                                            <span class="text-white fw-bold small">
                                                                {{ substr($payment->invoice->student?->name ?? 'U', 0, 1) }}
                                                            </span>
                                                        </div>
                                                        {{ $payment->invoice->student?->name ?? 'Unknown' }}
                                                    </div>
                                                </td>
                                                <td class="fw-bold text-success">₦{{ number_format($payment->amount, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ ucfirst($payment->payment_method) }}</span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M j, Y') }}</td>
                                                <td>
                                                    {{-- PRINT BUTTON --}}
                                                    <a href="{{ route('bursar.payments.receipt', $payment->id) }}" 
                                                    class="btn btn-sm btn-outline-primary" 
                                                    target="_blank">
                                                        Print
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    Quick Actions
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{route('finance.invoices.generate')}}" class="btn btn-outline-primary btn-lg">
                                        Create Invoice
                                    </a>
                                    <a href="{{route('finance.invoices.index')}}" class="btn btn-outline-success btn-lg">
                                        Record Payment
                                    </a>
                                    <a href="{{ route('finance.fee-structures.index') }}" class="btn btn-outline-info btn-lg">
                                        Set Fee Structure
                                    </a>
                                    <a href="{{ route('bursar.reports.index') }}" class="btn btn-outline-warning btn-lg">
                                        Generate Report
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    Top Outstanding Fees
                                </h6>
                            </div>
                            <div class="card-body">
                                @if(isset($outstandingFees) && count($outstandingFees) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($outstandingFees as $fee)
                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
                                            <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                                                style="width: 40px; height: 40px;">
                                                <span class="text-white fw-bold small">
                                                    {{ substr($fee->student?->name ?? 'U', 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="small fw-bold">{{ $fee->student?->name ?? 'Unknown Student' }}</div>
                                                <div class="text-muted small">
                                                    {{ $fee->student?->studentProfile?->classLevel?->name ?? 'N/A' }}
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold text-danger">₦{{ number_format($fee->balance, 2) }}</div>
                                                <small class="text-muted">Due</small>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="{{ route('bursar.reports.outstanding') }}" class="btn btn-sm btn-outline-danger">
                                            View All Outstanding
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <p class="text-muted small">No outstanding fees</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    Fee Collection Summary by Class
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Class</th>
                                                <th>Total Students</th>
                                                <th>Expected Amount</th>
                                                <th>Collected Amount</th>
                                                <th>Collection Rate</th>
                                                <th>Outstanding</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($classSummary as $summary)
                                            <tr>
                                                <td class="fw-bold">{{ $summary->class_name }}</td>
                                                <td>{{ $summary->total_students }}</td>
                                                <td>${{ number_format($summary->expected_amount, 2) }}</td>
                                                <td class="fw-bold text-success">${{ number_format($summary->collected_amount, 2) }}</td>
                                                <td>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-{{ $summary->collection_rate >= 80 ? 'success' : ($summary->collection_rate >= 50 ? 'warning' : 'danger') }}" 
                                                             style="width: {{ $summary->collection_rate }}%">
                                                        </div>
                                                    </div>
                                                    <small class="fw-bold">{{ $summary->collection_rate }}%</small>
                                                </td>
                                                <td class="fw-bold text-danger">${{ number_format($summary->outstanding_amount, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $summary->collection_rate >= 80 ? 'success' : ($summary->collection_rate >= 50 ? 'warning' : 'danger') }}">
                                                        {{ $summary->collection_rate >= 80 ? 'Good' : ($summary->collection_rate >= 50 ? 'Average' : 'Poor') }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Collection Chart
            const collectionCtx = document.getElementById('collectionChart');
            
            // Get data from Laravel
            const labels = {!! json_encode($chartLabels) !!};
            const data = {!! json_encode($chartData) !!};

            if (collectionCtx) {
                new Chart(collectionCtx, {
                    type: 'bar', 
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Fee Collection (₦)',
                            data: data,
                            backgroundColor: '#4e73df',  // Primary Blue
                            borderColor: '#4e73df',      // Border 
                            hoverBackgroundColor: '#2e59d9', // Darker blue on hover
                            borderRadius: 4, // Rounded top corners for bars
                            maxBarThickness: 50
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false // Hides the label box
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        // Naira sign formatting
                                        if (context.parsed.y !== null) {
                                            label += '₦' + context.parsed.y.toLocaleString();
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    borderDash: [2],
                                    drawBorder: false,
                                    color: "rgb(234, 236, 244)",
                                    zeroLineColor: "rgb(234, 236, 244)"
                                },
                                ticks: {
                                    callback: function(value) {
                                        return '₦' + value.toLocaleString();
                                    },
                                    padding: 10
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    maxTicksLimit: 6
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush