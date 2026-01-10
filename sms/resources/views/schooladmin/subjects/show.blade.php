@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        @include('schooladmin.partials.sidebar')

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Header -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">Subject Details</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.subjects.index') }}">Subjects</a></li>
                            <li class="breadcrumb-item active">Subject Details</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group">
                        <a href="{{ route('schooladmin.subjects.edit', $subject) }}" class="btn btn-outline-warning">
                            Edit
                        </a>
                        <a href="{{ route('schooladmin.subjects.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Subjects
                        </a>
                    </div>
                </div>
            </div>

            <!-- Subject Details -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                Subject Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">Basic Information</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Subject Name:</td>
                                            <td>{{ $subject->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Subject Code:</td>
                                            <td>{{ $subject->code }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Description:</td>
                                            <td>{{ $subject->description ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Status:</td>
                                            <td>
                                                <span class="badge bg-{{ $subject->is_active ? 'success' : 'secondary' }}">
                                                    {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">Teacher Assignments</h5>
                                    @if($subject->classroomAssignments && $subject->classroomAssignments->count() > 0)
                                        <div class="list-group">
                                            @foreach($subject->classroomAssignments as $assignment)
                                            <div class="list-group-item">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1">{{ $assignment->teacher->name ?? 'N/A' }}</h6>
                                                        <small class="text-muted">
                                                            {{ $assignment->classLevel->name ?? 'N/A' }}
                                                            @if($assignment->section)
                                                                - {{ $assignment->section->name }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                    <span class="badge bg-success">Active</span>
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
        </main>
    </div>
</div>
@endsection

@push('styles')
<style>
    .sidebar {
        min-height: calc(100vh - 56px);
        box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
    }

    .sidebar .nav-link {
        color: #adb5bd;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem;
        margin: 0.125rem 0.5rem;
        transition: all 0.15s ease;
    }

    .sidebar .nav-link:hover {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar .nav-link.active {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.2);
    }

    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
    }

    .card {
        border: none;
        border-radius: 0.5rem;
    }

    .table-borderless td {
        padding: 0.5rem 0.25rem;
        border: none;
    }
</style>
@endpush