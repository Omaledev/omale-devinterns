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
                        <a class="nav-link active text-white" href="{{ route('schooladmin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.students.index') }}">
                            <i class="fas fa-user-graduate me-2"></i>
                            Students
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.teachers.index')}}">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            Teachers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.parents.index')}}">
                            <i class="fas fa-users me-2"></i>
                            Parents
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
                    <h1 class="h2">Student Details</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.students.index') }}">Students</a></li>
                            <li class="breadcrumb-item active">Student Details</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group">
                        <a href="{{ route('schooladmin.students.edit', $student) }}" class="btn btn-outline-warning">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('schooladmin.students.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Students
                        </a>
                    </div>
                </div>
            </div>

            <!-- Student Details -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-user-graduate me-2"></i>Student Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Student Profile -->
                                <div class="col-md-4 text-center mb-4">
                                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                         style="width: 100px; height: 100px;">
                                        <span class="text-white fw-bold fs-2">
                                            {{ substr($student->name, 0, 1) }}{{ substr($student->name, -1, 1) }}
                                        </span>
                                    </div>
                                    <h4 class="mb-1">{{ $student->name }}</h4>
                                    <p class="text-muted mb-2">Student</p>
                                    <div class="mb-2">
                                        @if($student->is_approved)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">Member since {{ $student->created_at->format('M d, Y') }}</small>
                                </div>

                                <!-- Personal Information -->
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3">Personal Information</h6>
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td class="fw-bold" style="width: 40%;">Full Name:</td>
                                                    <td>{{ $student->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Email:</td>
                                                    <td>{{ $student->email }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Phone:</td>
                                                    <td>{{ $student->phone ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Date of Birth:</td>
                                                    <td>{{ $student->studentProfile && $student->studentProfile->date_of_birth ? $student->studentProfile->date_of_birth->format('M d, Y') : 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Status:</td>
                                                    <td>
                                                        @if($student->is_approved)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3">Academic Information</h6>
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td class="fw-bold" style="width: 40%;">Admission No:</td>
                                                    <td>{{ $student->admission_number }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Class:</td>
                                                    <td>
                                                        @if($student->class)
                                                            <span class="badge bg-info">{{ $student->class }}</span>
                                                            @if($student->section)
                                                                <small class="text-muted">({{ $student->section }})</small>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">Not assigned</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">School:</td>
                                                    <td>{{ $student->school->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Admission Date:</td>
                                                    <td>{{ $student->studentProfile && $student->studentProfile->admission_date ? $student->studentProfile->admission_date->format('M d, Y') : 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Last Updated:</td>
                                                    <td>{{ $student->updated_at->format('M d, Y') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Address Information -->
                                    @if($student->studentProfile && $student->studentProfile->address)
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h6 class="text-primary mb-3">Address Information</h6>
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <p class="mb-0">{{ $student->studentProfile->address }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
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

    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
    }

    .card {
        border: none;
        border-radius: 0.5rem;
    }

    .table-borderless td {
        padding: 0.5rem 0.25rem;
        border: none;
    }
</style>
@endpush