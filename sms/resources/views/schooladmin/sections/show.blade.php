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
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.class-levels.index') }}">
                            <i class="fas fa-door-open me-2"></i>
                            Classes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white" href="{{ route('schooladmin.sections.index') }}">
                            <i class="fas fa-layer-group me-2"></i>
                            Sections
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.subjects.index') }}">
                            <i class="fas fa-book me-2"></i>
                            Subjects
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
                    <h1 class="h2">Section Details</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.sections.index') }}">Sections</a></li>
                            <li class="breadcrumb-item active">Section Details</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group">
                        <a href="{{ route('schooladmin.sections.edit', $section) }}" class="btn btn-outline-warning">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('schooladmin.sections.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Sections
                        </a>
                    </div>
                </div>
            </div>

            <!-- Section Details -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-layer-group me-2"></i>Section Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">Basic Information</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Section Name:</td>
                                            <td>{{ $section->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Class Level:</td>
                                            <td>{{ $section->classLevel->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Capacity:</td>
                                            <td>
                                                @if($section->capacity)
                                                    @php
                                                        $studentCount = $section->studentProfiles->count();
                                                        $percentage = $studentCount > 0 ? round(($studentCount / $section->capacity) * 100) : 0;
                                                    @endphp
                                                    {{ $studentCount }}/{{ $section->capacity }} ({{ $percentage }}%)
                                                @else
                                                    {{ $section->studentProfiles->count() }} students
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Status:</td>
                                            <td>
                                                <span class="badge bg-{{ $section->is_active ? 'success' : 'secondary' }}">
                                                    {{ $section->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">Students in this Section</h5>
                                    @if($section->studentProfiles->count() > 0)
                                        <div class="list-group">
                                            @foreach($section->studentProfiles as $studentProfile)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $studentProfile->user->name }}</h6>
                                                    <small class="text-muted">Admission: {{ $studentProfile->student_id ?? 'N/A' }}</small>
                                                </div>
                                                <span class="badge bg-primary rounded-pill">
                                                    {{ $studentProfile->classLevel->name ?? 'N/A' }}
                                                </span>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No students assigned to this section yet.
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