@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
            <div class="position-sticky pt-3">
                <div class="text-center mb-4">
                    <div class="bg-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                        <i class="fas fa-crown text-dark fa-xl"></i>
                    </div>
                    <h6 class="text-white mb-1">SuperAdmin</h6>
                    <small class="text-white-50">System Administrator</small>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('superadmin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white" href="{{ route('superadmin.schools.index') }}">
                            <i class="fas fa-school me-2"></i>
                            Schools
                            <span class="badge bg-primary float-end">{{ $stats['total_schools'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{route('superadmin.users.index')}}">
                            <i class="fas fa-users me-2"></i>
                            All Users
                            <span class="badge bg-info float-end">{{ $stats['total_users'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link text-white-50" href="{{ route('superadmin.schools.index')}}">
                        <i class="fas fa-user-shield me-2"></i>
                        Roles & Permissions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-chart-bar me-2"></i>
                            System Analytics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-cog me-2"></i>
                            System Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-file-invoice me-2"></i>
                            Billing
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-bell me-2"></i>
                            Notifications
                            <span class="badge bg-danger float-end">{{ $stats['system_alerts'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-database me-2"></i>
                            Backup & Restore
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-life-ring me-2"></i>
                            Support
                        </a>
                    </li>
                </ul>
            </div>
        </div>

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