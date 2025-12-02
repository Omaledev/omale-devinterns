@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('schooladmin.partials.sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">Edit Academic Session</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.academic-sessions.index') }}">Sessions</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-calendar-edit me-2"></i>Edit Academic Session
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('schooladmin.academic-sessions.update', $session) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Session Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $session->name) }}" 
                                           placeholder="e.g., 2024/2025 Academic Year" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="start_date" class="form-label">Start Date *</label>
                                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                                   id="start_date" name="start_date" 
                                                   value="{{ old('start_date', $session->start_date->format('Y-m-d')) }}" required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label">End Date *</label>
                                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                                   id="end_date" name="end_date" 
                                                   value="{{ old('end_date', $session->end_date->format('Y-m-d')) }}" required>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                @if($session->is_active)
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        This session is currently active. You cannot edit active sessions.
                                    </div>
                                @endif
                                
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('schooladmin.academic-sessions.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Back
                                    </a>
                                    <button type="submit" class="btn btn-primary" @if($session->is_active) disabled @endif>
                                        <i class="fas fa-save me-1"></i>Update Session
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-info-circle me-2"></i>Session Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled small">
                                <li class="mb-2"><strong>Status:</strong> 
                                    @if($session->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </li>
                                <li class="mb-2"><strong>Created:</strong> {{ $session->created_at->format('M d, Y') }}</li>
                                <li class="mb-2"><strong>Last Updated:</strong> {{ $session->updated_at->format('M d, Y') }}</li>
                                <li class="mb-2"><strong>Duration:</strong> {{ $session->start_date->diffInDays($session->end_date) }} days</li>
                                <li>
                                    @if(!$session->is_active)
                                        <form action="{{ route('schooladmin.academic-sessions.activate', $session) }}" 
                                              method="POST" class="mt-3">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm w-100">
                                                <i class="fas fa-check me-1"></i>Activate This Session
                                            </button>
                                        </form>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection