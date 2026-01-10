@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
       @include('schooladmin.partials.sidebar')

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
                        New Assignment
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
                                Create First Assignment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>
@endsection