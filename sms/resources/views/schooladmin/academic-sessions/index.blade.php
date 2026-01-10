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
                    <h1 class="h2">Academic Sessions</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Academic Sessions</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('schooladmin.academic-sessions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Add New Session
                    </a>
                </div>
            </div>

            <!-- Current Session Info -->
            @if($activeSession)
                <div class="alert alert-info d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Current Active Session:</strong> 
                        {{ $activeSession->name }} 
                        ({{ $activeSession->start_date->format('M d, Y') }} - {{ $activeSession->end_date->format('M d, Y') }})
                    </div>
                    <span class="badge bg-success">Active</span>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No active academic session set. Please activate a session.
                </div>
            @endif

            <!-- Sessions Table -->
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        All Academic Sessions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Session Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sessions as $session)
                                    <tr>
                                        <td>
                                            <strong>{{ $session->name }}</strong>
                                            @if($session->is_active)
                                                <i class="fas fa-star text-warning ms-1" title="Active"></i>
                                            @endif
                                        </td>
                                        <td>{{ $session->start_date->format('M d, Y') }}</td>
                                        <td>{{ $session->end_date->format('M d, Y') }}</td>
                                        <td>
                                            @if($session->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if(!$session->is_active)
                                                    <form action="{{ route('schooladmin.academic-sessions.activate', $session) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success btn-sm">
                                                            <i class="fas fa-check me-1"></i>Activate
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <a href="{{ route('schooladmin.academic-sessions.edit', $session) }}"
                                                   class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                @if(!$session->is_active)
                                                    <form action="{{ route('schooladmin.academic-sessions.destroy', $session) }}"
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Delete this session?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                                                <h5>No Academic Sessions Found</h5>
                                                <p>Get started by creating your first academic session.</p>
                                                <a href="{{ route('schooladmin.academic-sessions.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus me-1"></i>Create Session
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection