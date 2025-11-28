@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 60px; height: 60px;">
                            <span class="text-white fw-bold fs-4">A</span>
                        </div>
                        <h6 class="text-white mb-1">{{ auth()->user()->school->name }}</h6>
                        <small class="text-white-50">School Admin</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="{{ route('schooladmin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.students.index') }}">
                                <i class="fas fa-user-graduate me-2"></i>
                                Students
                                <span class="badge bg-primary float-end">{{ $stats['total_students'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.teachers.index') }}">
                                <i class="fas fa-chalkboard-teacher me-2"></i>
                                Teachers
                                <span class="badge bg-success float-end">{{ $stats['total_teachers'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.parents.index')}}">
                                <i class="fas fa-users me-2"></i>
                                Parents
                                <span class="badge bg-info float-end">{{ $stats['total_parents'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.bursars.index') }}">
                                <i class="fas fa-money-check me-2"></i>
                                Bursars
                                <span class="badge bg-warning float-end">{{ $stats['total_bursars'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.class-levels.index')}}">
                                <i class="fas fa-door-open me-2"></i>
                                Class Levels
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.sections.index') }}">
                                <i class="fas fa-layer-group me-2"></i>
                                Sections
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="route('schooladmin.subjects.index') }}">
                                <i class="fas fa-book me-2"></i>
                                Subjects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="#">
                                <i class="fas fa-calendar-check me-2"></i>
                                Attendance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="#">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                Fees
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="#">
                                <i class="fas fa-bullhorn me-2"></i>
                                Notice
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="#">
                                <i class="fas fa-bus me-2"></i>
                                Transport
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="#">
                                <i class="fas fa-bed me-2"></i>
                                Hostel
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Header -->
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <div>
                        <h1 class="h2">Dashboard Overview</h1>
                        <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}</p>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-print me-1"></i>Print
                            </button>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Quick Add
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card bg-primary text-white shadow h-100 border-0">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">
                                            Total Students</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['total_students'] ?? 0 }}</div>
                                        <div class="mt-2 small">
                                            <i class="fas fa-arrow-up me-1"></i>
                                            <span>5.2% since last month</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-graduate fa-2x text-white-50"></i>
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
                                            Total Teachers</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['total_teachers'] ?? 0 }}</div>
                                        <div class="mt-2 small">
                                            <i class="fas fa-arrow-up me-1"></i>
                                            <span>2.1% since last month</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-chalkboard-teacher fa-2x text-white-50"></i>
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
                                            Total Parents</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['total_parents'] ?? 0 }}</div>
                                        <div class="mt-2 small">
                                            <i class="fas fa-arrow-up me-1"></i>
                                            <span>8.7% since last month</span>
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
                        <div class="card bg-warning text-white shadow h-100 border-0">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">
                                            Pending Approvals</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['pending_approvals'] ?? 0 }}</div>
                                        <div class="mt-2 small">
                                            <i class="fas fa-clock me-1"></i>
                                            <span>Requires attention</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts and Additional Content -->
                <div class="row">
                    <!-- School Performance Chart -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-chart-line me-2"></i>School Performance
                                </h6>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        This Month
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">This Week</a></li>
                                        <li><a class="dropdown-item" href="#">This Month</a></li>
                                        <li><a class="dropdown-item" href="#">This Year</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="p-3">
                                    <div style="height: 300px;">
                                        <canvas id="performanceChart"></canvas>
                                    </div>
                                    <p class="mt-2 mb-0 text-center">Student Performance Trend</p>
                                    <small class="text-center d-block">Last 6 months progress</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-bolt me-2"></i>Quick Actions
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <a href="{{ route('schooladmin.students.create') }}"
                                            class="btn btn-outline-primary w-100 h-100 p-3 d-flex flex-column align-items-center">
                                            <i class="fas fa-user-plus fa-2x mb-2"></i>
                                            <span>Add Student</span>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('schooladmin.teachers.create') }}"
                                            class="btn btn-outline-success w-100 h-100 p-3 d-flex flex-column align-items-center">
                                            <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                                            <span>Add Teacher</span>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('schooladmin.parents.create') }}"
                                            class="btn btn-outline-info w-100 h-100 p-3 d-flex flex-column align-items-center">
                                            <i class="fas fa-user-friends fa-2x mb-2"></i>
                                            <span>Add Parent</span>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('schooladmin.class-levels.create') }}"
                                            class="btn btn-outline-warning w-100 h-100 p-3 d-flex flex-column align-items-center">
                                            <i class="fas fa-door-open fa-2x mb-2"></i>
                                            <span>Create Class</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-history me-2"></i>Recent Activity
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex align-items-center px-0 border-0">
                                        <div class="bg-success rounded p-2 me-3">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="small fw-bold">New student registered</div>
                                            <div class="text-muted small">2 minutes ago</div>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex align-items-center px-0 border-0">
                                        <div class="bg-info rounded p-2 me-3">
                                            <i class="fas fa-chalkboard-teacher text-white"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="small fw-bold">Teacher assignment updated</div>
                                            <div class="text-muted small">1 hour ago</div>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex align-items-center px-0 border-0">
                                        <div class="bg-warning rounded p-2 me-3">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="small fw-bold">Pending approvals waiting</div>
                                            <div class="text-muted small">5 hours ago</div>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex align-items-center px-0 border-0">
                                        <div class="bg-primary rounded p-2 me-3">
                                            <i class="fas fa-money-bill text-white"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="small fw-bold">Fee payment received</div>
                                            <div class="text-muted small">Yesterday</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Stats Row -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card shadow">
                            <div class="card-body text-center">
                                <i class="fas fa-graduation-cap fa-2x text-primary mb-3"></i>
                                <h4 class="fw-bold">Academic Excellence</h4>
                                <p class="text-muted">Track and improve student performance</p>
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-success" style="width: 85%">85%</div>
                                </div>
                                <small class="text-muted">Overall academic performance</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-pie fa-2x text-info mb-3"></i>
                                <h4 class="fw-bold">Attendance Rate</h4>
                                <p class="text-muted">Monitor daily attendance patterns</p>
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-info" style="width: 92%">92%</div>
                                </div>
                                <small class="text-muted">Current month attendance</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow">
                            <div class="card-body text-center">
                                <i class="fas fa-tasks fa-2x text-warning mb-3"></i>
                                <h4 class="fw-bold">Task Completion</h4>
                                <p class="text-muted">Manage administrative tasks</p>
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-warning" style="width: 78%">78%</div>
                                </div>
                                <small class="text-muted">Monthly tasks completed</small>
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
        #performanceChart {
            width: 100% !important;
            height: 100% !important;
        }

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

        .chart-area {
            position: relative;
            height: 300px;
            width: 100%;
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
            const ctx = document.getElementById('performanceChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Student Performance',
                            data: [65, 59, 80, 81, 56, 55],
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                labels: {
                                    color: '#333' // dark color
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)' // light gray
                                },
                                ticks: {
                                    color: '#333' // dark color
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
