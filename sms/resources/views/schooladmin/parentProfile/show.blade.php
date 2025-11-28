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
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.students.index') }}">
                            <i class="fas fa-user-graduate me-2"></i>
                            Students
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.teachers.index') }}">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            Teachers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white" href="{{ route('schooladmin.parents.index') }}">
                            <i class="fas fa-users me-2"></i>
                            Parents
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
                    <h1 class="h2">Parent Details</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.parents.index') }}">Parents</a></li>
                            <li class="breadcrumb-item active">Parent Details</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('schooladmin.parents.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>Back to Parents
                    </a>
                    <a href="{{ route('schooladmin.parents.edit', $parent) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                </div>
            </div>

            <!-- Parent Details -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-user me-2"></i>Parent Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Parent Profile -->
                                <div class="col-md-4 text-center mb-4">
                                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                         style="width: 100px; height: 100px;">
                                        <span class="text-white fw-bold fs-2">
                                            {{ substr($parent->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <h4>{{ $parent->name }}</h4>
                                    <p class="text-muted">Parent</p>
                                    <div class="mb-2">
                                        @if($parent->is_approved)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">
                                        Member since {{ $parent->created_at->format('M d, Y') }}
                                    </small>
                                </div>

                                <!-- Contact Information -->
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3">Contact Information</h6>
                                            <div class="mb-3">
                                                <strong>Email:</strong><br>
                                                <a href="mailto:{{ $parent->email }}">{{ $parent->email }}</a>
                                            </div>
                                            <div class="mb-3">
                                                <strong>Phone:</strong><br>
                                                {{ $parent->phone ?? 'N/A' }}
                                            </div>
                                            <div class="mb-3">
                                                <strong>Address:</strong><br>
                                                {{ $parent->address ?? 'N/A' }}
                                            </div>
                                        </div>

                                        <!-- Children Information -->
                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3">Children Information</h6>
                                            @if($parent->children && $parent->children->count() > 0)
                                                <div class="list-group">
                                                    @foreach($parent->children as $child)
                                                        <div class="list-group-item">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <strong>{{ $child->name }}</strong>
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        {{ $child->admission_number }} | 
                                                                        {{ $child->class ?? 'No Class' }}
                                                                    </small>
                                                                </div>
                                                                <span class="badge bg-info">{{ $child->section ?? 'No Section' }}</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    No children assigned to this parent.
                                                </div>
                                            @endif
                                        </div>
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