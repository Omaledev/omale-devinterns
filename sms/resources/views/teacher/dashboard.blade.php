@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('teacher.partials.sidebar')
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <div>
                        <h1 class="h2">Teacher Dashboard at <span class="text-primary fw-bold">{{ auth()->user()->school->name }}</span></h1>
                        <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}</p>
                        <small class="text-muted">
                            Subjects: {{ implode(', ', $stats['subjects'] ?? []) }}
                        </small>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="{{ route('teacher.reports.index') }}" class="btn btn-sm btn-outline-success">
                                Reports
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-success">
                                Print
                            </button>
                        </div>
                        <button type="button" class="btn btn-sm btn-success">
                            New Assessment
                        </button>
                    </div>
                </div>

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
                                            <span>Across all classes</span>
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
                                            Pending Assessments</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['pending_assessments'] ?? 0 }}</div>
                                        <div class="mt-2 small">
                                            <span>To be graded</span>
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
                                            Today's Classes</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['todays_classes'] ?? 0 }}</div>
                                        <div class="mt-2 small">
                                            <span>Scheduled</span>
                                        </div>
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
                                            <span>From parents</span>
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
                                    Today's Schedule
                                </h6>
                                <span class="badge bg-success">{{ now()->format('l, F j, Y') }}</span>
                            </div>
                            <div class="card-body">
                                @if(isset($todaysSchedule) && count($todaysSchedule) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($todaysSchedule as $schedule)
                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
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
                                        <p class="text-muted">No classes scheduled for today</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    Pending Assessments
                                </h6>
                            </div>
                            <div class="card-body">
                                @if(isset($pendingAssessments) && count($pendingAssessments) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($pendingAssessments as $assessment)
                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
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
                                        <p class="text-muted">No pending assessments</p>
                                    </div>
                                @endif
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
                                    <a href="#" class="btn btn-outline-primary btn-lg">
                                        Mark Attendance
                                    </a>
                                    <a href="#" class="btn btn-outline-success btn-lg">
                                        Create Assessment
                                    </a>
                                    <a href="#" class="btn btn-outline-info btn-lg">
                                        View Grades
                                    </a>
                                    <a href="#" class="btn btn-outline-warning btn-lg">
                                        Messages
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    Recent Messages
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