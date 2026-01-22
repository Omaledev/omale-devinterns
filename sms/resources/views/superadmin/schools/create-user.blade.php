@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        
        {{-- Sidebar --}}
        @include('superadmin.partials.sidebar')

        {{-- Content --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">Create User for {{ $school->name }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('superadmin.schools.index') }}">Schools</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('superadmin.schools.show', $school) }}">{{ $school->name }}</a></li>
                            <li class="breadcrumb-item active">Create User</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('superadmin.schools.show', $school) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to School
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                User Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('superadmin.schools.store-user', $school) }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                                name="name" value="{{ old('name') }}" required autofocus
                                                placeholder="Enter user's full name">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email') }}" required
                                                placeholder="Enter email address">
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
                                            <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                name="phone" value="{{ old('phone') }}"
                                                placeholder="Enter phone number">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                            <select id="role" class="form-select @error('role') is-invalid @enderror" name="role" required>
                                                <option value="">Select Role</option>
                                                @foreach($availableRoles as $role)
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
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                                name="password" required
                                                placeholder="Enter password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password-confirm" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                            <input id="password-confirm" type="password" class="form-control"
                                                name="password_confirmation" required
                                                placeholder="Confirm password">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('superadmin.schools.show', $school) }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-1"></i>Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-user-plus me-1"></i>Create User
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