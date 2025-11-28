@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
            <div class="position-sticky pt-3">
                <div class="text-center mb-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                        style="width: 60px; height: 60px;">
                        <span class="text-white fw-bold fs-4">A</span>
                    </div>
                    <h6 class="text-white mb-1">{{ auth()->user()->school->name }}</h6>
                    <small class="text-white-50">School Admin</small>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.teachers.index') }}">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Teachers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white" href="{{ route('schooladmin.teacher-assignments.index') }}">
                            <i class="fas fa-link me-2"></i>Teacher Assignments
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">Assign Teachers to Subjects</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Teacher Assignments</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('schooladmin.teacher-assignments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>New Assignment
                    </a>
                </div>
            </div>
            <div class="card shadow">
                <div class="card-body">
                    @if($assignments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Teacher</th>
                                        <th>Subject</th>
                                        <th>Class</th>
                                        <th>Section</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignments as $assignment)
                                        <tr>
                                            <td>
                                                {{ $assignment->teacher->user->name ?? 'N/A' }}
                                                @if($assignment->teacher->employee_id)
                                                    <br><small class="text-muted">{{ $assignment->teacher->employee_id }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $assignment->subject->name ?? 'N/A' }}</td>
                                            <td>{{ $assignment->classLevel->name ?? 'N/A' }}</td>
                                            <td>{{ $assignment->section->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge {{ $assignment->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $assignment->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('schooladmin.teacher-assignments.show', $assignment) }}" 
                                                       class="btn btn-sm btn-outline-primary">View</a>
                                                    <a href="{{ route('schooladmin.teacher-assignments.edit', $assignment) }}" 
                                                       class="btn btn-sm btn-outline-secondary">Edit</a>
                                                    <form action="{{ route('schooladmin.teacher-assignments.destroy', $assignment) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                onclick="return confirm('Are you sure?')">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-link fa-3x text-muted mb-3"></i>
                            <h5>No Teacher Assignments Found</h5>
                            <p class="text-muted">Start by creating your first teacher assignment.</p>
                            <a href="{{ route('schooladmin.teacher-assignments.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Create First Assignment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>
@endsection