@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Left Side - Background Image/Color -->
            <div class="col-lg-4 d-none d-lg-flex login-left-section">
                <div class="d-flex align-items-center justify-content-center w-100">
                    <div class="text-center text-white px-5">
                        <div class="mb-5">
                            <div class="bg-white bg-opacity-25 rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                                style="width: 100px; height: 100px;">
                                <i class="fas fa-graduation-cap fa-3x text-white"></i>
                            </div>
                            <h1 class="display-5 fw-bold mb-3">Axia School Management System</h1>
                            <p class="lead">Empowering education through technology. Manage your academic journey with
                                ease and efficiency.</p>
                        </div>
                        <div class="features-list text-start">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-check-circle me-3 fa-lg"></i>
                                <span>Streamlined Academic Management</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-check-circle me-3 fa-lg"></i>
                                <span>Real-time Progress Tracking</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-check-circle me-3 fa-lg"></i>
                                <span>Secure & Reliable Platform</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="col-lg-8 d-flex align-items-center justify-content-center py-5">
                <div class="w-100" style="max-width: 400px;">
                    <!-- Header -->
                    <div class="text-center mb-5">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 70px; height: 70px;">
                            <i class="fas fa-lock fa-2x text-white"></i>
                        </div>
                        <h2 class="fw-bold text-primary mb-2">Welcome Back</h2>
                        <p class="text-muted">Sign in to your account to continue</p>
                    </div>

                    <!-- Login Form -->
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('sign-in') }}">
                                @csrf

                                <!-- Email Input -->
                                <div class="mb-4">
                                    <label for="email" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-envelope me-2 text-primary"></i>Email Address
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror border-start-0 ps-0"
                                            name="email" value="{{ old('email') }}"
                                            placeholder="Enter your email address" required autocomplete="email" autofocus>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
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
                                            name="password" placeholder="Enter your password" required
                                            autocomplete="current-password">
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

                                <!-- Remember Me & Forgot Password -->
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label text-muted" for="remember">
                                            Remember me
                                        </label>
                                    </div>
                                    @if (Route::has('password.request'))
                                        <a class="text-decoration-none text-primary fw-semibold"
                                            href="{{ route('password.request') }}">
                                            Forgot Password?
                                        </a>
                                    @endif
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-primary btn-lg fw-semibold py-2">
                                        <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                    </button>
                                </div>

                                <!-- Divider -->
                                <div class="text-center mb-4">
                                    <div class="position-relative">
                                        <hr class="text-muted">
                                        <span
                                            class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted">
                                            Or
                                        </span>
                                    </div>
                                </div>

                                <!-- Register Link -->
                                <div class="text-center">
                                    <p class="text-muted mb-0">
                                        Don't have an account?
                                        <a href="{{ route('sign-up') }}"
                                            class="text-decoration-none fw-semibold text-primary">
                                            Sign Up
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
                            Your data is securely protected
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

        .form-control {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus {
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

        #togglePassword {
            border-radius: 0 0.5rem 0.5rem 0;
            border: 2px solid #e9ecef;
            border-left: none;
        }

        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .login-left-section {
                display: none !important;
            }

            .container-fluid {
                /* background:#ffffff; */
            }

            .card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            if (togglePassword && password) {
                togglePassword.addEventListener('click', function() {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);

                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
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
