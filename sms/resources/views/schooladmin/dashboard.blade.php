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
                                Export
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('schooladmin.students.export') }}">
                                        Students (Excel)
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('schooladmin.timetables.export') }}">
                                        Timetable (PDF)
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                            Print
                        </button>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="quickAddDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Quick Add
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="quickAddDropdown">
                            <li><h6 class="dropdown-header">Academic</h6></li>
                            <li><a class="dropdown-item" href="{{ route('schooladmin.assessments.index') }}">Assessment Setup</a></li>
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
                    <div class="card bg-primary text-white shadow h-100 border-0 ">
                        <div class="card-body" >
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">Total Students</div>
                                    <div class="h2 mb-0 fw-bold">{{ $stats['total_students'] ?? 0 }}</div>
                                    <div class="mt-2 small"><span>5.2% since last month</span></div>
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
                                    <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">Total Teachers</div>
                                    <div class="h2 mb-0 fw-bold">{{ $stats['total_teachers'] ?? 0 }}</div>
                                    <div class="mt-2 small"><span>2.1% since last month</span></div>
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
                                    <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">Total Parents</div>
                                    <div class="h2 mb-0 fw-bold">{{ $stats['total_parents'] ?? 0 }}</div>
                                    <div class="mt-2 small"><span>8.7% since last month</span></div>
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
                                    <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">Pending Approvals</div>
                                    <div class="h2 mb-0 fw-bold">{{ $stats['pending_approvals'] ?? 0 }}</div>
                                    <div class="mt-2 small"><span>Requires attention</span></div>
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
                            <h6 class="m-0 fw-bold text-primary">School Performance Chat</h6>
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
                        {{-- Academic Excellence --}}
                        <div class="col-md-4 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-body text-center">
                                    <h4 class="fw-bold h5">Academic Excellence</h4>
                                    <p class="text-muted small">Average Grade Score</p>
                                    <div class="progress mb-2" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: {{ $performance['academic'] }}%"></div>
                                    </div>
                                    <small class="text-muted fw-bold">{{ $performance['academic'] }} / 100 Avg</small>
                                </div>
                            </div>
                        </div>

                        {{-- Attendance Rate --}}
                        <div class="col-md-4 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-body text-center">
                                    <h4 class="fw-bold h5">Attendance Rate</h4>
                                    <p class="text-muted small">Daily Presence</p>
                                    <div class="progress mb-2" style="height: 6px;">
                                        <div class="progress-bar bg-info" style="width: {{ $performance['attendance'] }}%"></div>
                                    </div>
                                    <small class="text-muted fw-bold">{{ $performance['attendance'] }}% Avg</small>
                                </div>
                            </div>
                        </div>

                        {{-- Fee Collection  --}}
                        <div class="col-md-4 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-body text-center">
                                    <h4 class="fw-bold h5">Fee Collection</h4>
                                    <p class="text-muted small">Revenue Collected</p>
                                    <div class="progress mb-2" style="height: 6px;">
                                        <div class="progress-bar bg-warning" style="width: {{ $performance['fees'] }}%"></div>
                                    </div>
                                    <small class="text-muted fw-bold">{{ $performance['fees'] }}% Collected</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('schooladmin.students.create') }}" class="btn btn-outline-primary btn-lg">
                                    Add Student
                                </a>
                                <a href="{{ route('schooladmin.teachers.create') }}" class="btn btn-outline-success btn-lg">
                                    Add Teacher
                                </a>
                                <a href="{{ route('schooladmin.class-levels.create') }}" class="btn btn-outline-warning btn-lg">
                                    Create Class
                                </a>
                                <a href="{{ route('schooladmin.timetables.index') }}" class="btn btn-outline-dark btn-lg">
                                    Timetable
                                </a>
                            </div>
                        </div>
                    </div>

                   <div class="card shadow mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">Recent Activity</h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @forelse($recentActivities as $activity)
                                    <div class="list-group-item d-flex align-items-center px-0 border-0">
                                        {{-- Icon --}}
                                        <div class="me-3">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                <i class="{{ $activity['icon'] }}"></i>
                                            </div>
                                        </div>
                                        {{-- Message --}}
                                        <div class="flex-grow-1">
                                            <div class="small text-dark">{!! $activity['message'] !!}</div>
                                            <div class="text-muted small" style="font-size: 0.75rem;">
                                                {{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted small">
                                        No recent activity found.
                                    </div>
                                @endforelse
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
        
        // DATA FROM LARAVEL
        const labels = {!! json_encode($chartLabels) !!};
        const data = {!! json_encode($studentGrowthData) !!};

        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels, // Dynamic Months
                    datasets: [{
                        label: 'New Student Enrollments', // Updated Label
                        data: data, // Real Data
                        borderColor: '#4e73df', // Bootstrap Primary Blue
                        backgroundColor: 'rgba(78, 115, 223, 0.05)', // Very light blue fill
                        pointRadius: 3,
                        pointBackgroundColor: '#4e73df',
                        pointBorderColor: '#4e73df',
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: '#4e73df',
                        pointHoverBorderColor: '#4e73df',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        tension: 0.3, // Smooth curve
                        fill: true
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Hide legend for cleaner look
                        },
                        tooltip: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyColor: "#858796",
                            titleColor: '#6e707e',
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10,
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 7
                            }
                        },
                        y: {
                            ticks: {
                                maxTicksLimit: 5,
                                padding: 10,
                                stepSize: 1 // Ensuring whole numbers (no 1.5 students)
                            },
                            grid: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush