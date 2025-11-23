@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Subject Details</h3>
                    <div class="btn-group">
                        <a href="{{ route('schooladmin.subjects.edit', $subject) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('schooladmin.subjects.index') }}" class="btn btn-secondary">
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
                                    <th width="40%">Subject Name:</th>
                                    <td>{{ $subject->name }}</td>
                                </tr>
                                <tr>
                                    <th>Subject Code:</th>
                                    <td>{{ $subject->code }}</td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $subject->description ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $subject->is_active ? 'success' : 'secondary' }}">
                                            {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Teacher Assignments</h5>
                            @if($subject->classroomAssignments->count() > 0)
                                <div class="list-group">
                                    @foreach($subject->classroomAssignments as $assignment)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $assignment->teacher->user->name ?? 'N/A' }}</h6>
                                                <small class="text-muted">
                                                    {{ $assignment->classLevel->name }}
                                                    @if($assignment->section)
                                                        - {{ $assignment->section->name }}
                                                    @endif
                                                </small>
                                            </div>
                                            <span class="badge bg-{{ $assignment->is_active ? 'success' : 'secondary' }}">
                                                {{ $assignment->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No teachers assigned to this subject yet.
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
