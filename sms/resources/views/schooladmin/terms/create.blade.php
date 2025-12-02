@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('schooladmin.partials.sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">Create Academic Term</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.terms.index') }}">Terms</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-calendar-plus me-2"></i>New Academic Term
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('schooladmin.terms.store') }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="academic_session_id" class="form-label">Academic Session *</label>
                                    <select class="form-select @error('academic_session_id') is-invalid @enderror" 
                                            id="academic_session_id" name="academic_session_id" required>
                                        <option value="">Select Academic Session</option>
                                        @foreach($sessions as $session)
                                            <option value="{{ $session->id }}" {{ old('academic_session_id') == $session->id ? 'selected' : '' }}>
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
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="e.g., First Term, Second Term, Third Term" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="start_date" class="form-label">Start Date *</label>
                                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                                   id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label">End Date *</label>
                                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                                   id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('schooladmin.terms.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Back
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Create Term
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
                                <i class="fas fa-info-circle me-2"></i>Term Guidelines
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled small">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Only one term can be active at a time</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Start date must be before end date</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Term dates should fall within the selected session</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Common terms: First, Second, Third Term</li>
                                <li><i class="fas fa-check text-success me-2"></i>Activate a term to make it current</li>
                            </ul>
                            
                            @if($activeSession)
                                <div class="alert alert-info mt-3">
                                    <strong>Active Session:</strong> {{ $activeSession->name }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection