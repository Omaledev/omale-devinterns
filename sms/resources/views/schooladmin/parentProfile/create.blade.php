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
                    <h1 class="h2">Add New Parent</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.parents.index') }}">Parents</a></li>
                            <li class="breadcrumb-item active">Add Parent</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('schooladmin.parents.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>Back to Parents
                    </a>
                </div>
            </div>

            <!-- Parent Form -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-user-plus me-2"></i>Parent Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('schooladmin.parents.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <!-- Personal Information -->
                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Personal Information</h6>

                                        <div class="mb-3">
                                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   id="name" name="name" value="{{ old('name') }}"
                                                   placeholder="Enter parent's full name" required>
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

                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                   id="password" name="password" 
                                                   placeholder="Enter password" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control"
                                                   id="password_confirmation" name="password_confirmation" 
                                                   placeholder="Confirm password" required>
                                        </div>
                                    </div>

                                    <!-- Additional Information -->
                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Additional Information</h6>

                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror"
                                                      id="address" name="address" rows="4"
                                                      placeholder="Enter parent's address">{{ old('address') }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Student Selection - FIXED VERSION -->
                                        <div class="mb-3">
                                            <label class="form-label">Assign Children</label>
                                            
                                            <!-- Search Box -->
                                            <div class="mb-3">
                                                <input type="text" class="form-control" id="studentSearch" 
                                                       placeholder="Search students by name, admission number, or class...">
                                            </div>
                                            
                                            <!-- Selected Students Display -->
                                            <div class="mb-3" id="selectedStudents" style="display: none;">
                                                <label class="form-label">Selected Students:</label>
                                                <div id="selectedList" class="d-flex flex-wrap gap-2 p-3 border rounded bg-light"></div>
                                            </div>
                                            
                                            <!-- Students List -->
                                            <div class="border rounded" style="max-height: 300px; overflow-y: auto;">
                                                <div class="p-3">
                                                    <div id="studentsList">
                                                        @foreach($students->chunk(2) as $chunk)
                                                        <div class="row">
                                                            @foreach($chunk as $student)
                                                            <div class="col-md-6 mb-2 student-item">
                                                                <div class="form-check">
                                                                    <input class="form-check-input student-checkbox" 
                                                                           type="checkbox" 
                                                                           name="children[]" 
                                                                           value="{{ $student->id }}"
                                                                           id="student_{{ $student->id }}"
                                                                           {{ in_array($student->id, old('children', [])) ? 'checked' : '' }}>
                                                                    <label class="form-check-label w-100" for="student_{{ $student->id }}">
                                                                        <div class="d-flex justify-content-between">
                                                                            <span class="fw-bold">{{ $student->name }}</span>
                                                                            <small class="text-muted">{{ $student->admission_number }}</small>
                                                                        </div>
                                                                        <small class="text-muted d-block">
                                                                            {{ $student->class ?? 'No Class' }} • {{ $student->section ?? 'No Section' }}
                                                                        </small>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    
                                                    @if($students->isEmpty())
                                                    <div class="text-center text-muted py-4">
                                                        <i class="fas fa-user-graduate fa-2x mb-2"></i>
                                                        <p>No students available</p>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            @error('children')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Select students by checking the boxes. Use search to filter the list.</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('schooladmin.parents.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-1"></i>Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i>Create Parent
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
    const searchInput = document.getElementById('studentSearch');
    const studentItems = document.querySelectorAll('.student-item');
    const selectedDiv = document.getElementById('selectedStudents');
    const selectedList = document.getElementById('selectedList');
    const checkboxes = document.querySelectorAll('.student-checkbox');
    
    let selectedStudents = new Set();
    
    // Initialize selected students from checked boxes
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            addSelectedStudent(checkbox);
        }
    });
    
    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        studentItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchTerm) ? 'block' : 'none';
        });
    });
    
    // Checkbox change handler
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                addSelectedStudent(this);
            } else {
                removeSelectedStudent(this);
            }
        });
    });
    
    function addSelectedStudent(checkbox) {
        const studentId = checkbox.value;
        const label = checkbox.closest('.form-check').querySelector('.form-check-label');
        const studentName = label.querySelector('.fw-bold').textContent;
        const admissionNo = label.querySelector('small.text-muted').textContent;
        
        selectedStudents.add(studentId);
        
        const badge = document.createElement('span');
        badge.className = 'badge bg-primary d-flex align-items-center';
        badge.innerHTML = `
            ${studentName}
            <button type="button" class="btn-close btn-close-white ms-2" 
                    data-student-id="${studentId}"></button>
        `;
        
        selectedList.appendChild(badge);
        selectedDiv.style.display = 'block';
        
        // Remove button handler
        badge.querySelector('.btn-close').addEventListener('click', function() {
            const id = this.getAttribute('data-student-id');
            const correspondingCheckbox = document.querySelector(`.student-checkbox[value="${id}"]`);
            if (correspondingCheckbox) {
                correspondingCheckbox.checked = false;
                removeSelectedStudent(correspondingCheckbox);
            }
        });
    }
    
    function removeSelectedStudent(checkbox) {
        const studentId = checkbox.value;
        selectedStudents.delete(studentId);
        
        const badge = selectedList.querySelector(`[data-student-id="${studentId}"]`)?.closest('.badge');
        if (badge) {
            badge.remove();
        }
        
        if (selectedStudents.size === 0) {
            selectedDiv.style.display = 'none';
        }
    }
});
</script>
@endpush