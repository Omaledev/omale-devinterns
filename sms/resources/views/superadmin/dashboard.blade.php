@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
       @include('superadmin.partials.sidebar')
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Header -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">System Overview</h1>
                    <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }} - Full System Control</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-download me-1"></i>Export Report
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-sync me-1"></i>Refresh
                        </button>
                    </div>
                    <a href="{{ route('superadmin.schools.create') }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-plus me-1"></i>Add School
                    </a>
                </div>
            </div>

            <!-- System Stats Cards -->
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
                                        <i class="fas fa-arrow-up me-1"></i>
                                        <span>{{ $stats['school_growth'] ?? 0 }}% growth</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-school fa-2x text-white-50"></i>
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
                                        <i class="fas fa-users me-1"></i>
                                        <span>Across all schools</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-white-50"></i>
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
                                        <i class="fas fa-signal me-1"></i>
                                        <span>Currently online</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-plug fa-2x text-white-50"></i>
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
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        <span>Requires attention</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-bell fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and System Overview -->
            <div class="row">
                <!-- System Performance Chart -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-chart-line me-2"></i>System Performance & Growth
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

                    <!-- Recent Schools -->
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-school me-2"></i>Recently Added Schools
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

                <!-- Quick Actions & System Info -->
                <div class="col-xl-4 col-lg-5">
                    <!-- Quick Actions -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-bolt me-2"></i>Quick Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <a href="{{ route('superadmin.schools.create') }}" class="btn btn-outline-primary w-100 h-100 p-3 d-flex flex-column align-items-center">
                                        <i class="fas fa-school fa-2x mb-2"></i>
                                        <span>Add School</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('superadmin.schools.create-user', $school) }}" class="btn btn-outline-success w-100 h-100 p-3 d-flex flex-column align-items-center">
                                        <i class="fas fa-user-plus fa-2x mb-2"></i>
                                        <span>Create User</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="#" class="btn btn-outline-info w-100 h-100 p-3 d-flex flex-column align-items-center">
                                        <i class="fas fa-cog fa-2x mb-2"></i>
                                        <span>System Settings</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="#" class="btn btn-outline-warning w-100 h-100 p-3 d-flex flex-column align-items-center">
                                        <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                        <span>View Reports</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Alerts -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>System Alerts
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex align-items-center px-0 border-0">
                                    <div class="bg-warning rounded p-2 me-3">
                                        <i class="fas fa-database text-white"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small fw-bold">Backup Required</div>
                                        <div class="text-muted small">Last backup: 2 days ago</div>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center px-0 border-0">
                                    <div class="bg-info rounded p-2 me-3">
                                        <i class="fas fa-server text-white"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small fw-bold">System Update Available</div>
                                        <div class="text-muted small">Version 2.1.0 ready</div>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center px-0 border-0">
                                    <div class="bg-success rounded p-2 me-3">
                                        <i class="fas fa-shield-alt text-white"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small fw-bold">Security Scan Clean</div>
                                        <div class="text-muted small">No threats detected</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Status -->
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-heartbeat me-2"></i>System Status
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>CPU Usage</span>
                                    <span class="fw-bold">42%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: 42%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Memory Usage</span>
                                    <span class="fw-bold">68%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: 68%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Storage</span>
                                    <span class="fw-bold">35%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width: 35%"></div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <small class="text-muted">Last updated: {{ now()->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional System Metrics -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-user-graduate fa-2x text-primary mb-3"></i>
                            <h4 class="fw-bold">{{ $stats['total_students'] ?? 0 }}</h4>
                            <p class="text-muted mb-1">Total Students</p>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                12% increase
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-chalkboard-teacher fa-2x text-success mb-3"></i>
                            <h4 class="fw-bold">{{ $stats['total_teachers'] ?? 0 }}</h4>
                            <p class="text-muted mb-1">Total Teachers</p>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                5% increase
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x text-info mb-3"></i>
                            <h4 class="fw-bold">{{ $stats['total_parents'] ?? 0 }}</h4>
                            <p class="text-muted mb-1">Total Parents</p>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                8% increase
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-money-bill-wave fa-2x text-warning mb-3"></i>
                            <h4 class="fw-bold">₦{{ number_format($stats['revenue'] ?? 0) }}</h4>
                            <p class="text-muted mb-1">Monthly Revenue</p>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                15% increase
                            </small>
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

    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
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
        if (systemCtx) {
            new Chart(systemCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [
                        {
                            label: 'New Schools',
                            data: [12, 19, 15, 25, 22, 30, 28],
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'New Users',
                            data: [8, 12, 18, 22, 28, 35, 40],
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#333'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                color: '#333'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                color: '#333'
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
