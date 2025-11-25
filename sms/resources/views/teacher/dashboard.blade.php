@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Teacher Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        @if(auth()->user()->teacherProfile && auth()->user()->teacherProfile->photo)
                            <img src="{{ asset('storage/' . auth()->user()->teacherProfile->photo) }}" 
                                 class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                style="width: 60px; height: 60px;">
                                <span class="text-success fw-bold fs-4">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <h6 class="text-white mb-1">{{ auth()->user()->name }}</h6>
                        <small class="text-white-50">Teacher • {{ auth()->user()->teacherProfile->employee_id ?? 'N/A' }}</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="{{ route('teacher.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('teacher.classes') }}">
                                <i class="fas fa-door-open me-2"></i>
                                My Classes
                                <span class="badge bg-primary float-end">{{ $stats['total_classes'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                <i class="fas fa-calendar-check me-2"></i>
                                Attendance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                <i class="fas fa-tasks me-2"></i>
                                Assessments
                                <span class="badge bg-warning float-end">{{ $stats['pending_assessments'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                <i class="fas fa-chart-bar me-2"></i>
                                Gradebook
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Timetable
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                <i class="fas fa-user-graduate me-2"></i>
                                Students
                                <span class="badge bg-info float-end">{{ $stats['total_students'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                <i class="fas fa-comments me-2"></i>
                                Messages
                                <span class="badge bg-primary float-end">{{ $stats['unread_messages'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                <i class="fas fa-bullhorn me-2"></i>
                                Announcements
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                <i class="fas fa-chart-pie me-2"></i>
                                Reports
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
                        <h1 class="h2"> <i class="fas fa-chalkboard-teacher text-primary me-2"></i>Teacher Dashboard at <span class="text-primary fw-bold">{{ auth()->user()->school->name }}</span></h1>
                        <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}</p>
                        <small class="text-muted">
                            Subjects: {{ implode(', ', $stats['subjects'] ?? []) }}
                        </small>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-download me-1"></i>Reports
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-print me-1"></i>Print
                            </button>
                        </div>
                        <button type="button" class="btn btn-sm btn-success">
                            <i class="fas fa-plus me-1"></i>New Assessment
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
                                            <i class="fas fa-user-graduate me-1"></i>
                                            <span>Across all classes</span>
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
                                            Pending Assessments</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['pending_assessments'] ?? 0 }}</div>
                                        <div class="mt-2 small">
                                            <i class="fas fa-tasks me-1"></i>
                                            <span>To be graded</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-check fa-2x text-white-50"></i>
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
                                            Today's Classes</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['todays_classes'] ?? 0 }}</div>
                                        <div class="mt-2 small">
                                            <i class="fas fa-calendar-day me-1"></i>
                                            <span>Scheduled</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-door-open fa-2x text-white-50"></i>
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
                                            Unread Messages</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['unread_messages'] ?? 0 }}</div>
                                        <div class="mt-2 small">
                                            <i class="fas fa-comments me-1"></i>
                                            <span>From parents</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-envelope fa-2x text-white-50"></i>
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
                                <span class="badge bg-success">{{ now()->format('l, F j, Y') }}</span>
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
                                                    {{ $schedule->classLevel->name }} • {{ $schedule->section->name ?? 'All' }} • 
                                                    {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-secondary">{{ $schedule->room_number ?? 'Class' }}</span>
                                                <div class="mt-1">
                                                    <a href="{{ route('teacher.attendance.take', $schedule->id) }}" class="btn btn-sm btn-outline-primary">
                                                        Take Attendance
                                                    </a>
                                                </div>
                                            </div>
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

                        <!-- Pending Assessments -->
                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-tasks me-2"></i>Pending Assessments
                                </h6>
                            </div>
                            <div class="card-body">
                                @if(isset($pendingAssessments) && count($pendingAssessments) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($pendingAssessments as $assessment)
                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
                                            <div class="bg-warning rounded p-2 me-3">
                                                <i class="fas fa-clipboard-list text-white"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold">{{ $assessment->title }}</div>
                                                <div class="text-muted small">
                                                    {{ $assessment->subject->name }} • {{ $assessment->classLevel->name }} • 
                                                    Due: {{ $assessment->due_date->format('M j, Y') }}
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-info">{{ $assessment->submissions_count }}/{{ $assessment->total_students }}</span>
                                                <div class="mt-1">
                                                    <a href="{{ route('teacher.assessments.grade', $assessment->id) }}" class="btn btn-sm btn-outline-warning">
                                                        Grade Now
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                        <p class="text-muted">No pending assessments</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions & Recent Messages -->
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
                                        <a href=""
                                            class="btn btn-outline-primary w-100 h-100 p-3 d-flex flex-column align-items-center">
                                            <i class="fas fa-calendar-check fa-2x mb-2"></i>
                                            <span>Attendance</span>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href=""
                                            <div class="col-6">
                                        <a href=""
                                            class="btn btn-outline-success w-100 h-100 p-3 d-flex flex-column align-items-center">
                                            <i class="fas fa-tasks fa-2x mb-2"></i>
                                            <span>Assessment</span>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href=""
                                            class="btn btn-outline-info w-100 h-100 p-3 d-flex flex-column align-items-center">
                                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                            <span>Grades</span>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href=""
                                            class="btn btn-outline-warning w-100 h-100 p-3 d-flex flex-column align-items-center">
                                            <i class="fas fa-comments fa-2x mb-2"></i>
                                            <span>Message</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Messages -->
                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-comments me-2"></i>Recent Messages
                                </h6>
                            </div>
                            <div class="card-body">
                                @if(isset($recentMessages) && count($recentMessages) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($recentMessages as $message)
                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                style="width: 40px; height: 40px;">
                                                <span class="text-white fw-bold small">{{ substr($message->sender->name, 0, 1) }}</span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="small fw-bold">{{ $message->sender->name }}</div>
                                                <div class="text-muted small text-truncate">{{ Str::limit($message->content, 30) }}</div>
                                            </div>
                                            <span class="badge bg-{{ $message->read_at ? 'secondary' : 'primary' }} small">
                                                {{ $message->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="{{ route('teacher.messages') }}" class="btn btn-sm btn-outline-primary">
                                            View All Messages
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="fas fa-comments fa-2x text-muted mb-2"></i>
                                        <p class="text-muted small">No recent messages</p>
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