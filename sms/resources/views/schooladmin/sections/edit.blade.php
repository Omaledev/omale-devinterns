@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Edit Section</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('schooladmin.sections.update', $section) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="class_level_id" class="form-label">Class Level *</label>
                                    <select class="form-select @error('class_level_id') is-invalid @enderror"
                                            id="class_level_id" name="class_level_id" required>
                                        <option value="">Select Class Level</option>
                                        @foreach($classLevels as $classLevel)
                                            <option value="{{ $classLevel->id }}"
                                                {{ old('class_level_id', $section->class_level_id) == $classLevel->id ? 'selected' : '' }}>
                                                {{ $classLevel->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_level_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Section Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $section->name) }}"
                                           placeholder="e.g., A, B, Science" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="capacity" class="form-label">Capacity</label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                           id="capacity" name="capacity" value="{{ old('capacity', $section->capacity) }}"
                                           placeholder="e.g., 30" min="1">
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                       value="1" {{ old('is_active', $section->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Section
                                </label>
                            </div>
                            <small class="form-text text-muted">Inactive sections won't be available for new student assignments</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('schooladmin.sections.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back to List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Section
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
