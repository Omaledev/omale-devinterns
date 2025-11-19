@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required autocomplete="name" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- ROLE SELECTION FIELD -->
                            <div class="row mb-3">
                                <label for="role"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Register As') }}</label>
                                <div class="col-md-6">
                                    <label for="role" class="form-label fw-semibold">Select Your Role <span
                                            class="text-danger">*</span></label>
                                    <select id="role" class="form-select @error('role') is-invalid @enderror"
                                        name="role" required style="cursor: pointer;">
                                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Choose your
                                            role...</option>

                                        <option value="Student" {{ old('role') == 'Student' ? 'selected' : '' }}
                                            class="text-success fw-medium">
                                            👨‍🎓 Student
                                        </option>

                                        <option value="Parent" {{ old('role') == 'Parent' ? 'selected' : '' }}
                                            class="text-primary fw-medium">
                                            👨‍👩‍👧‍👦 Parent
                                        </option>

                                        <option value="Teacher" {{ old('role') == 'Teacher' ? 'selected' : '' }}
                                            class="text-warning fw-medium">
                                            👩‍🏫 Teacher
                                        </option>
                                    </select>

                                    @error('role')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror

                                    <div class="alert alert-info mt-2 p-2"
                                        style="cursor: help; border-left: 4px solid #0dcaf0;">
                                        <small class="d-flex align-items-center">
                                            <i class="fas fa-info-circle me-2 text-info"></i>
                                            <span>
                                                <strong>Note:</strong> School Admin, Bursar, and SuperAdmin roles are
                                                assigned by system administrators.
                                            </span>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional fields  based on the role selection -->
                            <div id="student-fields" class="role-specific-fields" style="display: none;">
                                <div class="row mb-3">
                                    <label for="admission_number"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Admission Number') }}</label>
                                    <div class="col-md-6">
                                        <input id="admission_number" type="text" class="form-control"
                                            name="admission_number" value="{{ old('admission_number') }}">
                                    </div>
                                </div>
                            </div>

                            <div id="teacher-fields" class="role-specific-fields" style="display: none;">
                                <div class="row mb-3">
                                    <label for="employee_id"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Employee ID') }}</label>
                                    <div class="col-md-6">
                                        <input id="employee_id" type="text" class="form-control" name="employee_id"
                                            value="{{ old('employee_id') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <script>
        document.getElementById('role').addEventListener('change', function() {
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
            document.getElementById('role').dispatchEvent(new Event('change'));
        });
    </script> --}}
@endsection
