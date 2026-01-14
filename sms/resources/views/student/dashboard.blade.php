@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('student.partials.sidebar')

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                {{-- Header --}}
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
                             <a href="{{ route('student.results') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-chart-line me-1"></i> Report
                            </a>
                            <a href="{{ route('student.timetable') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-calendar-alt me-1"></i> Schedule
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Stats Cards --}}
                <div class="row mb-4">
                    {{-- Attendance --}}
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card bg-success text-white shadow h-100 border-0">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">
                                            Attendance Rate</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['attendance_rate'] ?? 0 }}%</div>
                                        <div class="mt-2 small">
                                            <span>This session</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Grades --}}
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card bg-info text-white shadow h-100 border-0">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">
                                            Average Grade</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['average_grade'] ?? 'N/A' }}%</div>
                                        <div class="mt-2 small">
                                            <span>Across subjects</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Assignments --}}
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card bg-warning text-white shadow h-100 border-0">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">
                                            Pending Assignments</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['pending_assignments'] ?? 0 }}</div>
                                        <div class="mt-2 small">
                                            <span>Requires attention</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Fees --}}
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card bg-danger text-white shadow h-100 border-0">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">
                                            Fee Balance</div>
                                        <div class="h2 mb-0 fw-bold">₦{{ number_format($stats['fee_balance'] ?? 0, 2) }}</div>
                                        <div class="mt-2 small">
                                            <span>Outstanding</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Left Column --}}
                    <div class="col-xl-8 col-lg-7">
                        
                        {{-- Today's Schedule --}}
                        <div class="card shadow mb-4">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    Today's Schedule
                                </h6>
                                <span class="badge bg-primary">{{ now()->format('l, F j') }}</span>
                            </div>
                            <div class="card-body">
                                @if(isset($todaysSchedule) && count($todaysSchedule) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($todaysSchedule as $schedule)
                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
                                            <div class="me-3 text-center" style="min-width: 80px;">
                                                <div class="fw-bold">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</div>
                                                <div class="small text-muted">{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</div>
                                            </div>
                                            <div class="flex-grow-1 border-start ps-3">
                                                <div class="fw-bold">{{ $schedule->subject->name }}</div>
                                                <div class="text-muted small">
                                                    {{ $schedule->teacher->name ?? 'Teacher' }} 
                                                </div>
                                            </div>
                                            <span class="badge bg-light text-dark border">{{ $schedule->room_number ?? 'Class' }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-coffee fa-2x text-muted mb-2 opacity-50"></i>
                                        <p class="text-muted mb-0">No classes scheduled for today.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Recent Assignments --}}
                        <div class="card shadow">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-tasks me-1"></i> Recent Assignments
                                </h6>
                                <a href="{{ route('student.assignments') }}" class="btn btn-sm btn-link text-decoration-none">View All</a>
                            </div>
                            <div class="card-body">
                                @if(isset($recentAssignments) && count($recentAssignments) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($recentAssignments as $assignment)
                                            <div class="list-group-item d-flex align-items-center px-0 border-0">
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold">{{ $assignment->title }}</div>
                                                    <div class="text-muted small">
                                                        {{ $assignment->subject->name }} • 
                                                        <span class="{{ $assignment->due_date->isPast() && !$assignment->is_submitted ? 'text-danger fw-bold' : '' }}">
                                                            Due: {{ $assignment->due_date->format('M j') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                {{-- Dynamic Badge Logic --}}
                                                @if($assignment->is_submitted)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i> Submitted
                                                    </span>
                                                @else
                                                    @if($assignment->due_date->isPast())
                                                        <span class="badge bg-danger">Overdue</span>
                                                    @else
                                                        <a href="{{ route('student.assignments') }}" class="btn btn-sm btn-outline-warning">
                                                            Pending
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <p class="text-muted">No assignments found.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="col-xl-4 col-lg-5">
                        
                        {{-- Quick Actions --}}
                        <div class="card shadow mb-4">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    Quick Actions
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('student.assignments') }}"
                                        class="btn btn-outline-primary text-center">
                                        Submit Assignment
                                    </a>
                                    <a href="{{ route('student.results') }}"
                                        class="btn btn-outline-success text-center">
                                        View Results
                                    </a>
                                    <a href="{{ route('student.attendance') }}"
                                        class="btn btn-outline-info text-center">
                                        Check Attendance
                                    </a>
                                    <a href="{{ route('student.fees') }}"
                                        class="btn btn-outline-warning text-center">
                                        Pay Fees
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Upcoming Events (Placeholder) --}}
                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    Upcoming Events
                                </h6>
                            </div>
                            <div class="card-body">
                                @if(isset($upcomingEvents) && count($upcomingEvents) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($upcomingEvents as $event)
                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
                                            <div class="flex-grow-1">
                                                <div class="small fw-bold">{{ $event->title }}</div>
                                                <div class="text-muted small">{{ $event->date->format('M j, Y') }}</div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <p class="text-muted small">No upcoming events found.</p>
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
    </style>
@endpush