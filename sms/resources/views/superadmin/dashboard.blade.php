@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('superadmin.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">System Overview</h1>
                    <h6 class="text-dark mb-0">{{ now()->format('l, F j, Y') }}</h6>
                    <small class="text-muted">Status: <span class="text-success fw-bold">● Online</span></small>
                    <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }} - Full System Control</p>
                    
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">
                            Export Report
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">
                            Refresh
                        </button>
                    </div>
                    <a href="{{ route('superadmin.schools.create') }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-plus me-1"></i> Add School
                    </a>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-primary text-white shadow h-100 border-0">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">
                                        Total Schools</div>
                                    <div class="h2 mb-0 fw-bold">{{ $stats['total_schools'] ?? 0 }}</div>
                                    <div class="mt-2 small">
                                        <span>{{ $stats['school_growth'] ?? 0 }}% growth</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-success text-white shadow h-100 border-0">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">
                                        Total Users</div>
                                    <div class="h2 mb-0 fw-bold">{{ $stats['total_users'] ?? 0 }}</div>
                                    <div class="mt-2 small">
                                        <span>Across all schools</span>
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
                                        Active Sessions</div>
                                    <div class="h2 mb-0 fw-bold">{{ $stats['active_sessions'] ?? 0 }}</div>
                                    <div class="mt-2 small">
                                        <span>Currently online</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-warning text-white shadow h-100 border-0">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">
                                        System Alerts</div>
                                    <div class="h2 mb-0 fw-bold">{{ $stats['system_alerts'] ?? 0 }}</div>
                                    <div class="mt-2 small">
                                        <span>Requires attention</span>
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
                                System Performance & Growth
                            </h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Last 30 Days
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Last 7 Days</a></li>
                                    <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
                                    <li><a class="dropdown-item" href="#">Last 90 Days</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-3">
                                <div style="height: 300px;">
                                    <canvas id="systemPerformanceChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                Recently Added Schools
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>School Name</th>
                                            <th>Email</th>
                                            <th>Principal</th>
                                            <th>Created</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentSchools as $school)
                                        <tr>
                                            <td>
                                                <a href="{{ route('superadmin.schools.show', $school) }}" class="text-decoration-none">
                                                    <strong>{{ $school->name }}</strong>
                                                </a>
                                            </td>
                                            <td>{{ $school->email }}</td>
                                            <td>{{ $school->principal_name ?? 'N/A' }}</td>
                                            <td>{{ $school->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('superadmin.schools.index') }}" class="btn btn-outline-primary btn-sm">
                                    View All Schools
                                </a>
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
                            <a href="{{ route('superadmin.schools.create') }}" class="btn btn-outline-primary btn-lg">
                                Add School
                            </a>
                            <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-success btn-lg">
                                Manage Users
                            </a>
                            <a href="#" class="btn btn-outline-info btn-lg">
                                System Settings
                            </a>
                            <a href="#" class="btn btn-outline-warning btn-lg">
                                View Reports
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-bold text-danger">
                            System Alerts
                        </h6>
                    </div>
                </div>

                <div class="card shadow mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-danger">
                                Critical Insights
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                
                                {{-- Inactive Schools --}}
                                <div class="list-group-item d-flex align-items-center px-0 border-0">
                                    <div class="me-3">
                                        <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-school"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small fw-bold">Inactive Schools</div>
                                        <div class="text-muted small">
                                            {{ $platformHealth['inactive_schools'] }} schools have 0 users.
                                        </div>
                                    </div>
                                    @if($platformHealth['inactive_schools'] > 0)
                                        <button class="btn btn-sm btn-outline-danger">View</button>
                                    @endif
                                </div>

                                {{-- New Signups --}}
                                <div class="list-group-item d-flex align-items-center px-0 border-0">
                                    <div class="me-3">
                                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small fw-bold">New Schools Today</div>
                                        <div class="text-muted small">
                                            {{ $platformHealth['new_today'] }} registered in last 24h.
                                        </div>
                                    </div>
                                </div>

                                {{-- Server Storage --}}
                                <div class="list-group-item d-flex align-items-center px-0 border-0">
                                    <div class="me-3">
                                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-hdd"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small fw-bold">Server Storage</div>
                                        <div class="text-muted small">
                                            {{ $platformHealth['disk_usage'] }}% used.
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                Platform Targets
                            </h6>
                        </div>
                        <div class="card-body">
                            
                            {{-- User Growth --}}
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small fw-bold text-gray-800">User Goal (1,000)</span>
                                    <span class="small fw-bold text-primary">{{ $platformHealth['user_progress'] }}%</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-primary" role="progressbar" 
                                        style="width: {{ $platformHealth['user_progress'] }}%">
                                    </div>
                                </div>
                                <small class="text-muted">Current: {{ number_format($stats['total_users']) }} users</small>
                            </div>

                            {{-- School Growth --}}
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small fw-bold text-gray-800">School Goal (50)</span>
                                    <span class="small fw-bold text-success">{{ $platformHealth['school_progress'] }}%</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                        style="width: {{ $platformHealth['school_progress'] }}%">
                                    </div>
                                </div>
                                <small class="text-muted">Current: {{ $stats['total_schools'] }} schools</small>
                            </div>

                            {{-- Disk Space (Visualizing the storage alert) --}}
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small fw-bold text-gray-800">Server Capacity</span>
                                    <span class="small fw-bold text-info">{{ $platformHealth['disk_usage'] }}%</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-info" role="progressbar" 
                                        style="width: {{ $platformHealth['disk_usage'] }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-3 border-top pt-3">
                                <small class="text-muted">Last updated: {{ now()->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="row mt-4">
                {{-- Students --}}
                <div class="col-md-3">
                    <div class="card shadow h-100 border-bottom-primary">
                        <div class="card-body text-center">
                            <h4 class="fw-bold text-gray-800">{{ number_format($stats['total_students']) }}</h4>
                            <p class="text-muted mb-1 small text-uppercase">Total Students</p>
                            
                            {{-- Dynamic Growth Badge --}}
                            <small class="{{ $stats['student_growth'] >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                <i class="fas fa-arrow-{{ $stats['student_growth'] >= 0 ? 'up' : 'down' }}"></i> 
                                {{ abs($stats['student_growth']) }}%
                            </small>
                            <span class="text-muted small">vs last month</span>
                        </div>
                    </div>
                </div>

                {{-- Teachers --}}
                <div class="col-md-3">
                    <div class="card shadow h-100 border-bottom-success">
                        <div class="card-body text-center">
                            <h4 class="fw-bold text-gray-800">{{ number_format($stats['total_teachers']) }}</h4>
                            <p class="text-muted mb-1 small text-uppercase">Total Teachers</p>

                            <small class="{{ $stats['teacher_growth'] >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                <i class="fas fa-arrow-{{ $stats['teacher_growth'] >= 0 ? 'up' : 'down' }}"></i> 
                                {{ abs($stats['teacher_growth']) }}%
                            </small>
                            <span class="text-muted small">vs last month</span>
                        </div>
                    </div>
                </div>

                {{-- Parents --}}
                <div class="col-md-3">
                    <div class="card shadow h-100 border-bottom-info">
                        <div class="card-body text-center">
                            <h4 class="fw-bold text-gray-800">{{ number_format($stats['total_parents']) }}</h4>
                            <p class="text-muted mb-1 small text-uppercase">Total Parents</p>

                            <small class="{{ $stats['parent_growth'] >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                <i class="fas fa-arrow-{{ $stats['parent_growth'] >= 0 ? 'up' : 'down' }}"></i> 
                                {{ abs($stats['parent_growth']) }}%
                            </small>
                            <span class="text-muted small">vs last month</span>
                        </div>
                    </div>
                </div>

                {{-- Revenue --}}
                <div class="col-md-3">
                    <div class="card shadow h-100 border-bottom-warning">
                        <div class="card-body text-center">
                            <h4 class="fw-bold text-gray-800">₦{{ number_format($stats['revenue']) }}</h4>
                            <p class="text-muted mb-1 small text-uppercase">Total Revenue</p>

                            <small class="{{ $stats['revenue_growth'] >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                <i class="fas fa-arrow-{{ $stats['revenue_growth'] >= 0 ? 'up' : 'down' }}"></i> 
                                {{ abs($stats['revenue_growth']) }}%
                            </small>
                            <span class="text-muted small">vs last month</span>
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
    .sidebar {
        min-height: calc(100vh - 56px);
        box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
    }

    .sidebar .nav-link {
        color: #adb5bd;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem;
        margin: 0.125rem 0.5rem;
        transition: all 0.15s ease;
    }

    .sidebar .nav-link:hover {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar .nav-link.active {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.2);
    }

    .card {
        border: none;
        border-radius: 0.5rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .progress {
        height: 8px;
        border-radius: 4px;
    }

    @media (max-width: 767.98px) {
        .sidebar {
            position: fixed;
            top: 56px;
            bottom: 0;
            left: -100%;
            width: 100%;
            transition: left 0.3s ease;
            z-index: 1000;
        }

        .sidebar.show {
            left: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // System Performance Chart
        const systemCtx = document.getElementById('systemPerformanceChart');
        
        // DATA FROM LARAVEL CONTROLLER
        const labels = {!! json_encode($chartLabels) !!};
        const schoolData = {!! json_encode($schoolGrowthData) !!};
        const userData = {!! json_encode($userGrowthData) !!};

        if (systemCtx) {
            new Chart(systemCtx, {
                type: 'line',
                data: {
                    labels: labels, // Dynamic Months
                    datasets: [
                        {
                            label: 'New Schools',
                            data: schoolData, // Dynamic Data
                            borderColor: 'rgba(54, 162, 235, 1)', // Blue
                            backgroundColor: 'rgba(54, 162, 235, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        },
                        {
                            label: 'New Users',
                            data: userData, // Dynamic Data
                            borderColor: 'rgba(28, 200, 138, 1)', // Green (Changed to distinct color)
                            backgroundColor: 'rgba(28, 200, 138, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Ensuring it fits the container height
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
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
                                stepSize: 1 // No decimals for counts (e.g. can't have 1.5 schools)
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush