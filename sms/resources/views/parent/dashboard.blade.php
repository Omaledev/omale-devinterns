@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('parent.partials.sidebar')

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <div>
                      <h1 class="h2">Parent Dashboard at <span class="text-primary fw-bold">{{ auth()->user()->school->name }}</span></h1>
                        <p class="text-muted mb-0">Monitoring {{ $stats['children_count'] ?? 0 }} children</p>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-info">
                                Reports
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info">
                                Print
                            </button>
                        </div>
                    </div>
                </div>

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
                                @if($activeSession && $activeTerm)
                                    <a href="{{ route('parent.reports.download', ['student' => $child->user->id, 'session_id' => $activeSession->id, 'term_id' => $activeTerm->id]) }}" 
                                    class="btn btn-sm btn-outline-danger ms-1">
                                        Report
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

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
                                            <span>All children</span>
                                        </div>
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
                                            <span>Overall performance</span>
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
                                            Pending Assignments</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['pending_assignments'] ?? 0 }}</div>
                                        <div class="mt-2 small">
                                            <span>Across all children</span>
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
                                            Total Fee Balance</div>
                                        <div class="h2 mb-0 fw-bold">${{ number_format($stats['total_fee_balance'] ?? 0, 2) }}</div>
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
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    Recent Activity
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    @foreach($recentActivities as $activity)
                                    <div class="list-group-item d-flex align-items-center px-0 border-0">
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

                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    Quick Actions
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('parent.messages') }}" class="btn btn-outline-primary btn-lg">
                                        Message Teachers
                                    </a>
                                    <a href="{{ route('parent.fees') }}" class="btn btn-outline-success btn-lg">
                                        Pay Fees
                                    </a>
                                    <a href="{{ route('parent.meetings') }}" class="btn btn-outline-info btn-lg">
                                        Schedule Meeting
                                    </a>
                                    <a href="{{ route('parent.announcements') }}" class="btn btn-outline-warning btn-lg">
                                        View Announcements
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