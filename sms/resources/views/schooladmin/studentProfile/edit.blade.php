@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        @include('schooladmin.partials.sidebar')

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Header -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">Edit Student</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.students.index') }}">Students</a></li>
                            <li class="breadcrumb-item active">Edit Student</li>
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
                                <i class="fas fa-edit me-2"></i>Edit Student Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('schooladmin.students.update', $student) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Personal Information -->
                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Personal Information</h6>

                                        <div class="mb-3">
                                            <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                                id="full_name" name="full_name" value="{{ old('full_name', $student->name) }}"
                                                placeholder="Enter student's full name" required>
                                            @error('full_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                   id="email" name="email" value="{{ old('email', $student->email) }}"
                                                   placeholder="Enter email address" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                   id="phone" name="phone" value="{{ old('phone', $student->phone) }}"
                                                   placeholder="Enter phone number">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                                   id="date_of_birth" name="date_of_birth" 
                                                   value="{{ old('date_of_birth', $student->studentProfile->date_of_birth ?? '') }}">
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
                                                   id="admission_number" name="admission_number" 
                                                   value="{{ old('admission_number', $student->admission_number) }}"
                                                   placeholder="e.g., ADM2024001" required>
                                            @error('admission_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="class" class="form-label">Class/Grade</label>
                                            <select class="form-select @error('class') is-invalid @enderror" id="class" name="class">
                                                <option value="">Select Class</option>
                                                <option value="Grade 1" {{ old('class', $student->class) == 'Grade 1' ? 'selected' : '' }}>Grade 1</option>
                                                <option value="Grade 2" {{ old('class', $student->class) == 'Grade 2' ? 'selected' : '' }}>Grade 2</option>
                                                <option value="Grade 3" {{ old('class', $student->class) == 'Grade 3' ? 'selected' : '' }}>Grade 3</option>
                                                <option value="Grade 4" {{ old('class', $student->class) == 'Grade 4' ? 'selected' : '' }}>Grade 4</option>
                                                <option value="Grade 5" {{ old('class', $student->class) == 'Grade 5' ? 'selected' : '' }}>Grade 5</option>
                                                <option value="Grade 6" {{ old('class', $student->class) == 'Grade 6' ? 'selected' : '' }}>Grade 6</option>
                                            </select>
                                            @error('class')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="section" class="form-label">Section</label>
                                            <input type="text" class="form-control @error('section') is-invalid @enderror"
                                                   id="section" name="section" value="{{ old('section', $student->section) }}"
                                                   placeholder="Enter section">
                                            @error('section')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="admission_date" class="form-label">Admission Date</label>
                                            <input type="date" class="form-control @error('admission_date') is-invalid @enderror"
                                                   id="admission_date" name="admission_date" 
                                                   value="{{ old('admission_date', $student->studentProfile->admission_date ?? '') }}">
                                            @error('admission_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved"
                                                       value="1" {{ old('is_approved', $student->is_approved) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_approved">
                                                    Active Student
                                                </label>
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
                                                      placeholder="Enter student's address">{{ old('address', $student->studentProfile->address ?? '') }}</textarea>
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
                                            <a href="{{ route('schooladmin.students.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-1"></i>Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i>Update Student
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