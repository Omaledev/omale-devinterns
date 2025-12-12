@extends('layouts.app')



@section('content')
<div class="container-fluid">
    <div class="row">
        @include('schooladmin.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">Dashboard Overview</h1>
                    <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}</p>
                </div>
               <div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group me-2">
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-download me-1"></i>Export
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="{{ route('schooladmin.students.export') }}">
                        <i class="fas fa-user-graduate me-2"></i>Students (Excel)
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('schooladmin.timetables.export') }}">
                        <i class="fas fa-calendar-alt me-2"></i>Timetable (PDF)
                    </a>
                </li>
            </ul>
        </div>

        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
            <i class="fas fa-print me-1"></i>Print
        </button>
    </div>

    <div class="dropdown">
        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="quickAddDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-plus me-1"></i>Quick Add
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="quickAddDropdown">
            <li><h6 class="dropdown-header">Academic</h6></li>
            <li><a class="dropdown-item" href="{{ route('schooladmin.timetables.create') }}">Add Timetable</a></li>
            <li><a class="dropdown-item" href="{{ route('schooladmin.class-levels.create') }}">Add Class</a></li>
            <li><a class="dropdown-item" href="{{ route('schooladmin.subjects.create') }}">Add Subject</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><h6 class="dropdown-header">Users</h6></li>
            <li><a class="dropdown-item" href="{{ route('schooladmin.students.create') }}">Add Student</a></li>
            <li><a class="dropdown-item" href="{{ route('schooladmin.teachers.create') }}">Add Teacher</a></li>
        </ul>
    </div>
</div>
            </div>

            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-primary text-white shadow h-100 border-0">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">Total Students</div>
                                    <div class="h2 mb-0 fw-bold">{{ $stats['total_students'] ?? 0 }}</div>
                                    <div class="mt-2 small"><i class="fas fa-arrow-up me-1"></i><span>5.2% since last month</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-user-graduate fa-2x text-white-50"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-success text-white shadow h-100 border-0">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">Total Teachers</div>
                                    <div class="h2 mb-0 fw-bold">{{ $stats['total_teachers'] ?? 0 }}</div>
                                    <div class="mt-2 small"><i class="fas fa-arrow-up me-1"></i><span>2.1% since last month</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-chalkboard-teacher fa-2x text-white-50"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-info text-white shadow h-100 border-0">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">Total Parents</div>
                                    <div class="h2 mb-0 fw-bold">{{ $stats['total_parents'] ?? 0 }}</div>
                                    <div class="mt-2 small"><i class="fas fa-arrow-up me-1"></i><span>8.7% since last month</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-users fa-2x text-white-50"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-warning text-white shadow h-100 border-0">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">Pending Approvals</div>
                                    <div class="h2 mb-0 fw-bold">{{ $stats['pending_approvals'] ?? 0 }}</div>
                                    <div class="mt-2 small"><i class="fas fa-clock me-1"></i><span>Requires attention</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-clock fa-2x text-white-50"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-xl-8 col-lg-7">

                    <div class="card shadow mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-primary"><i class="fas fa-chart-line me-2"></i>School Performance</h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">This Month</button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">This Week</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-3">
                                <div style="height: 300px;"><canvas id="performanceChart"></canvas></div>
                                <p class="mt-2 mb-0 text-center">Student Performance Trend</p>
                                <small class="text-center d-block">Last 6 months progress</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-graduation-cap fa-2x text-primary mb-3"></i>
                                    <h4 class="fw-bold h5">Academic Excellence</h4>
                                    <p class="text-muted small">Track performance</p>
                                    <div class="progress mb-2" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: 85%"></div>
                                    </div>
                                    <small class="text-muted">85% Avg</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-pie fa-2x text-info mb-3"></i>
                                    <h4 class="fw-bold h5">Attendance Rate</h4>
                                    <p class="text-muted small">Daily patterns</p>
                                    <div class="progress mb-2" style="height: 6px;">
                                        <div class="progress-bar bg-info" style="width: 92%"></div>
                                    </div>
                                    <small class="text-muted">92% Avg</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-tasks fa-2x text-warning mb-3"></i>
                                    <h4 class="fw-bold h5">Task Completion</h4>
                                    <p class="text-muted small">Admin tasks</p>
                                    <div class="progress mb-2" style="height: 6px;">
                                        <div class="progress-bar bg-warning" style="width: 78%"></div>
                                    </div>
                                    <small class="text-muted">78% Done</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">

                    <div class="card shadow mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <a href="{{ route('schooladmin.students.create') }}" class="btn btn-outline-primary w-100 h-100 p-3 d-flex flex-column align-items-center">
                                        <i class="fas fa-user-plus fa-2x mb-2"></i><span>Add Student</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('schooladmin.teachers.create') }}" class="btn btn-outline-success w-100 h-100 p-3 d-flex flex-column align-items-center">
                                        <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i><span>Add Teacher</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('schooladmin.class-levels.create') }}" class="btn btn-outline-warning w-100 h-100 p-3 d-flex flex-column align-items-center">
                                        <i class="fas fa-door-open fa-2x mb-2"></i><span>Create Class</span>
                                    </a>
                                </div>

                                <div class="col-6">
                                    <a href="{{ route('schooladmin.timetables.index') }}" class="btn btn-outline-dark w-100 h-100 p-3 d-flex flex-column align-items-center">
                                        <i class="fas fa-calendar-alt fa-2x mb-2"></i><span>Timetable</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary"><i class="fas fa-history me-2"></i>Recent Activity</h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex align-items-center px-0 border-0">
                                    <div class="bg-success rounded p-2 me-3"><i class="fas fa-user text-white"></i></div>
                                    <div class="flex-grow-1">
                                        <div class="small fw-bold">New student registered</div>
                                        <div class="text-muted small">2 minutes ago</div>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center px-0 border-0">
                                    <div class="bg-info rounded p-2 me-3"><i class="fas fa-chalkboard-teacher text-white"></i></div>
                                    <div class="flex-grow-1">
                                        <div class="small fw-bold">Teacher assignment updated</div>
                                        <div class="text-muted small">1 hour ago</div>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center px-0 border-0">
                                    <div class="bg-warning rounded p-2 me-3"><i class="fas fa-exclamation-triangle text-white"></i></div>
                                    <div class="flex-grow-1">
                                        <div class="small fw-bold">Pending approvals waiting</div>
                                        <div class="text-muted small">5 hours ago</div>
                                    </div>
                                </div>
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