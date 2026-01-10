@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        
        {{-- Sidebar --}}
        @include('superadmin.partials.sidebar')

        {{-- Main Content Area --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="mb-2 mb-md-0">
                            <h3 class="h5 mb-1 fw-bold text-primary">
                                Create New User
                            </h3>
                            <div class="text-muted small">
                                <a href="{{ route('superadmin.users.index') }}" class="text-decoration-none text-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Users
                                </a>
                                <span class="mx-2 text-muted">|</span>
                                <span>Add a new system user</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary d-none d-md-block">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            {{-- button for 'form' attribute --}}
                            <button type="submit" form="userForm" class="btn btn-primary">
                                Create User
                            </button>
                        </div>
                    </div>
                </div>
  
                <div class="card-body">
                    <form id="userForm" action="{{ route('superadmin.users.store') }}" method="POST">
                        @csrf
                        
                        <h6 class="text-uppercase text-muted fw-bold mb-3 small">Personal Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required placeholder="John Doe">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required placeholder="john@example.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" placeholder="+1234567890">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="school_id" class="form-label">School Assignment</label>
                                    <select class="form-select @error('school_id') is-invalid @enderror" 
                                            id="school_id" name="school_id">
                                        <option value="">Select School</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                                {{ $school->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('school_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <h6 class="text-uppercase text-muted fw-bold mb-3 small">Account Settings</h6>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role') is-invalid @enderror" 
                                            id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Account Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row bg-light p-3 rounded border mx-1">
                            <div class="col-md-6">
                                <div class="mb-3 mb-md-0">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required placeholder="Enter secure password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div>
                                    <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required placeholder="Repeat password">
                                </div>
                            </div>
                        </div>

                        {{-- Mobile Only--}}
                        <div class="mt-4 d-block d-md-none">
                            <button type="submit" class="btn btn-primary w-100 mb-2">Create User</button>
                            <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary w-100">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Password strength indicator
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        let strengthIndicator = document.getElementById('password-strength');
        
        // Ensure the parent container exists 
        if (!strengthIndicator) {
            strengthIndicator = document.createElement('div');
            strengthIndicator.id = 'password-strength';
            strengthIndicator.className = 'mt-2';
            this.parentNode.appendChild(strengthIndicator);
        }
        
        // Hide if empty
        if (password.length === 0) {
            strengthIndicator.innerHTML = '';
            return;
        }

        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        // Clamp strength between 0 and 3
        if (strength > 3) strength = 3;
        
        const strengthText = ['Very Weak', 'Weak', 'Medium', 'Strong'];
        const strengthColor = ['danger', 'warning', 'info', 'success'];
        const width = (strength + 1) * 25;
        
        strengthIndicator.innerHTML = `
            <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-${strengthColor[strength]}" style="width: ${width}%"></div>
            </div>
            <small class="text-${strengthColor[strength]} fw-bold mt-1 d-block" style="font-size: 0.75rem;">
                ${strengthText[strength]}
            </small>
        `;
    });
</script>
@endsection