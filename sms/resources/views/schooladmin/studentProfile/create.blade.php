@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('schooladmin.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
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

            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">Student Information</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('schooladmin.students.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Personal Information</h6>
                                        <div class="mb-3">
                                            <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                                id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                                            @error('full_name')
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
                                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                id="password" name="password" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control"
                                                id="password_confirmation" name="password_confirmation" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                   id="phone" name="phone" value="{{ old('phone') }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                                   id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Academic Information</h6>

                                        <div class="mb-3">
                                            <label for="admission_number" class="form-label">Admission Number <span class="text-danger">*</span></label>
                                            
                                            <div class="input-group">
                                                <input type="text" 
                                                    class="form-control @error('admission_number') is-invalid @enderror"
                                                    id="admission_number" 
                                                    name="admission_number" 
                                                    value="{{ old('admission_number') }}" 
                                                    placeholder="Click Generate to assign next ID"
                                                    readonly
                                                    required>
                                                
                                                <button class="btn btn-primary" 
                                                        type="button" 
                                                        id="generateIdBtn"
                                                        data-next-id="{{ $nextAdmissionNumber }}">
                                                    Generate ID
                                                </button>
                                            </div>
                                            
                                            @error('admission_number')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                       <div class="mb-3">
                                            <label for="class_level_id" class="form-label">Class <span class="text-danger">*</span></label>
                                            <select class="form-select @error('class_level_id') is-invalid @enderror" 
                                                    id="class_level_id" name="class_level_id">
                                                <option value="">Select Class</option>
                                                @foreach($classLevels as $class)
                                                    <option value="{{ $class->id }}" {{ old('class_level_id') == $class->id ? 'selected' : '' }}>
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
                                                    id="section_id" name="section_id">
                                                <option value="">Select Section</option>
                                                @foreach($sections as $section)
                                                    <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                                        {{ $section->name }} 
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="admission_date" class="form-label">Admission Date</label>
                                            <input type="date" class="form-control" name="admission_date" value="{{ old('admission_date', date('Y-m-d')) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="state" class="form-label">State</label>
                                            <input type="text" class="form-control" name="state" value="{{ old('state') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="emergency_contact" class="form-label">Emergency Contact</label>
                                            <input type="text" class="form-control" name="emergency_contact" value="{{ old('emergency_contact') }}">
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
                                                Add Student
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
    document.addEventListener('DOMContentLoaded', function() {
        const generateBtn = document.getElementById('generateIdBtn');
        const inputField = document.getElementById('admission_number');

        if (generateBtn) {
            generateBtn.addEventListener('click', function() {
                // Get the ID that was calculated by the Controller (Sequential)
                const sequentialId = this.getAttribute('data-next-id');
                
                // Fill the input box
                inputField.value = sequentialId;
            });
        }
    });
</script>
@endpush