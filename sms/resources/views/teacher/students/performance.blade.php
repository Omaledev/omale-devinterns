@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('teacher.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.students.index') }}">Directory</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('teacher.students.list', ['class_level_id' => $student->class_level_id]) }}">List</a></li>
                            <li class="breadcrumb-item active">Performance</li>
                        </ol>
                    </nav>
                    <h3 class="fw-bold">{{ $student->user->name }}</h3>
                    <span class="text-muted">
                        {{ $student->student_id }} • {{ $student->classLevel->name }} 
                        @if($student->section) - {{ $student->section->name }} @endif
                    </span>
                </div>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>

            <div class="row g-4">
                {{-- Left Column: Profile & Stats --}}
                <div class="col-md-4">
                    {{-- Student Card --}}
                    <div class="card shadow-sm mb-4 text-center p-4">
                        <div class="mb-3 mx-auto bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <span class="display-4 fw-bold text-primary">{{ substr($student->user->name, 0, 1) }}</span>
                        </div>
                        <h5 class="fw-bold mb-1">{{ $student->user->name }}</h5>
                        <p class="text-muted mb-3">{{ $student->user->email }}</p>
                        
                        <div class="d-grid">
                            <button class="btn btn-primary btn-sm">
                                <i class="fas fa-envelope me-1"></i> Send Message
                            </button>
                        </div>
                    </div>

                    {{-- Attendance Summary Card --}}
                    @php
                        $totalAttendance = $student->attendances->count();
                        $presentCount = $student->attendances->where('status', 'PRESENT')->count();
                        $absentCount = $student->attendances->where('status', 'ABSENT')->count();
                        $lateCount = $student->attendances->where('status', 'LATE')->count();
                        
                        // Avoiding division by zero
                        $attendancePercentage = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100) : 0;
                        
                        // Color logic
                        $attendanceColor = 'success';
                        if($attendancePercentage < 75) $attendanceColor = 'warning';
                        if($attendancePercentage < 50) $attendanceColor = 'danger';
                    @endphp

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold">Attendance Overview</div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <h2 class="fw-bold text-{{ $attendanceColor }} mb-0">{{ $attendancePercentage }}%</h2>
                                <small class="text-muted">Overall Attendance</small>
                            </div>

                            <div class="progress mb-3" style="height: 10px;">
                                <div class="progress-bar bg-{{ $attendanceColor }}" role="progressbar" style="width: {{ $attendancePercentage }}%"></div>
                            </div>

                            <ul class="list-group list-group-flush small">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Days Present</span>
                                    <span class="fw-bold text-success">{{ $presentCount }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Days Absent</span>
                                    <span class="fw-bold text-danger">{{ $absentCount }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Days Late</span>
                                    <span class="fw-bold text-warning">{{ $lateCount }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Detailed Tabs --}}
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance" type="button" role="tab">Attendance History</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="grades-tab" data-bs-toggle="tab" data-bs-target="#grades" type="button" role="tab">Academic Grades</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                
                                {{-- Attendance History --}}
                                <div class="tab-pane fade show active" id="attendance" role="tabpanel">
                                    <h6 class="fw-bold mb-3">Recent Attendance Records</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Teacher</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($student->attendances()->latest()->take(10)->get() as $record)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}</td>
                                                        <td>
                                                            @if($record->status == 'PRESENT')
                                                                <span class="badge bg-success">Present</span>
                                                            @elseif($record->status == 'ABSENT')
                                                                <span class="badge bg-danger">Absent</span>
                                                            @else
                                                                <span class="badge bg-warning text-dark">Late</span>
                                                            @endif
                                                        </td>
                                                        <td class="small text-muted">
                                                            {{ $record->teacher->name ?? 'N/A' }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center py-3 text-muted">No records found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Grades (Placeholder) --}}
                                <div class="tab-pane fade" id="grades" role="tabpanel">
                                    <div class="text-center py-5">
                                        <i class="fas fa-chart-bar fa-3x text-muted mb-3 opacity-50"></i>
                                        <h5>Grades Module</h5>
                                        <p class="text-muted">
                                            Detailed grade analysis will appear here once the grading module is fully connected.
                                        </p>
                                        <a href="{{ route('teacher.grades.index') }}" class="btn btn-outline-primary btn-sm">
                                            Go to Grade Entry
                                        </a>
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