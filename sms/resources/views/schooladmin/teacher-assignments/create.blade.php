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
                    <h1 class="h2">Assign Teacher to Subject & Class</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.teacher-assignments.index') }}">Teacher Assignments</a></li>
                            <li class="breadcrumb-item active">Assign Teacher</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('schooladmin.teacher-assignments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Assignments
                    </a>
                </div>
            </div>

            <!-- Assignment Form -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-link me-2"></i>Teacher Assignment
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('schooladmin.teacher-assignments.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                       <div class="mb-3">
                                            <label for="teacher_id" class="form-label">Teacher <span class="text-danger">*</span></label>
                                            <select class="form-select @error('teacher_id') is-invalid @enderror" 
                                                    id="teacher_id" name="teacher_id" required>
                                                <option value="">Select Teacher</option>
                                                @foreach($teachers as $user)
                                                    @if($user->teacherProfile)
                                                        <option value="{{ $user->teacherProfile->id }}" {{ old('teacher_id') == $user->teacherProfile->id ? 'selected' : '' }}>
                                                            {{ $user->name }} ({{ $user->email }})
                                                            @if($user->teacherProfile->employee_id)
                                                                - {{ $user->teacherProfile->employee_id }}
                                                            @endif
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('teacher_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
                                            <select class="form-select @error('subject_id') is-invalid @enderror" 
                                                    id="subject_id" name="subject_id" required>
                                                <option value="">Select Subject</option>
                                                @foreach($subjects as $subject)
                                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                        {{ $subject->name }} ({{ $subject->code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('subject_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="class_level_id" class="form-label">Class Level <span class="text-danger">*</span></label>
                                            <select class="form-select @error('class_level_id') is-invalid @enderror" 
                                                    id="class_level_id" name="class_level_id" required>
                                                <option value="">Select Class Level</option>
                                                @foreach($classLevels as $classLevel)
                                                    <option value="{{ $classLevel->id }}" {{ old('class_level_id') == $classLevel->id ? 'selected' : '' }}>
                                                        {{ $classLevel->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('class_level_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="section_id" class="form-label">Section (Optional)</label>
                                            <select class="form-select @error('section_id') is-invalid @enderror" 
                                                    id="section_id" name="section_id">
                                                <option value="">Select Section (Optional)</option>
                                                @foreach($sections as $section)
                                                    <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                                        {{ $section->name }} - {{ $section->classLevel->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('section_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="academic_year" class="form-label">Academic Year <span class="text-danger">*</span></label>
                                            <select class="form-select @error('academic_year') is-invalid @enderror" 
                                                    id="academic_year" name="academic_year" required>
                                                <option value="">Select Academic Year</option>
                                                @php
                                                    $currentYear = date('Y');
                                                    $nextYear = $currentYear + 1;
                                                @endphp
                                                <option value="{{ $currentYear }}-{{ $nextYear }}" {{ old('academic_year') == "$currentYear-$nextYear" ? 'selected' : '' }}>
                                                    {{ $currentYear }}-{{ $nextYear }}
                                                </option>
                                                <option value="{{ $currentYear-1 }}-{{ $currentYear }}" {{ old('academic_year') == ($currentYear-1)."-".$currentYear ? 'selected' : '' }}>
                                                    {{ $currentYear-1 }}-{{ $currentYear }}
                                                </option>
                                                <option value="{{ $currentYear+1 }}-{{ $currentYear+2 }}" {{ old('academic_year') == ($currentYear+1)."-".($currentYear+2) ? 'selected' : '' }}>
                                                    {{ $currentYear+1 }}-{{ $currentYear+2 }}
                                                </option>
                                            </select>
                                            @error('academic_year')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('schooladmin.teacher-assignments.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-1"></i>Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-link me-1"></i>Assign Teacher
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