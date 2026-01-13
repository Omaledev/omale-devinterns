@extends('layouts.app')

@php
    $schoolName = $teacher->school->name ?? 'EMP';
    $schoolPrefix = '';
    $words = explode(' ', $schoolName);
    foreach($words as $word) {
        $schoolPrefix .= strtoupper(substr($word, 0, 1));
    }
    $schoolPrefix = substr($schoolPrefix, 0, 4);
@endphp

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('schooladmin.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">Edit Teacher</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.teachers.index') }}">Teachers</a></li>
                            <li class="breadcrumb-item active">Edit Teacher</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('schooladmin.teachers.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>Back to Teachers
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                Edit Teacher Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('schooladmin.teachers.update', $teacher) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Personal Information</h6>

                                        <div class="mb-3">
                                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   id="name" name="name" value="{{ old('name', $teacher->name) }}" 
                                                   placeholder="Enter teacher's full name" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                   id="email" name="email" value="{{ old('email', $teacher->email) }}"
                                                   placeholder="Enter email address" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                   id="phone" name="phone" value="{{ old('phone', $teacher->phone) }}"
                                                   placeholder="Enter phone number">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror"
                                                      id="address" name="address" rows="3"
                                                      placeholder="Enter teacher's address">{{ old('address', $teacher->teacherProfile->address ?? '') }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Professional Information</h6>

                                        <div class="mb-3">
                                            <label for="employee_id" class="form-label">Employee ID <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" 
                                                    class="form-control @error('employee_id') is-invalid @enderror"
                                                    id="employee_id" 
                                                    name="employee_id" 
                                                    value="{{ old('employee_id', $teacher->employee_id) }}"
                                                    placeholder="Enter or Generate ID" 
                                                    required>
                                                    
                                                <button class="btn btn-outline-secondary" 
                                                        type="button" 
                                                        id="generateIdBtn"
                                                        data-prefix="{{ $schoolPrefix }}">
                                                    <i class="fas fa-magic me-1"></i>Generate
                                                </button>
                                            </div>
                                            @error('employee_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="card bg-light border-0 mb-3">
                                            <div class="card-body">
                                                <label class="form-label fw-bold">Account Status</label>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved" value="1" 
                                                        {{ $teacher->is_approved ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_approved">
                                                        Approve Account (Active)
                                                    </label>
                                                </div>
                                                <small class="text-muted">
                                                    Unchecking this will prevent the teacher from logging in.
                                                    <br>
                                                    <span class="text-danger"><i class="fas fa-info-circle"></i> Note: To see the green "Active" badge on the list, the teacher must be approved AND assigned to a subject.</span>
                                                </small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">New Password</label>
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                           id="password" name="password" placeholder="Leave blank to keep current">
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">Leave blank if you don't want to change password</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                                    <input type="password" class="form-control"
                                                           id="password_confirmation" name="password_confirmation" 
                                                           placeholder="Confirm new password">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('schooladmin.teachers.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-1"></i>Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                Update Teacher
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
        
        // Result: SCH20248831
        const generatedId = prefix + year + random;
        
        document.getElementById('employee_id').value = generatedId;
    });
</script>
@endpush