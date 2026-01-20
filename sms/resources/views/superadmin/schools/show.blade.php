@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        @include('superadmin.partials.sidebar')

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Header -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">School Details</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('superadmin.schools.index') }}">Schools</a></li>
                            <li class="breadcrumb-item active">{{ $school->name }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('superadmin.schools.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Schools
                    </a>
                </div>
            </div>

            <!-- School Details -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-school me-2"></i>{{ $school->name }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">Basic Information</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">School ID:</td>
                                            <td>{{ $school->id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">School Name:</td>
                                            <td>{{ $school->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Email:</td>
                                            <td>{{ $school->email }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Phone:</td>
                                            <td>{{ $school->phone ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Principal:</td>
                                            <td>{{ $school->principal_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Created:</td>
                                            <td>{{ $school->created_at->format('M d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Last Updated:</td>
                                            <td>{{ $school->updated_at->format('M d, Y') }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">Address Information</h5>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <p class="mb-0">{{ $school->address }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <h5 class="text-primary mb-3">User Management</h5>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <a href="{{ route('superadmin.schools.create-user', $school) }}" class="btn btn-success w-100">
                                                    <i class="fas fa-user-plus me-2"></i>Create School User
                                                </a>
                                            </div>
                                            <div class="col-12">
                                                <a href="{{ route('superadmin.schools.users', $school) }}" class="btn btn-info w-100">
                                                    <i class="fas fa-users me-2"></i>View School Users
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- School Actions -->
                            <div class="row mt-4 pt-3 border-top">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">School Actions</h5>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ route('superadmin.schools.edit', $school) }}" class="btn btn-warning">
                                            <i class="fas fa-edit me-1"></i>Edit School
                                        </a>
                                        <form action="{{ route('superadmin.schools.destroy', $school) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this school? This action cannot be undone.')">
                                                <i class="fas fa-trash me-1"></i>Delete School
                                            </button>
                                        </form>
                                        <a href="{{ route('superadmin.schools.index') }}" class="btn btn-primary">
                                            <i class="fas fa-list me-1"></i>View All Schools
                                        </a>
                                    </div>
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

    .btn-group .btn {
        margin: 0 2px;
    }
</style>
@endpush