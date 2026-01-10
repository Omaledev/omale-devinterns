@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        
        {{-- Sidebar --}}
        @include('schooladmin.partials.sidebar')

        {{-- Main Content Area --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('schooladmin.class-levels.index') }}">Class Levels</a></li>
                        <li class="breadcrumb-item active">Edit Class Level</li>
                    </ol>
                </nav>
            </div>

            {{-- Form Content --}}
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                Edit Class Level: {{ $classLevel->name }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('schooladmin.class-levels.update', $classLevel) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Class Level Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   id="name" name="name" value="{{ old('name', $classLevel->name) }}"
                                                   placeholder="e.g., Grade 1, Form 1" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="order" class="form-label">Order Sequence <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('order') is-invalid @enderror"
                                                   id="order" name="order" value="{{ old('order', $classLevel->order) }}"
                                                   placeholder="e.g., 1, 2, 3" min="1" required>
                                            <small class="text-muted" style="font-size: 0.8em;">Determines the sorting order in lists.</small>
                                            @error('order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="3"
                                              placeholder="Optional description for this class level">{{ old('description', $classLevel->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                               value="1" {{ old('is_active', $classLevel->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="is_active">
                                            Active Status
                                        </label>
                                    </div>
                                    <small class="text-muted">Inactive class levels cannot be assigned to students.</small>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <a href="{{ route('schooladmin.class-levels.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        Update Class Level
                                    </button>
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