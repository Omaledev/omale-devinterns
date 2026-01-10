@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        
        {{-- Sidebar --}}
        @include('schooladmin.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
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
                        Edit
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                Parent Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center mb-4 border-end-md">
                                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 mt-3 shadow-sm"
                                         style="width: 100px; height: 100px;">
                                        <span class="text-white fw-bold display-4">
                                            {{ strtoupper(substr($parent->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <h4 class="fw-bold">{{ $parent->name }}</h4>
                                    <p class="text-muted mb-2">Parent / Guardian</p>
                                    <div class="mb-3">
                                        @if($parent->is_approved)
                                            <span class="badge bg-success px-3 py-2 rounded-pill">Active Account</span>
                                        @else
                                            <span class="badge bg-warning px-3 py-2 rounded-pill">Pending Approval</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">
                                        Joined {{ $parent->created_at->format('M d, Y') }}
                                    </small>
                                </div>

                                <div class="col-md-8 ps-md-4">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <h6 class="text-primary fw-bold text-uppercase small mb-3 border-bottom pb-2">Contact Information</h6>
                                            <div class="mb-3">
                                                <label class="text-muted small fw-bold">Email Address</label>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-envelope text-secondary me-2"></i>
                                                    <a href="mailto:{{ $parent->email }}" class="text-decoration-none">{{ $parent->email }}</a>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="text-muted small fw-bold">Phone Number</label>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-phone text-secondary me-2"></i>
                                                    <span>{{ $parent->phone ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="text-muted small fw-bold">Residential Address</label>
                                                <div class="d-flex align-items-start">
                                                    <i class="fas fa-map-marker-alt text-secondary me-2 mt-1"></i>
                                                    <span>{{ $parent->address ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <h6 class="text-primary fw-bold text-uppercase small mb-3 border-bottom pb-2">Children / Wards</h6>
                                            @if($parent->children && $parent->children->count() > 0)
                                                <div class="list-group list-group-flush">
                                                    @foreach($parent->children as $child)
                                                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <div class="fw-bold text-dark">{{ $child->name }}</div>
                                                                <small class="text-muted">
                                                                    ID: {{ $child->admission_number }}
                                                                </small>
                                                            </div>
                                                            <div class="text-end">
                                                                <span class="badge bg-info text-dark mb-1">{{ $child->class ?? 'No Class' }}</span>
                                                                <br>
                                                                <small class="text-muted">{{ $child->section ?? '' }}</small>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="alert alert-light border text-center text-muted">
                                                    <i class="fas fa-child fa-2x mb-2 d-block"></i>
                                                    No children linked yet.
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

@section('styles')
<style>
    @media (min-width: 768px) {
        .border-end-md {
            border-right: 1px solid #dee2e6;
        }
    }
</style>
@endsection