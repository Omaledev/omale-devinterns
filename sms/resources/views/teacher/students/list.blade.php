
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('teacher.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.students.index') }}">Student Directory</a></li>
                            <li class="breadcrumb-item active">List</li>
                        </ol>
                    </nav>
                    <h4>
                        {{ $selectedClass->name }}
                        @if($selectedSection)
                            <span class="text-muted">- Section {{ $selectedSection->name }}</span>
                        @endif
                        
                        {{-- NEW: Total Count Display --}}
                        <span class="badge bg-primary ms-2 fs-6 align-middle">
                            {{ $students->total() }} Students
                        </span>
                    </h4>
                </div>
                <a href="{{ route('teacher.students.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Student Name</th>
                                    <th>Admission No.</th>
                                    <th>Section</th>
                                    <th>Contact</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 35px; height: 35px;">
                                                    <span class="fw-bold text-primary">{{ substr($student->user->name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $student->user->name }}</div>
                                                    <small class="text-muted">{{ $student->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $student->student_id ?? 'N/A' }}</td>
                                        <td>
                                            @if($student->section)
                                                <span class="badge bg-info">{{ $student->section->name }}</span>
                                            @else
                                                <span class="text-muted">--</span>
                                            @endif
                                        </td>
                                        <td>{{ $student->user->phone ?? 'N/A' }}</td>
                                        <td>
                                            {{-- NEW: Updated Performance Route --}}
                                            <a href="{{ route('teacher.students.performance', $student->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-chart-line"></i> Performance
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            No students found in this class/section.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($students->hasPages())
                    <div class="card-footer">
                        {{ $students->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </main>
    </div>
</div>
@endsection