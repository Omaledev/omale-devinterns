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
                        <a class="nav-link active text-white" href="{{ route('schooladmin.teachers.index')}}">
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
                    <h1 class="h2">Add New Teacher</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.teachers.index') }}">Teachers</a></li>
                            <li class="breadcrumb-item active">Add Teacher</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('schooladmin.teachers.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Teachers
                    </a>
                </div>
            </div>

            <!-- Teacher Form -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-user-plus me-2"></i>Teacher Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('schooladmin.teachers.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <!-- Personal Information -->
                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Personal Information</h6>

                                        <div class="mb-3">
                                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   id="name" name="name" value="{{ old('name') }}" 
                                                   placeholder="Enter teacher's full name" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                   id="email" name="email" value="{{ old('email') }}"
                                                   placeholder="Enter email address" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                   id="phone" name="phone" value="{{ old('phone') }}"
                                                   placeholder="Enter phone number">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Professional Information -->
                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Professional Information</h6>

                                        <div class="mb-3">
                                            <label for="employee_id" class="form-label">Employee ID <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('employee_id') is-invalid @enderror"
                                                   id="employee_id" name="employee_id" value="{{ old('employee_id') }}"
                                                   placeholder="e.g., EMP2024001" required>
                                            @error('employee_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Password Fields Side by Side -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                           id="password" name="password" placeholder="Enter password" required>
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control"
                                                           id="password_confirmation" name="password_confirmation" 
                                                           placeholder="Confirm password" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Address Information -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6 class="text-primary mb-3">Address Information</h6>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror"
                                                      id="address" name="address" rows="3"
                                                      placeholder="Enter teacher's address">{{ old('address') }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('schooladmin.teachers.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-1"></i>Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i>Create Teacher
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
    }

    .breadcrumb {
        margin-bottom: 0;
    }
</style>
@endpush