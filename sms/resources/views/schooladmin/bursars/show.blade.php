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
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.teachers.index') }}">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            Teachers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.parents.index') }}">
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
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.sections.index') }}">
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
                    <li class="nav-item">
                        <a class="nav-link active text-white" href="{{ route('schooladmin.bursars.index') }}">
                            <i class="fas fa-money-check me-2"></i>
                            Bursars
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
                    <h1 class="h2">Bursar Details</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.bursars.index') }}">Bursars</a></li>
                            <li class="breadcrumb-item active">{{ $bursar->name }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group">
                        <a href="{{ route('schooladmin.bursars.edit', $bursar) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('schooladmin.bursars.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bursar Details -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-money-check me-2"></i>Bursar Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center mb-4">
                                    <div class="avatar-lg bg-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                        <span class="text-white fw-bold display-6">{{ substr($bursar->name, 0, 1) }}</span>
                                    </div>
                                    <h4 class="fw-bold">{{ $bursar->name }}</h4>
                                    <span class="badge bg-{{ $bursar->is_approved ? 'success' : 'warning' }} fs-6">
                                        {{ $bursar->is_approved ? 'Active' : 'Pending' }}
                                    </span>
                                </div>

                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold text-muted">Employee ID</label>
                                                <p class="fs-5">{{ $bursar->employee_id }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold text-muted">Email Address</label>
                                                <p class="fs-5">{{ $bursar->email }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold text-muted">Phone Number</label>
                                                <p class="fs-5">{{ $bursar->phone ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold text-muted">School</label>
                                                <p class="fs-5">{{ $bursar->school->name }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Address</label>
                                        <p class="fs-5">{{ $bursar->address ?? 'N/A' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Registration Date</label>
                                        <p class="fs-5">{{ $bursar->created_at->format('M d, Y') }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Last Updated</label>
                                        <p class="fs-5">{{ $bursar->updated_at->format('M d, Y') }}</p>
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