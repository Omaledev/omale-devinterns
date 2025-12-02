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
                    <h1 class="h2">Teacher Details</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.teachers.index') }}">Teachers</a></li>
                            <li class="breadcrumb-item active">Teacher Details</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group">
                        <a href="{{ route('schooladmin.teachers.edit', $teacher) }}" class="btn btn-outline-warning">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('schooladmin.teachers.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Teachers
                        </a>
                    </div>
                </div>
            </div>

            <!-- Teacher Details -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-user-tie me-2"></i>Teacher Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Teacher Profile -->
                                <div class="col-md-4 text-center mb-4">
                                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                         style="width: 100px; height: 100px;">
                                        <span class="text-white fw-bold fs-2">
                                            {{ substr($teacher->name, 0, 1) }}{{ substr($teacher->name, -1, 1) }}
                                        </span>
                                    </div>
                                    <h4 class="mb-1">{{ $teacher->name }}</h4>
                                    <p class="text-muted mb-2">Teacher</p>
                                    <div class="mb-2">
                                        @if($teacher->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">Member since {{ $teacher->created_at->format('M d, Y') }}</small>
                                </div>

                                <!-- Personal Information -->
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3">Personal Information</h6>
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td class="fw-bold" style="width: 40%;">Full Name:</td>
                                                    <td>{{ $teacher->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Email:</td>
                                                    <td>{{ $teacher->email }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Phone:</td>
                                                    <td>{{ $teacher->phone ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Status:</td>
                                                    <td>
                                                        @if($teacher->is_active)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-secondary">Inactive</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3">Professional Information</h6>
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td class="fw-bold" style="width: 40%;">Employee ID:</td>
                                                    <td>{{ $teacher->employee_id }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">School:</td>
                                                    <td>{{ $teacher->school->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Joined Date:</td>
                                                    <td>{{ $teacher->created_at->format('M d, Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Last Updated:</td>
                                                    <td>{{ $teacher->updated_at->format('M d, Y') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Address Information -->
                                    @if($teacher->teacherProfile && $teacher->teacherProfile->address)
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h6 class="text-primary mb-3">Address Information</h6>
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <p class="mb-0">{{ $teacher->teacherProfile->address }}</p>
                                                </div>
                                            </div>
                                        </div>
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