@extends('layouts.app')

@php
    // Get School Name
    $schoolName = $student->school->name ?? 'STD';
    
    // Extract first letter of each word (e.g., "High School" -> "HS")
    $schoolPrefix = '';
    $words = explode(' ', $schoolName);
    foreach($words as $word) {
        $schoolPrefix .= strtoupper(substr($word, 0, 1));
    }
    
    // Keep it reasonable (max 4 chars)
    $schoolPrefix = substr($schoolPrefix, 0, 4);
@endphp

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
                                Edit Student Information
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
                                            <label for="admission_number" class="form-label">
                                                Admission Number <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="text" 
                                                    class="form-control @error('admission_number') is-invalid @enderror"
                                                    id="admission_number" 
                                                    name="admission_number" 
                                                    value="{{ old('admission_number', $student->admission_number) }}"
                                                    placeholder="Enter or Generate ID" 
                                                    required>
                                                    
                                                <button class="btn btn-outline-secondary" 
                                                        type="button" 
                                                        id="generateIdBtn"
                                                        data-prefix="{{ $schoolPrefix }}">
                                                    <i class="fas fa-magic me-1"></i>Generate
                                                </button>
                                            </div>
                                            
                                            @if(!$student->admission_number)
                                                <div class="form-text text-warning">
                                                    <i class="fas fa-info-circle"></i> This student registered online. Please assign an ID now.
                                                </div>
                                            @endif

                                            @error('admission_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="class_level_id" class="form-label">Class <span class="text-danger">*</span></label>
                                            <select class="form-select @error('class_level_id') is-invalid @enderror" 
                                                    id="class_level_id" 
                                                    name="class_level_id">
                                                
                                                <option value="">Select Class</option>
                                                
                                                @foreach($classLevels as $class)
                                                    <option value="{{ $class->id }}" 
                                                        {{-- Check if the student's current profile class matches this option --}}
                                                        {{ (old('class_level_id', $student->studentProfile->class_level_id ?? '') == $class->id) ? 'selected' : '' }}>
                                                        {{ $class->name }}
                                                    </option>
                                                @endforeach
                                                
                                            </select>
                                            @error('class_level_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="section_id" class="form-label">Section</label>
                                            <select class="form-select @error('section_id') is-invalid @enderror" 
                                                    id="section_id" 
                                                    name="section_id">
                                                
                                                <option value="">Select Section</option>
                                                
                                                @foreach($sections as $section)
                                                    <option value="{{ $section->id }}" 
                                                        {{-- Logic: Check 'old' input first (if validation failed), otherwise check database value --}}
                                                        {{ (old('section_id', $student->studentProfile->section_id ?? '') == $section->id) ? 'selected' : '' }}>
                                                        
                                                        {{ $section->name }} 
                                                    </option>
                                                @endforeach

                                            </select>
                                            
                                            @error('section_id')
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
        // Get the School Prefix from the button
        const prefix = this.getAttribute('data-prefix'); 
        
        // Get current year (e.g., 2026)
        const year = new Date().getFullYear();
       
        // Generate Random 4 digits
        const random = Math.floor(1000 + Math.random() * 9000);
        
        // Combine them: GVA + 2024 + 5892
        const generatedId = prefix + year + random;
        
        document.getElementById('admission_number').value = generatedId;
    });
</script>
@endpush