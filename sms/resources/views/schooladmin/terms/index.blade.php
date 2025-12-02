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
                    <h1 class="h2">Academic Terms</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Terms</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('schooladmin.terms.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Add New Term
                    </a>
                </div>
            </div>

            <!-- Current Term Info -->
            @if($activeTerm)
                <div class="alert alert-success d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Current Active Term:</strong> 
                        {{ $activeTerm->name }} 
                        ({{ $activeTerm->start_date->format('M d, Y') }} - {{ $activeTerm->end_date->format('M d, Y') }})
                        @if($activeTerm->academicSession)
                            <span class="text-muted">• Session: {{ $activeTerm->academicSession->name }}</span>
                        @endif
                    </div>
                    <span class="badge bg-success">Active</span>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No active term set. Please activate a term.
                </div>
            @endif

            <!-- Terms Table -->
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-calendar-week me-2"></i>All Academic Terms
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Term Name</th>
                                    <th>Academic Session</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($terms as $term)
                                    <tr>
                                        <td>
                                            <strong>{{ $term->name }}</strong>
                                            @if($term->is_active)
                                                <i class="fas fa-star text-warning ms-1" title="Active"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($term->academicSession)
                                                <span class="badge bg-info">{{ $term->academicSession->name }}</span>
                                            @else
                                                <span class="text-muted">No Session</span>
                                            @endif
                                        </td>
                                        <td>{{ $term->start_date->format('M d, Y') }}</td>
                                        <td>{{ $term->end_date->format('M d, Y') }}</td>
                                        <td>
                                            @if($term->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if(!$term->is_active)
                                                    <form action="{{ route('schooladmin.terms.activate', $term) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success btn-sm">
                                                            <i class="fas fa-check me-1"></i>Activate
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <a href="{{ route('schooladmin.terms.edit', $term) }}"
                                                   class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                @if(!$term->is_active)
                                                    <form action="{{ route('schooladmin.terms.destroy', $term) }}"
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Delete this term?')">
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
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-calendar-week fa-3x mb-3"></i>
                                                <h5>No Academic Terms Found</h5>
                                                <p>Get started by creating your first academic term.</p>
                                                <a href="{{ route('schooladmin.terms.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus me-1"></i>Create Term
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