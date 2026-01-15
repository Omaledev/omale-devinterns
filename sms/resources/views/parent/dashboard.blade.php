@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('parent.partials.sidebar')

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                
                {{-- HEADER --}}
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <div>
                      <h1 class="h2">Parent Dashboard</h1>
                        <p class="text-muted mb-0">
                            Welcome back, <strong>{{ auth()->user()->name }}</strong>. 
                            Monitoring {{ $stats['children_count'] ?? 0 }} children.
                        </p>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <span class="badge bg-light text-dark border p-2">
                                {{ now()->format('D, M d, Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- GLOBAL STATS --}}
                <div class="row mb-4">
                    {{-- Avg Attendance --}}
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

                    {{-- Avg Grade --}}
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

                    {{-- Pending Tasks --}}
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card bg-warning text-white shadow h-100 border-0">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">
                                            Pending Tasks</div>
                                        <div class="h2 mb-0 fw-bold">{{ $stats['pending_assignments'] ?? 0 }}</div>
                                        <div class="mt-2 small">
                                            <span>Assignments & Homework</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Total Balance --}}
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card bg-danger text-white shadow h-100 border-0">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-white-50 text-uppercase mb-1">
                                            Total Balance</div>
                                        <div class="h2 mb-0 fw-bold">₦{{ number_format($stats['total_fee_balance'] ?? 0, 2) }}</div>
                                        <div class="mt-2 small">
                                            <span>Outstanding Fees</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CHILDREN LIST SECTION --}}
                <h5 class="fw-bold text-secondary mb-3">My Children</h5>
                <div class="row mb-4">
                    @foreach($children as $child)
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center">
                                {{-- Avatar --}}
                                <div class="bg-info rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 80px; height: 80px;">
                                    <span class="text-white fw-bold fs-3">{{ substr($child->name, 0, 1) }}</span>
                                </div>
                                
                                {{-- Name --}}
                                <h5 class="fw-bold text-dark">{{ $child->name }}</h5>
                                
                                {{-- Class Info --}}
                                <p class="text-muted mb-2">
                                    {{ $child->studentProfile->classLevel->name ?? 'N/A' }} • 
                                    {{ $child->studentProfile->section->name ?? 'N/A' }}
                                </p>
                                
                                <hr class="my-3">

                                {{-- Individual Stats --}}
                                <div class="row text-center">
                                    <div class="col-4 border-end">
                                        <div class="fw-bold text-primary">{{ $child->attendance_rate }}%</div>
                                        <small class="text-muted" style="font-size: 10px;">ATTENDANCE</small>
                                    </div>
                                    <div class="col-4 border-end">
                                        <div class="fw-bold text-success">{{ $child->average_grade }}</div>
                                        <small class="text-muted" style="font-size: 10px;">AVG GRADE</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="fw-bold text-{{ $child->fee_balance > 0 ? 'danger' : 'success' }}">
                                            ₦{{ number_format($child->fee_balance, 0) }}
                                        </div>
                                        <small class="text-muted" style="font-size: 10px;">BALANCE</small>
                                    </div>
                                </div>
                                
                                {{-- Actions --}}
                                <div class="mt-4 d-grid gap-2">
                                    <a href="{{ route('parent.children') }}" class="btn btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                    
                                    @if(isset($activeSession) && isset($activeTerm))
                                        <a href="{{ route('parent.report.download', ['term_id' => $activeTerm->id, 'session_id' => $activeSession->id, 'student_id' => $child->id]) }}" 
                                        class="btn btn-outline-danger btn-sm" target="_blank">
                                            <i class="fas fa-file-pdf me-1"></i> Report Card
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Quick Actions --}}
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-history me-1"></i> Recent Activity
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    @forelse($recentActivities as $activity)
                                    <div class="list-group-item d-flex align-items-center px-4 py-3 border-bottom">
                                        <div class="flex-grow-1">
                                            <div class="small fw-bold">{{ $activity['title'] }}</div>
                                            <div class="text-muted small">{{ $activity['child_name'] }} • {{ $activity['time'] }}</div>
                                        </div>
                                        <span class="badge bg-{{ $activity['badge_color'] }}">{{ $activity['badge_text'] }}</span>
                                    </div>
                                    @empty
                                    <div class="text-center py-4 text-muted">No recent activity found.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-bolt me-1"></i> Quick Actions
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('messages.index') }}" class="btn btn-outline-primary btn-lg text-start">
                                        <i class="fas fa-envelope me-2"></i> Message Teachers
                                    </a>
                                    <a href="{{ route('parent.fees') }}" class="btn btn-outline-success btn-lg text-start">
                                        <i class="fas fa-credit-card me-2"></i> Pay Fees
                                    </a>
                                    <a href="{{ route('announcements.index') }}" class="btn btn-outline-warning btn-lg text-start">
                                        <i class="fas fa-bullhorn me-2"></i> Announcements
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