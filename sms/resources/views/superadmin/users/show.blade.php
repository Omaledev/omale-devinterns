@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        
        {{--  Sidebar --}}
        @include('superadmin.partials.sidebar')

        {{--  Main Content Area --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            {{-- User Details Card --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <h3 class="mb-3 mb-md-0 h4">
                            User Details
                        </h3>
                        <div class="d-flex gap-2">
                            <a href="{{ route('superadmin.users.edit', $user->id) }}" class="btn btn-warning">
                                 Edit
                            </a>
                            <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Users
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        {{-- Left Column: Avatar & Status --}}
                        <div class="col-md-4 text-center border-end-md">
                            <div class="mb-4 mt-3">
                                <div class="avatar-placeholder bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" 
                                     style="width: 120px; height: 120px; font-size: 48px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <h4 class="mt-3 fw-bold">{{ $user->name }}</h4>
                                <span class="badge bg-{{ $user->status == 'active' ? 'success' : ($user->status == 'inactive' ? 'warning' : 'danger') }} px-3 py-2 rounded-pill">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- Right Column: Details --}}
                        <div class="col-md-8 ps-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small text-uppercase fw-bold">Email Address</label>
                                        <p class="form-control-plaintext text-dark fw-medium">{{ $user->email }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small text-uppercase fw-bold">Phone Number</label>
                                        <p class="form-control-plaintext text-dark fw-medium">{{ $user->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small text-uppercase fw-bold">School</label>
                                        <p class="form-control-plaintext">
                                            @if($user->school)
                                                <a href="{{ route('superadmin.schools.show', $user->school_id) }}" class="text-decoration-none fw-bold">
                                                    {{ $user->school->name }}
                                                </a>
                                            @else
                                                <span class="text-muted fst-italic">Not Assigned</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small text-uppercase fw-bold">Role</label>
                                        <p class="form-control-plaintext">
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-primary">{{ $role->name }}</span>
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small text-uppercase fw-bold">Address</label>
                                <p class="form-control-plaintext text-dark fw-medium">{{ $user->address ?? 'N/A' }}</p>
                            </div>
                            
                            <hr class="my-4">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small text-uppercase fw-bold">Created At</label>
                                        <p class="form-control-plaintext text-dark">{{ $user->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small text-uppercase fw-bold">Last Updated</label>
                                        <p class="form-control-plaintext text-dark">{{ $user->updated_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($user->last_login_at)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small text-uppercase fw-bold">Last Login</label>
                                            <p class="form-control-plaintext text-dark">{{ $user->last_login_at->format('M d, Y h:i A') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small text-uppercase fw-bold">IP Address</label>
                                            <p class="form-control-plaintext text-dark">{{ $user->last_login_ip ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($user->notes)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-light border">
                                    <h6 class="alert-heading fw-bold">Additional Notes</h6>
                                    <hr>
                                    <p class="mb-0">{{ $user->notes }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </main>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Styling for the Avatar Circle */
    .avatar-placeholder {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #0d6efd; /* Bootstrap Primary */
        color: white;
        font-weight: bold;
        border: 4px solid #f8f9fa; /* White border effect */
    }

    /* Styling for the read-only text fields */
    .form-control-plaintext {
        padding-top: 0;
        padding-bottom: 0;
        margin-bottom: 0;
        line-height: 1.5;
    }

    /* Vertical divider line on larger screens */
    @media (min-width: 768px) {
        .border-end-md {
            border-right: 1px solid #dee2e6;
        }
    }
</style>
@endsection