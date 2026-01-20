@extends('layouts.app')

@php
    // Getting School Name
    $schoolName = $student->school->name ?? 'STD';

    $cleanName = preg_replace('/[^a-zA-Z]/', '', $schoolName);
    $schoolPrefix = strtoupper(substr($cleanName, 0, 3));
@endphp

@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('schooladmin.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
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

            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                Edit Student Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('schooladmin.students.update', $student) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Personal Information</h6>

                                        {{-- Name --}}
                                        <div class="mb-3">
                                            <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                                id="full_name" name="full_name" value="{{ old('full_name', $student->name) }}"
                                                placeholder="Enter student's full name" required>
                                            @error('full_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Email --}}
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                   id="email" name="email" value="{{ old('email', $student->email) }}"
                                                   placeholder="Enter email address" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Phone --}}
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                   id="phone" name="phone" value="{{ old('phone', $student->phone) }}"
                                                   placeholder="Enter phone number">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Date of Birth --}}
                                        <div class="mb-3">
                                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                                   id="date_of_birth" name="date_of_birth" 
                                                   value="{{ old('date_of_birth', $student->studentProfile?->date_of_birth?->format('Y-m-d')) }}">
                                            @error('date_of_birth')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        {{-- Address --}}
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror"
                                                      id="address" name="address" rows="3"
                                                      placeholder="Enter student's address">{{ old('address', $student->studentProfile?->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Academic Information</h6>

                                        {{-- Admission Number (READ ONLY) --}}
                                        <div class="mb-3">
                                            <label for="admission_number" class="form-label">
                                                Admission Number
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-id-card"></i></span>
                                                <input type="text" 
                                                    class="form-control bg-light fw-bold"
                                                    id="admission_number" 
                                                    name="admission_number" 
                                                    value="{{ old('admission_number', $student->admission_number) }}"
                                                    readonly>
                                            </div>
                                            <small class="text-muted">Admission number cannot be changed.</small>
                                        </div>

                                        {{-- Class Selection --}}
                                        <div class="mb-3">
                                            <label for="class_level_id" class="form-label">Class <span class="text-danger">*</span></label>
                                            <select class="form-select @error('class_level_id') is-invalid @enderror" 
                                                    id="class_level_id" 
                                                    name="class_level_id">
                                                
                                                <option value="">Select Class</option>
                                                
                                                @foreach($classLevels as $class)
                                                    <option value="{{ $class->id }}" 
                                                        {{ (old('class_level_id', $student->studentProfile?->class_level_id) == $class->id) ? 'selected' : '' }}>
                                                        {{ $class->name }}
                                                    </option>
                                                @endforeach
                                                
                                            </select>
                                            @error('class_level_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Section Selection --}}
                                        <div class="mb-3">
                                            <label for="section_id" class="form-label">Section</label>
                                            <select class="form-select @error('section_id') is-invalid @enderror" 
                                                    id="section_id" 
                                                    name="section_id">
                                                
                                                <option value="">Select Section</option>
                                                
                                                @foreach($sections as $section)
                                                    <option value="{{ $section->id }}" 
                                                        {{ (old('section_id', $student->studentProfile?->section_id) == $section->id) ? 'selected' : '' }}>
                                                        {{ $section->name }} 
                                                    </option>
                                                @endforeach

                                            </select>
                                            @error('section_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Admission Date --}}
                                        <div class="mb-3">
                                            <label for="admission_date" class="form-label">Admission Date</label>
                                            <input type="date" class="form-control @error('admission_date') is-invalid @enderror"
                                                   id="admission_date" name="admission_date" 
                                                   value="{{ old('admission_date', $student->studentProfile?->admission_date?->format('Y-m-d')) }}">
                                            @error('admission_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Approval Switch --}}
                                        <div class="card bg-light border-0 mb-3">
                                            <div class="card-body">
                                                <label class="form-label fw-bold">Account Status</label>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved" value="1" 
                                                        {{ old('is_approved', $student->is_approved) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_approved">
                                                        Approve Account (Active)
                                                    </label>
                                                </div>
                                                <small class="text-muted">
                                                    Check this to verify the student and allow them to login.
                                                </small>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('schooladmin.students.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-1"></i>Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                Update Student
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

@push('scripts')
<script>
    document.getElementById('generateIdBtn').addEventListener('click', function() {
        const prefix = this.getAttribute('data-prefix'); 
        const year = new Date().getFullYear();
        const random = Math.floor(1000 + Math.random() * 9000);
        
        // Random generation with slashes for Edit mode
        const generatedId = prefix + '/' + year + '/' + random; 
        
        document.getElementById('admission_number').value = generatedId;
    });
</script>
@endpush