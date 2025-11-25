@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Parent Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        @if(auth()->user()->parentProfile && auth()->user()->parentProfile->photo)
                            <img src="{{ asset('storage/' . auth()->user()->parentProfile->photo) }}" 
                                 class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                style="width: 60px; height: 60px;">
                                <span class="text-info fw-bold fs-4">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <h6 class="text-white mb-1">{{ auth()->user()->name }}</h6>
                        <small class="text-white-50">Parent • {{ $stats['children_count'] ?? 0 }} Children</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="{{ route('parent.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('parent.children') }}">
                                <i class="fas fa-child me-2"></i>
                                My Children
                                <span class="badge bg-primary float-end">{{ $stats['children_count'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('parent.attendance') }}">
                                <i class="fas fa-calendar-check me-2"></i>
                                Attendance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('parent.results') }}">
                                <i class="fas fa-chart-bar me-2"></i>
                                Results
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('parent.fees') }}">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                Fee Payments
                                @if(($stats['total_fee_balance'] ?? 0) > 0)
                                <span class="badge bg-danger float-end">Due</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('parent.timetable') }}">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Timetable
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('parent.teachers') }}">
                                <i class="fas fa-chalkboard-teacher me-2"></i>
                                Teachers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('parent.messages') }}">
                                <i class="fas fa-comments me-2"></i>
                                Messages
                                <span class="badge bg-primary float-end">{{ $stats['unread_messages'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('parent.announcements') }}">
                                <i class="fas fa-bullhorn me-2"></i>
                                Announcements
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('parent.meetings') }}">
                                <i class="fas fa-handshake me-2"></i>
                                Parent-Teacher Meetings
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
                      <h1 class="h2"> <i class="fas fa-chalkboard-teacher text-primary me-2"></i>Parent Dashboard at <span class="text-primary fw-bold">{{ auth()->user()->school->name }}</span></h1>
                        <p class="text-muted mb-0">Monitoring {{ $stats['children_count'] ?? 0 }} children</p>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-download me-1"></i>Reports
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-print me-1"></i>Print
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Children Overview -->
                <div class="row mb-4">
                    @foreach($children as $child)
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card shadow border-0 h-100">
                            <div class="card-body text-center">
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 80px; height: 80px;">
                                    <span class="text-white fw-bold fs-3">{{ substr($child->user->name, 0, 1) }}</span>
                                </div>
                                <h5 class="fw-bold">{{ $child->user->name }}</h5>
                                <p class="text-muted mb-2">
                                    {{ $child->classLevel->name ?? 'N/A' }} • {{ $child->section->name ?? 'N/A' }}
                                </p>
                                <div class="row text-center mt-3">
                                    <div class="col-4">
                                        <div class="fw-bold text-primary">{{ $child->attendance_rate ?? 0 }}%</div>
                                        <small class="text-muted">Attendance</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="fw-bold text-success">{{ $child->average_grade ?? 'N/A' }}</div>
                                        <small class="text-muted">Grade</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="fw-bold text-{{ $child->fee_balance > 0 ? 'danger' : 'success' }}">
                                            ${{ number_format($child->fee_balance ?? 0, 2) }}
                                        </div>
                                        <small class="text-muted">Balance</small>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('parent.child.details', $child->id) }}" class="btn btn-sm btn-outline-primary">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card bg-success text-white shadow h-100 border-0">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">
                                            Avg Attendance</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['average_attendance'] ?? 0 }}%</div>
                                        <div class="mt-2 small">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            <span>All children</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-check fa-2x text-white-50"></i>
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
                                            Avg Grade</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['average_grade'] ?? 'N/A' }}</div>
                                        <div class="mt-2 small">
                                            <i class="fas fa-graduation-cap me-1"></i>
                                            <span>Overall performance</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-chart-line fa-2x text-white-50"></i>
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
                                            <i class="fas fa-tasks me-1"></i>
                                            <span>Across all children</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-white-50"></i>
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
                                            Total Fee Balance</div>
                                        <div class="h2 mb-0 fw-bold">${{ number_format($stats['total_fee_balance'] ?? 0, 2) }}</div>
                                        <div class="mt-2 small">
                                            <i class="fas fa-money-bill-wave me-1"></i>
                                            <span>Outstanding</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-credit-card fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity & Quick Actions -->
                <div class="row">
                    <!-- Recent Activity -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-history me-2"></i>Recent Activity
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    @foreach($recentActivities as $activity)
                                    <div class="list-group-item d-flex align-items-center px-0 border-0">
                                        <div class="bg-{{ $activity['color'] }} rounded p-2 me-3">
                                            <i class="fas fa-{{ $activity['icon'] }} text-white"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="small fw-bold">{{ $activity['title'] }}</div>
                                            <div class="text-muted small">{{ $activity['child_name'] }} • {{ $activity['time'] }}</div>
                                        </div>
                                        <span class="badge bg-{{ $activity['badge_color'] }}">{{ $activity['badge_text'] }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-bolt me-2"></i>Quick Actions
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('parent.messages') }}" class="btn btn-outline-primary btn-lg">
                                        <i class="fas fa-comments me-2"></i>Message Teachers
                                    </a>
                                    <a href="{{ route('parent.fees') }}" class="btn btn-outline-success btn-lg">
                                        <i class="fas fa-money-bill-wave me-2"></i>Pay Fees
                                    </a>
                                    <a href="{{ route('parent.meetings') }}" class="btn btn-outline-info btn-lg">
                                        <i class="fas fa-handshake me-2"></i>Schedule Meeting
                                    </a>
                                    <a href="{{ route('parent.announcements') }}" class="btn btn-outline-warning btn-lg">
                                        <i class="fas fa-bullhorn me-2"></i>View Announcements
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection 