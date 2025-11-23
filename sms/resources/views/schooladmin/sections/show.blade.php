@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Section Details</h3>
                    <div class="btn-group">
                        <a href="{{ route('schooladmin.sections.edit', $section) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('schooladmin.sections.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Basic Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Section Name:</th>
                                    <td>{{ $section->name }}</td>
                                </tr>
                                <tr>
                                    <th>Class Level:</th>
                                    <td>{{ $section->classLevel->name }}</td>
                                </tr>
                                <tr>
                                    <th>Capacity:</th>
                                    <td>
                                        @if($section->capacity)
                                            {{ $section->students->count() }}/{{ $section->capacity }}
                                            ({{ round(($section->students->count() / $section->capacity) * 100) }}%)
                                        @else
                                            Unlimited
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $section->is_active ? 'success' : 'secondary' }}">
                                            {{ $section->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Students in this Section</h5>
                            @if($section->students->count() > 0)
                                <div class="list-group">
                                    @foreach($section->students as $student)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $student->user->name }}</h6>
                                            <small class="text-muted">Student ID: {{ $student->student_id }}</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">
                                            {{ $student->classLevel->name }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No students assigned to this section yet.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
