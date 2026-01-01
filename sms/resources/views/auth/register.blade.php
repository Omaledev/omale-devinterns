
@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Left Side div -->
            <div class="col-lg-4 d-none d-lg-flex login-left-section">
                <div class="d-flex align-items-center justify-content-center w-100">
                    <div class="text-center text-white px-5">
                        <div class="mb-5">
                            <div class="bg-white bg-opacity-25 rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                                style="width: 100px; height: 100px;">
                                <i class="fas fa-user-plus fa-3x text-white"></i>
                            </div>
                            <h1 class="display-5 fw-bold mb-3">Create Your Account</h1>
                            <p class="lead">Join thousands of educational professionals, students, and parents using our
                                platform.</p>
                        </div>

                        <!-- Role Information -->
                        <div class="role-info">
                            <h5 class="mb-4 text-center border-bottom pb-2">Choose Your Role</h5>

                            <!-- Student Card -->
                            <div class="role-card mb-4 p-3 rounded" style="background: rgba(255,255,255,0.1);">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-graduation-cap me-2 text-success fa-lg"></i>
                                    <h6 class="mb-0 fw-bold">Student</h6>
                                </div>
                                <p class="small mb-0 opacity-90">
                                    Access your timetable, assignments, grades, and academic progress in one place.
                                </p>
                            </div>

                            <!-- Parent Card -->
                            <div class="role-card mb-4 p-3 rounded" style="background: rgba(255,255,255,0.1);">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-users me-2 text-primary fa-lg"></i>
                                    <h6 class="mb-0 fw-bold">Parent</h6>
                                </div>
                                <p class="small mb-0 opacity-90">
                                    Monitor your children's academic performance, attendance, and school activities.
                                </p>
                            </div>

                            <!-- Teacher Card -->
                            <div class="role-card mb-4 p-3 rounded" style="background: rgba(255,255,255,0.1);">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-chalkboard-teacher me-2 text-warning fa-lg"></i>
                                    <h6 class="mb-0 fw-bold">Teacher</h6>
                                </div>
                                <p class="small mb-0 opacity-90">
                                    Manage classes, assignments, grading, and communicate with students and parents.
                                </p>
                            </div>
                        </div>

                        <!-- Quick Guide -->
                        <div class="mt-5 pt-4 border-top">
                            <h6 class="mb-3">Registration Process</h6>
                            <div class="text-start small">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-white text-primary me-2">1</span>
                                    <span>Fill in your basic information</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-white text-primary me-2">2</span>
                                    <span>Select your role (Student/Parent/Teacher)</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-white text-primary me-2">3</span>
                                    <span>Provide role-specific details</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-white text-primary me-2">4</span>
                                    <span>Set up your secure password</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side div for registraion -->
            <div class="col-lg-8 d-flex align-items-center justify-content-center py-5">
                <div class="w-100" style="max-width: 450px;">
                    <!-- Header -->
                    <div class="text-center mb-5">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 70px; height: 70px;">
                            <i class="fas fa-user-plus fa-2x text-white"></i>
                        </div>
                        <h2 class="fw-bold text-primary mb-2">Create Account</h2>
                        <p class="text-muted">Join our educational community today</p>
                    </div>

                    <!-- Register Form -->
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('sign-up') }}">
                                @csrf

                                <!-- Name Input -->
                                <div class="mb-4">
                                    <label for="name" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-user me-2 text-primary"></i>Full Name
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-id-card text-muted"></i>
                                        </span>
                                        <input id="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror border-start-0 ps-0"
                                            name="name" value="{{ old('name') }}" placeholder="Enter your full name"
                                            required autocomplete="name" autofocus>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Email Input -->
                                <div class="mb-4">
                                    <label for="email" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-envelope me-2 text-primary"></i>Email Address
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-at text-muted"></i>
                                        </span>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror border-start-0 ps-0"
                                            name="email" value="{{ old('email') }}"
                                            placeholder="Enter your email address" required autocomplete="email">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="school_id" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-school me-2 text-primary"></i>Select School
                                    </label>
                                    <select id="school_id" class="form-select @error('school_id') is-invalid @enderror" 
                                            name="school_id" required style="cursor: pointer;">
                                        <option value="" disabled {{ old('school_id') ? '' : 'selected' }}>Choose your school...</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                                🏫 {{ $school->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('school_id')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Role Selection -->
                                <div class="mb-4">
                                    <label for="role" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-user-tag me-2 text-primary"></i>Register As
                                    </label>
                                    <select id="role" class="form-select @error('role') is-invalid @enderror"
                                        name="role" required
                                        style="cursor: pointer; padding: 0.75rem 1rem; border: 2px solid #e9ecef; border-radius: 0.5rem;">
                                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Choose your
                                            role...</option>
                                        <option value="Student" {{ old('role') == 'Student' ? 'selected' : '' }}
                                            class="text-success">
                                            👨‍🎓 Student
                                        </option>
                                        <option value="Parent" {{ old('role') == 'Parent' ? 'selected' : '' }}
                                            class="text-primary">
                                            👨‍👩‍👧‍👦 Parent
                                        </option>
                                        <option value="Teacher" {{ old('role') == 'Teacher' ? 'selected' : '' }}
                                            class="text-warning">
                                            👩‍🏫 Teacher
                                        </option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror

                                    <!-- Info Alert -->
                                    <div class="alert alert-info mt-3 p-3 border-0"
                                        style="background-color: #f0f8ff; border-left: 4px solid #0dcaf0;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-info-circle me-2 text-info"></i>
                                            <small class="flex-grow-1">
                                                <strong>Note:</strong> School Admin, Bursar, and SuperAdmin roles are
                                                assigned by system administrators.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Role Specific Fields -->
                                <!-- Student Fields -->
                                <div id="student-fields" class="role-specific-fields mb-4" style="display: none;">
                                    <label for="admission_number" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-id-card me-2 text-success"></i>Admission Number 
                                        <span class="text-muted small fw-normal">(Optional)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-graduation-cap text-success"></i>
                                        </span>
                                        <input id="admission_number" type="text"
                                            class="form-control border-start-0 ps-0" name="admission_number"
                                            value="{{ old('admission_number') }}" 
                                            placeholder="Enter ID (or leave blank if new)">
                                    </div>
                                    
                                </div>

                                <!-- Teacher Fields -->
                                <div id="teacher-fields" class="role-specific-fields mb-4" style="display: none;">
                                    <label for="employee_id" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-briefcase me-2 text-warning"></i>Employee ID
                                        <span class="text-muted small fw-normal">(Optional)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-user-tie text-warning"></i>
                                        </span>
                                        <input id="employee_id" type="text" class="form-control border-start-0 ps-0"
                                            name="employee_id" value="{{ old('employee_id') }}"
                                            placeholder="Enter ID (or leave blank if new)">
                                    </div>
                                </div>

                                <!-- Password Input -->
                                <div class="mb-4">
                                    <label for="password" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-key me-2 text-primary"></i>Password
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror border-start-0 ps-0"
                                            name="password" placeholder="Create a password" required
                                            autocomplete="new-password">
                                        <button class="btn btn-outline-secondary border-start-0" type="button"
                                            id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Confirm Password Input -->
                                <div class="mb-4">
                                    <label for="password-confirm" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-key me-2 text-primary"></i>Confirm Password
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input id="password-confirm" type="password"
                                            class="form-control border-start-0 ps-0" name="password_confirmation"
                                            placeholder="Confirm your password" required autocomplete="new-password">
                                        <button class="btn btn-outline-secondary border-start-0" type="button"
                                            id="toggleConfirmPassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-primary btn-lg fw-semibold py-2">
                                        <i class="fas fa-user-plus me-2"></i>Create Account
                                    </button>
                                </div>

                                <!-- Divider -->
                                <div class="text-center mb-4">
                                    <div class="position-relative">
                                        <hr class="text-muted">
                                        <span
                                            class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted">
                                            Already have an account?
                                        </span>
                                    </div>
                                </div>

                                <!-- Login Link -->
                                <div class="text-center">
                                    <p class="text-muted mb-0">
                                        Already registered?
                                        <a href="{{ route('sign-in') }}"
                                            class="text-decoration-none fw-semibold text-primary">
                                            Sign In
                                        </a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="text-center mt-4">
                        <p class="text-muted small">
                            <i class="fas fa-shield-alt me-1 text-success"></i>
                            Your information is securely protected
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .login-left-section {
            background: blue;
            position: relative;
            overflow: hidden;
        }

        .login-left-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.05)" points="0,1000 1000,0 1000,1000"/></svg>');
        }

        .login-left-section>div {
            position: relative;
            z-index: 2;
        }

        .features-list {
            font-size: 1.1rem;
        }

        .card {
            border-radius: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
        }

        .form-control,
        .form-select {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .input-group-text {
            border-radius: 0.5rem 0 0 0.5rem;
            border: 2px solid #e9ecef;
            border-right: none;
            background-color: #f8f9fa;
        }

        .btn-primary {
            border: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.3);
        }

        #togglePassword,
        #toggleConfirmPassword {
            border-radius: 0 0.5rem 0.5rem 0;
            border: 2px solid #e9ecef;
            border-left: none;
        }

        .role-specific-fields {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .login-left-section {
                display: none !important;
            }

            .container-fluid {
                /* background: #ffffff; */
            }

            .card {
                background: #ffffff;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password-confirm');

            // Toggle main password
            if (togglePassword && password) {
                togglePassword.addEventListener('click', function() {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);

                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }

            // Toggle confirm password
            if (toggleConfirmPassword && passwordConfirm) {
                toggleConfirmPassword.addEventListener('click', function() {
                    const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordConfirm.setAttribute('type', type);

                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }

            // Role selection functionality
            const roleSelect = document.getElementById('role');
            if (roleSelect) {
                roleSelect.addEventListener('change', function() {
                    // Hide all role-specific fields
                    document.querySelectorAll('.role-specific-fields').forEach(field => {
                        field.style.display = 'none';
                    });

                    // Show fields for selected role
                    if (this.value === 'Student') {
                        document.getElementById('student-fields').style.display = 'block';
                    } else if (this.value === 'Teacher') {
                        document.getElementById('teacher-fields').style.display = 'block';
                    }
                });

                // Trigger change on page load to show/hide fields based on old input
                document.addEventListener('DOMContentLoaded', function() {
                    roleSelect.dispatchEvent(new Event('change'));
                });
            }

            // Add floating label effect
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.parentElement.classList.remove('focused');
                    }
                });

                // Check initial state
                if (input.value) {
                    input.parentElement.parentElement.classList.add('focused');
                }
            });
        });
    </script>
@endpush
