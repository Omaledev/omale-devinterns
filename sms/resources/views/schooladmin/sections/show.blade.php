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
                    <h1 class="h2">Section Details</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.sections.index') }}">Sections</a></li>
                            <li class="breadcrumb-item active">Section Details</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group">
                        <a href="{{ route('schooladmin.sections.edit', $section) }}" class="btn btn-outline-warning">
                            Edit
                        </a>
                        <a href="{{ route('schooladmin.sections.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Sections
                        </a>
                    </div>
                </div>
            </div>

            <!-- Section Details -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                Section Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">Basic Information</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Section Name:</td>
                                            <td>{{ $section->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Class Level:</td>
                                            <td>{{ $section->classLevel->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Capacity:</td>
                                            <td>
                                                @if($section->capacity)
                                                    @php
                                                        $studentCount = $section->studentProfiles->count();
                                                        $percentage = $studentCount > 0 ? round(($studentCount / $section->capacity) * 100) : 0;
                                                    @endphp
                                                    {{ $studentCount }}/{{ $section->capacity }} ({{ $percentage }}%)
                                                @else
                                                    {{ $section->studentProfiles->count() }} students
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Status:</td>
                                            <td>
                                                <span class="badge bg-{{ $section->is_active ? 'success' : 'secondary' }}">
                                                    {{ $section->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">Students in this Section</h5>
                                    @if($section->studentProfiles->count() > 0)
                                        <div class="list-group">
                                            @foreach($section->studentProfiles as $studentProfile)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $studentProfile->user->name }}</h6>
                                                    <small class="text-muted">Admission: {{ $studentProfile->student_id ?? 'N/A' }}</small>
                                                </div>
                                                <span class="badge bg-primary rounded-pill">
                                                    {{ $studentProfile->classLevel->name ?? 'N/A' }}
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