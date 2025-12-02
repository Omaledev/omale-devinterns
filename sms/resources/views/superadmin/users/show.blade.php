@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>User Details</h3>
                        <div>
                            <a href="{{ route('superadmin.users.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                            <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">Back to Users</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="mb-4">
                                <div class="avatar-placeholder bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; font-size: 48px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <h4 class="mt-3">{{ $user->name }}</h4>
                                <span class="badge bg-{{ $user->status == 'active' ? 'success' : ($user->status == 'inactive' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Email Address</label>
                                        <p class="form-control-plaintext">{{ $user->email }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Phone Number</label>
                                        <p class="form-control-plaintext">{{ $user->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">School</label>
                                        <p class="form-control-plaintext">
                                            @if($user->school)
                                                <a href="{{ route('superadmin.schools.show', $user->school_id) }}" class="text-decoration-none">
                                                    {{ $user->school->name }}
                                                </a>
                                            @else
                                                Not Assigned
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Role</label>
                                        <p class="form-control-plaintext">
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-primary">{{ $role->name }}</span>
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Address</label>
                                <p class="form-control-plaintext">{{ $user->address ?? 'N/A' }}</p>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Created At</label>
                                        <p class="form-control-plaintext">{{ $user->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Last Updated</label>
                                        <p class="form-control-plaintext">{{ $user->updated_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($user->last_login_at)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Last Login</label>
                                            <p class="form-control-plaintext">{{ $user->last_login_at->format('M d, Y h:i A') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted">IP Address</label>
                                            <p class="form-control-plaintext">{{ $user->last_login_ip ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($user->notes)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Additional Notes</h5>
                                    </div>
                                    <div class="card-body">
                                        <p>{{ $user->notes }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .avatar-placeholder {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #007bff;
        color: white;
        font-weight: bold;
    }
    .form-control-plaintext {
        min-height: 38px;
        padding: 0.375rem 0;
        border-bottom: 1px solid #dee2e6;
    }
</style>
@endsection