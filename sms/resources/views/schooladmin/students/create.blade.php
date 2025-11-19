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
                        <a class="nav-link active text-white" href="{{ route('schooladmin.students.index') }}">
                            <i class="fas fa-user-graduate me-2"></i>
                            Students
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            Teachers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-users me-2"></i>
                            Parents
                        </a>
                    </li>
                    <!-- ... rest of your sidebar items ... -->
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Header -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">Add New Student</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.students.index') }}">Students</a></li>
                            <li class="breadcrumb-item active">Add Student</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('schooladmin.students.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>Back to Students
                    </a>
                </div>
            </div>

            <!-- Student Form -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-user-plus me-2"></i>Student Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('schooladmin.students.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <!-- Personal Information -->
                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Personal Information</h6>

                                        <div class="mb-3">
                                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                                   id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                                   id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                   id="email" name="email" value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                   id="phone" name="phone" value="{{ old('phone') }}">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                                   id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                            @error('date_of_birth')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Academic Information -->
                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Academic Information</h6>

                                        <div class="mb-3">
                                            <label for="admission_number" class="form-label">Admission Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('admission_number') is-invalid @enderror"
                                                   id="admission_number" name="admission_number" value="{{ old('admission_number') }}" required>
                                            @error('admission_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="class" class="form-label">Class/Grade</label>
                                            <select class="form-select @error('class') is-invalid @enderror" id="class" name="class">
                                                <option value="">Select Class</option>
                                                <option value="Grade 1" {{ old('class') == 'Grade 1' ? 'selected' : '' }}>Grade 1</option>
                                                <option value="Grade 2" {{ old('class') == 'Grade 2' ? 'selected' : '' }}>Grade 2</option>
                                                <option value="Grade 3" {{ old('class') == 'Grade 3' ? 'selected' : '' }}>Grade 3</option>
                                                <option value="Grade 4" {{ old('class') == 'Grade 4' ? 'selected' : '' }}>Grade 4</option>
                                                <option value="Grade 5" {{ old('class') == 'Grade 5' ? 'selected' : '' }}>Grade 5</option>
                                                <option value="Grade 6" {{ old('class') == 'Grade 6' ? 'selected' : '' }}>Grade 6</option>
                                            </select>
                                            @error('class')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="section" class="form-label">Section</label>
                                            <input type="text" class="form-control @error('section') is-invalid @enderror"
                                                   id="section" name="section" value="{{ old('section') }}">
                                            @error('section')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="roll_number" class="form-label">Roll Number</label>
                                            <input type="text" class="form-control @error('roll_number') is-invalid @enderror"
                                                   id="roll_number" name="roll_number" value="{{ old('roll_number') }}">
                                            @error('roll_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="admission_date" class="form-label">Admission Date</label>
                                            <input type="date" class="form-control @error('admission_date') is-invalid @enderror"
                                                   id="admission_date" name="admission_date" value="{{ old('admission_date', date('Y-m-d')) }}">
                                            @error('admission_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Address Information -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h6 class="text-primary mb-3">Address Information</h6>

                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror"
                                                      id="address" name="address" rows="3">{{ old('address') }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="city" class="form-label">City</label>
                                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                                           id="city" name="city" value="{{ old('city') }}">
                                                    @error('city')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="state" class="form-label">State</label>
                                                    <input type="text" class="form-control @error('state') is-invalid @enderror"
                                                           id="state" name="state" value="{{ old('state') }}">
                                                    @error('state')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="zip_code" class="form-label">Zip Code</label>
                                                    <input type="text" class="form-control @error('zip_code') is-invalid @enderror"
                                                           id="zip_code" name="zip_code" value="{{ old('zip_code') }}">
                                                    @error('zip_code')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('schooladmin.students.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-1"></i>Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i>Add Student
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
