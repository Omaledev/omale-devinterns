@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('schooladmin.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">Teacher Management</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Teachers</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="{{ route('schooladmin.teacher-assignments.create') }}" class="btn btn-success me-3">
                            <i class="fas fa-link me-1"></i>Assign to Subjects
                        </a>
                        <a href="{{ route('schooladmin.teachers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Add New Teacher
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-primary">
                                All Teachers
                            </h6>
                            <div class="d-flex gap-2">
                                <form action="{{ route('schooladmin.teachers.index') }}" method="GET" class="d-flex gap-2">
                                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search teachers..." value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
                                </form>
                                <span class="badge bg-success align-self-center">{{ $teachers->total() }} teachers</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Employee ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Assigned Subjects/Classes</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($teachers as $teacher)
                                            <tr>
                                                <td>
                                                    @if($teacher->employee_id)
                                                        <strong>{{ $teacher->employee_id }}</strong>
                                                    @else
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="fas fa-exclamation-circle me-1"></i>Pending ID
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-2"
                                                             style="width: 35px; height: 35px;">
                                                            <span class="text-white fw-bold small">
                                                                {{ substr($teacher->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $teacher->name }}</div>
                                                            <small class="text-muted">Joined: {{ $teacher->created_at->format('M Y') }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $teacher->email }}</td>
                                                <td>{{ $teacher->phone ?? 'N/A' }}</td>
                                                <td>
                                                    @if($teacher->taughtClasses && $teacher->taughtClasses->count() > 0)
                                                        @foreach($teacher->taughtClasses->take(2) as $assignment)
                                                            <span class="badge bg-info mb-1">{{ $assignment->subject->code }}</span>
                                                        @endforeach
                                                        @if($teacher->taughtClasses->count() > 2)
                                                            <span class="badge bg-secondary">+{{ $teacher->taughtClasses->count() - 2 }} more</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Not assigned</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{-- Active Logic: Must be Approved AND Assigned to at least 1 class --}}
                                                    @if($teacher->is_approved && $teacher->taughtClasses->count() > 0)
                                                        <span class="badge bg-success">Active</span>
                                                    @elseif($teacher->is_approved)
                                                        <span class="badge bg-warning text-dark">Not Assigned</span>
                                                    @else
                                                        <span class="badge bg-secondary">Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('schooladmin.teachers.show', $teacher) }}"
                                                           class="btn btn-outline-info"
                                                           data-bs-toggle="tooltip"
                                                           title="View Teacher">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('schooladmin.teachers.edit', $teacher) }}"
                                                           class="btn btn-outline-warning"
                                                           data-bs-toggle="tooltip"
                                                           title="Edit Teacher">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('schooladmin.teachers.destroy', $teacher) }}"
                                                              method="POST"
                                                              class="d-inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this teacher?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="btn btn-outline-danger"
                                                                    data-bs-toggle="tooltip"
                                                                    title="Delete Teacher">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="fas fa-chalkboard-teacher fa-3x mb-3"></i>
                                                        <h5>No Teachers Found</h5>
                                                        <p>Get started by adding your first teacher.</p>
                                                        <a href="{{ route('schooladmin.teachers.create') }}" class="btn btn-primary">
                                                            <i class="fas fa-user-plus me-1"></i>Add Teacher
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-center mt-3">
                                {{ $teachers->withQueryString()->links() }}
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
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .btn-group .btn {
        border-radius: 0.375rem !important;
        margin: 0 1px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush