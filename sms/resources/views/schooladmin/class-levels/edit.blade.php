@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Edit Class Level</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('schooladmin.class-levels.update', $classLevel) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Class Level Name *</label>
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
                                    <label for="order" class="form-label">Order *</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror"
                                           id="order" name="order" value="{{ old('order', $classLevel->order) }}"
                                           placeholder="e.g., 1, 2, 3" min="1" required>
                                    <small class="form-text text-muted">Used for sorting class levels</small>
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
                                      placeholder="Optional class level description">{{ old('description', $classLevel->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                       value="1" {{ old('is_active', $classLevel->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Class Level
                                </label>
                            </div>
                            <small class="form-text text-muted">Inactive class levels won't be available for new assignments</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('schooladmin.class-levels.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back to List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Class Level
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
