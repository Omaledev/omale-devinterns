@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('bursar.partials.sidebar')

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <div>
                        <h1 class="h2">Bursar Dashboard</h1>
                        <p class="text-muted mb-0">Financial Management Overview</p>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-print me-1"></i>Print
                            </button>
                        </div>
                        <button type="button" class="btn btn-sm btn-warning">
                            <i class="fas fa-plus me-1"></i>Generate Invoices
                        </button>
                    </div>
                </div>

                <!-- Financial Overview Cards -->
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
                                            <i class="fas fa-calendar me-1"></i>
                                            <span>This month</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-money-bill-wave fa-2x text-white-50"></i>
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
                                            <i class="fas fa-file-invoice me-1"></i>
                                            <span>To be processed</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-file-invoice-dollar fa-2x text-white-50"></i>
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
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            <span>Total due</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-credit-card fa-2x text-white-50"></i>
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
                                            <i class="fas fa-chart-line me-1"></i>
                                            <span>This term</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-percentage fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Charts & Recent Activity -->
                <div class="row">
                    <!-- Collection Chart -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-chart-line me-2"></i>Fee Collection Trend
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

                        <!-- Recent Payments -->
                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-history me-2"></i>Recent Payments
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentPayments as $payment)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                            style="width: 30px; height: 30px;">
                                                            <span class="text-white fw-bold small">{{ substr($payment->student->user->name, 0, 1) }}</span>
                                                        </div>
                                                        {{ $payment->student->user->name }}
                                                    </div>
                                                </td>
                                                <td class="fw-bold text-success">${{ number_format($payment->amount, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ ucfirst($payment->payment_method) }}</span>
                                                </td>
                                                <td>{{ $payment->payment_date->format('M j, Y') }}</td>
                                                <td>
                                                    <span class="badge bg-success">Verified</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions & Outstanding Fees -->
                    <div class="col-xl-4 col-lg-5">
                        <!-- Quick Actions -->
                        <div class="card shadow mb-4">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-bolt me-2"></i>Quick Actions
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="" class="btn btn-outline-primary btn-lg">
                                        <i class="fas fa-file-invoice me-2"></i>Create Invoice
                                    </a>
                                    <a href="" class="btn btn-outline-success btn-lg">
                                        <i class="fas fa-credit-card me-2"></i>Record Payment
                                    </a>
                                    <a href="" class="btn btn-outline-info btn-lg">
                                        <i class="fas fa-money-bill-wave me-2"></i>Set Fee Structure
                                    </a>
                                    <a href="" class="btn btn-outline-warning btn-lg">
                                        <i class="fas fa-chart-pie me-2"></i>Generate Report
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Top Outstanding Fees -->
                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Top Outstanding Fees
                                </h6>
                            </div>
                            <div class="card-body">
                                @if(isset($outstandingFees) && count($outstandingFees) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($outstandingFees as $fee)
                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
                                            <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                                                style="width: 40px; height: 40px;">
                                                {{-- FIXED: Use safe navigation and remove extra ->user --}}
                                                <span class="text-white fw-bold small">
                                                    {{ substr($fee->student?->name ?? 'U', 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                {{-- FIXED: Direct access to student name --}}
                                                <div class="small fw-bold">{{ $fee->student?->name ?? 'Unknown Student' }}</div>
                                                
                                                {{-- FIXED: Correct path to Class Level --}}
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
                                        <a href="{{ route('finance.invoices.index') }}" class="btn btn-sm btn-outline-danger">
                                            View All Outstanding
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                        <p class="text-muted small">No outstanding fees</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fee Summary by Class -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-chart-pie me-2"></i>Fee Collection Summary by Class
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
            if (collectionCtx) {
                new Chart(collectionCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Fee Collection ($)',
                            data: [12000, 19000, 15000, 25000, 22000, 30000],
                            backgroundColor: 'rgba(54, 162, 235, 0.8)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush  