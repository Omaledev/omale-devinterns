@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
            <div class="position-sticky pt-3">
                <div class="text-center mb-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                        style="width: 60px; height: 60px;">
                        <span class="text-white fw-bold fs-4">A</span>
                    </div>
                    <h6 class="text-white mb-1">{{ auth()->user()->school->name }}</h6>
                    <small class="text-white-50">School Admin</small>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.teachers.index') }}">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Teachers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white" href="{{ route('schooladmin.teacher-assignments.index') }}">
                            <i class="fas fa-link me-2"></i>Teacher Assignments
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">Assignment Details</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.teacher-assignments.index') }}">Teacher Assignments</a></li>
                            <li class="breadcrumb-item active">Details</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('schooladmin.teacher-assignments.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                    <a href="{{ route('schooladmin.teacher-assignments.edit', $teacherAssignment) }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <form action="{{ route('schooladmin.teacher-assignments.destroy', $teacherAssignment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this assignment?')">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-info-circle me-2"></i>Assignment Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Teacher Information</h6>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Teacher Name</label>
                                        <p class="form-control-plaintext">{{ $teacherAssignment->teacher->name }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Email</label>
                                        <p class="form-control-plaintext">{{ $teacherAssignment->teacher->email }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Employee ID</label>
                                        <p class="form-control-plaintext">{{ $teacherAssignment->teacher->employee_id ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Assignment Details</h6>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Subject</label>
                                        <p class="form-control-plaintext">{{ $teacherAssignment->subject->name }} ({{ $teacherAssignment->subject->code }})</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Class Level</label>
                                        <p class="form-control-plaintext">{{ $teacherAssignment->classLevel->name }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Section</label>
                                        <p class="form-control-plaintext">{{ $teacherAssignment->section->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Academic Year</label>
                                        <p class="form-control-plaintext">{{ $teacherAssignment->academic_year }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">Timestamps</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Created At</label>
                                                <p class="form-control-plaintext">{{ $teacherAssignment->created_at->format('M d, Y h:i A') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Updated At</label>
                                                <p class="form-control-plaintext">{{ $teacherAssignment->updated_at->format('M d, Y h:i A') }}</p>
                                            </div>
                                        </div>
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