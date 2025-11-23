@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Student Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        @if(auth()->user()->studentProfile && auth()->user()->studentProfile->photo)
                            <img src="{{ asset('storage/' . auth()->user()->studentProfile->photo) }}" 
                                 class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                style="width: 60px; height: 60px;">
                                <span class="text-primary fw-bold fs-4">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <h6 class="text-white mb-1">{{ auth()->user()->name }}</h6>
                        <small class="text-white-50">Student • {{ auth()->user()->studentProfile->classLevel->name ?? 'N/A' }}</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="{{ route('student.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('student.timetable') }}">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Timetable
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('student.attendance') }}">
                                <i class="fas fa-calendar-check me-2"></i>
                                Attendance
                                <span class="badge bg-success float-end">{{ $stats['attendance_rate'] ?? '0' }}%</span>
                            </a>
                        </li>

                        <!-- <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('student.books') }}">
                                <i class="fas fa-book-open me-2"></i>
                                Study Materials
                                <span class="badge bg-info float-end">{{ $stats['new_books'] ?? 0 }}</span>
                            </a>
                        </li> -->

                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('student.results') }}">
                                <i class="fas fa-chart-bar me-2"></i>
                                Results
                                <span class="badge bg-info float-end">{{ $stats['average_grade'] ?? 'N/A' }}</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('student.books') }}">
                                <i class="fas fa-book-open me-2"></i>
                                Study Books
                                <span class="badge bg-info float-end">{{ $stats['new_books'] ?? 0 }}</span>
                            </a>
                        </li> 
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('student.fees') }}">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                Fees
                                @if(($stats['fee_balance'] ?? 0) > 0)
                                <span class="badge bg-danger float-end">Due</span>
                                @else
                                <span class="badge bg-success float-end">Paid</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('student.assignments') }}">
                                <i class="fas fa-tasks me-2"></i>
                                Assignments
                                <span class="badge bg-warning float-end">{{ $stats['pending_assignments'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('student.subjects') }}">
                                <i class="fas fa-book me-2"></i>
                                Subjects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('student.teachers') }}">
                                <i class="fas fa-chalkboard-teacher me-2"></i>
                                Teachers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('student.messages') }}">
                                <i class="fas fa-comments me-2"></i>
                                Messages
                                <span class="badge bg-primary float-end">{{ $stats['unread_messages'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('student.announcements') }}">
                                <i class="fas fa-bullhorn me-2"></i>
                                Announcements
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <div>
                        <h1 class="h2">Student Dashboard</h1>
                        <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}</p>
                        <small class="text-muted">
                            Class: {{ auth()->user()->studentProfile->classLevel->name ?? 'N/A' }} • 
                            Section: {{ auth()->user()->studentProfile->section->name ?? 'N/A' }}
                        </small>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-1"></i>Report Card
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-print me-1"></i>Print Schedule
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card bg-success text-white shadow h-100 border-0">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">
                                            Attendance Rate</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['attendance_rate'] ?? 0 }}%</div>
                                        <div class="mt-2 small">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            <span>This month</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-check fa-2x text-white-50"></i>
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
                                            Average Grade</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['average_grade'] ?? 'N/A' }}</div>
                                        <div class="mt-2 small">
                                            <i class="fas fa-chart-line me-1"></i>
                                            <span>Current term</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-graduation-cap fa-2x text-white-50"></i>
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
                                            Pending Assignments</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['pending_assignments'] ?? 0 }}</div>
                                        <div class="mt-2 small">
                                            <i class="fas fa-clock me-1"></i>
                                            <span>Requires attention</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-tasks fa-2x text-white-50"></i>
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
                                            Fee Balance</div>
                                        <div class="h2 mb-0 fw-bold">₦{{ number_format($stats['fee_balance'] ?? 0, 2) }}</div>
                                        <div class="mt-2 small">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            <span>Outstanding</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-money-bill-wave fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Schedule & Quick Actions -->
                <div class="row">
                    <!-- Today's Schedule -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-calendar-day me-2"></i>Today's Schedule
                                </h6>
                                <span class="badge bg-primary">{{ now()->format('l, F j, Y') }}</span>
                            </div>
                            <div class="card-body">
                                @if(isset($todaysSchedule) && count($todaysSchedule) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($todaysSchedule as $schedule)
                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
                                            <div class="bg-primary rounded p-2 me-3">
                                                <i class="fas fa-book text-white"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold">{{ $schedule->subject->name }}</div>
                                                <div class="text-muted small">
                                                    {{ $schedule->start_time }} - {{ $schedule->end_time }} • 
                                                    {{ $schedule->teacher->user->name ?? 'Teacher' }}
                                                </div>
                                            </div>
                                            <span class="badge bg-secondary">{{ $schedule->room_number ?? 'Class' }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No classes scheduled for today</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Recent Assignments -->
                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-tasks me-2"></i>Recent Assignments
                                </h6>
                            </div>
                            <div class="card-body">
                                @if(isset($recentAssignments) && count($recentAssignments) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($recentAssignments as $assignment)
                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
                                            <div class="bg-{{ $assignment->is_submitted ? 'success' : 'warning' }} rounded p-2 me-3">
                                                <i class="fas fa-{{ $assignment->is_submitted ? 'check' : 'clock' }} text-white"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold">{{ $assignment->title }}</div>
                                                <div class="text-muted small">
                                                    {{ $assignment->subject->name }} • Due: {{ $assignment->due_date->format('M j, Y') }}
                                                </div>
                                            </div>
                                            <span class="badge bg-{{ $assignment->is_submitted ? 'success' : 'warning' }}">
                                                {{ $assignment->is_submitted ? 'Submitted' : 'Pending' }}
                                            </span>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                        <p class="text-muted">No pending assignments</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions & Upcoming Events -->
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
                                        <a href="{{ route('student.assignments') }}"
                                            class="btn btn-outline-primary w-100 h-100 p-3 d-flex flex-column align-items-center">
                                            <i class="fas fa-tasks fa-2x mb-2"></i>
                                            <span>Assignments</span>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('student.results') }}"
                                            class="btn btn-outline-success w-100 h-100 p-3 d-flex flex-column align-items-center">
                                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                            <span>Results</span>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('student.attendance') }}"
                                            class="btn btn-outline-info w-100 h-100 p-3 d-flex flex-column align-items-center">
                                            <i class="fas fa-calendar-check fa-2x mb-2"></i>
                                            <span>Attendance</span>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('student.fees') }}"
                                            class="btn btn-outline-warning w-100 h-100 p-3 d-flex flex-column align-items-center">
                                            <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                            <span>Pay Fees</span>
                                        </a>
                                    </div>
                                    <!-- <div class="col-6">
                                        <a href="{{ route('student.books') }}"
                                            class="btn btn-outline-secondary w-100 h-100 p-3 d-flex flex-column align-items-center">
                                            <i class="fas fa-book-open fa-2x mb-2"></i>
                                            <span>Study Materials</span>
                                        </a>
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming Events -->
                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-calendar-alt me-2"></i>Upcoming Events
                                </h6>
                            </div>
                            <div class="card-body">
                                @if(isset($upcomingEvents) && count($upcomingEvents) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($upcomingEvents as $event)
                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
                                            <div class="bg-info rounded p-2 me-3">
                                                <i class="fas fa-{{ $event->type === 'exam' ? 'file-alt' : 'calendar' }} text-white"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="small fw-bold">{{ $event->title }}</div>
                                                <div class="text-muted small">{{ $event->date->format('M j, Y') }}</div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="fas fa-calendar-plus fa-2x text-muted mb-2"></i>
                                        <p class="text-muted small">No upcoming events</p>
                                    </div>
                                @endif
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