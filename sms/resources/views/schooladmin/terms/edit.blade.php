@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('schooladmin.partials.sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">Edit Academic Term</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.terms.index') }}">Terms</a></li>
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
                                Edit Academic Term
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('schooladmin.terms.update', $term) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="academic_session_id" class="form-label">Academic Session *</label>
                                    <select class="form-select @error('academic_session_id') is-invalid @enderror" 
                                            id="academic_session_id" name="academic_session_id" required
                                            @if($term->is_active) disabled @endif>
                                        <option value="">Select Academic Session</option>
                                        @foreach($sessions as $session)
                                            <option value="{{ $session->id }}" 
                                                {{ old('academic_session_id', $term->academic_session_id) == $session->id ? 'selected' : '' }}>
                                               {{ $session->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('academic_session_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Term Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $term->name) }}" 
                                           placeholder="e.g., First Term, Second Term, Third Term" required
                                           @if($term->is_active) disabled @endif>
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
                                                   value="{{ old('start_date', $term->start_date->format('Y-m-d')) }}" required
                                                   @if($term->is_active) disabled @endif>
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
                                                   value="{{ old('end_date', $term->end_date->format('Y-m-d')) }}" required
                                                   @if($term->is_active) disabled @endif>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                @if($term->is_active)
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        This term is currently active. You cannot edit active terms.
                                    </div>
                                @endif
                                
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('schooladmin.terms.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Back
                                    </a>
                                    <button type="submit" class="btn btn-primary" @if($term->is_active) disabled @endif>
                                        Update Term
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
                                Term Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled small">
                                <li class="mb-2"><strong>Status:</strong> 
                                    @if($term->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </li>
                                <li class="mb-2"><strong>Academic Session:</strong> 
                                    @if($term->academicSession)
                                        {{ $term->academicSession->name }}
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </li>
                                <li class="mb-2"><strong>Created:</strong> {{ $term->created_at->format('M d, Y') }}</li>
                                <li class="mb-2"><strong>Last Updated:</strong> {{ $term->updated_at->format('M d, Y') }}</li>
                                <li class="mb-2"><strong>Duration:</strong> {{ $term->start_date->diffInDays($term->end_date) }} days</li>
                                <li>
                                    @if(!$term->is_active)
                                        <form action="{{ route('schooladmin.terms.activate', $term) }}" 
                                              method="POST" class="mt-3">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm w-100">
                                                <i class="fas fa-check me-1"></i>Activate This Term
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