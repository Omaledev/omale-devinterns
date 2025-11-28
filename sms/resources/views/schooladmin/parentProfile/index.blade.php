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
                            <span class="badge bg-info float-end">{{ $parents->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.bursars.index') }}">
                            <i class="fas fa-users me-2"></i>
                            Bursars
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.class-levels.index') }}">
                            <i class="fas fa-door-open me-2"></i>
                            Classes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.sections.index') }}">
                            <i class="fas fa-layer-group me-2"></i>
                            Sections
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('schooladmin.subjects.index') }}">
                            <i class="fas fa-book me-2"></i>
                            Subjects
                        </a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-calendar-check me-2"></i>
                            Attendance
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-money-bill-wave me-2"></i>
                            Fees
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-bullhorn me-2"></i>
                            Notice
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-bus me-2"></i>
                            Transport
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-bed me-2"></i>
                            Hostel
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
                    <h1 class="h2">Parent Management</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Parents</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('schooladmin.parents.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i>Add New Parent
                    </a>
                </div>
            </div>

            <!-- Parents Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-users me-2"></i>All Parents
                            </h6>
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control form-control-sm" placeholder="Search parents..." id="searchInput">
                                <span class="badge bg-info align-self-center">{{ $parents->count() }} parents</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Children</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($parents as $parent)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-2"
                                                             style="width: 35px; height: 35px;">
                                                            <span class="text-white fw-bold small">
                                                                {{ substr($parent->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $parent->name }}</div>
                                                            <small class="text-muted">Registered: {{ $parent->created_at->format('M Y') }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $parent->email }}</td>
                                                <td>{{ $parent->phone ?? 'N/A' }}</td>
                                                <td>
                                                    <small class="text-muted">{{ Str::limit($parent->address, 30) ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    @if($parent->children && $parent->children->count() > 0)
                                                        @foreach($parent->children->take(2) as $child)
                                                            <span class="badge bg-primary mb-1">{{ $child->name }}</span>
                                                        @endforeach
                                                        @if($parent->children->count() > 2)
                                                            <span class="badge bg-secondary">+{{ $parent->children->count() - 2 }} more</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">No children</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($parent->is_approved)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-warning">Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('schooladmin.parents.show', $parent) }}"
                                                           class="btn btn-outline-info"
                                                           data-bs-toggle="tooltip"
                                                           title="View Parent">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('schooladmin.parents.edit', $parent) }}"
                                                           class="btn btn-outline-warning"
                                                           data-bs-toggle="tooltip"
                                                           title="Edit Parent">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('schooladmin.parents.destroy', $parent) }}"
                                                              method="POST"
                                                              class="d-inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this parent?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="btn btn-outline-danger"
                                                                    data-bs-toggle="tooltip"
                                                                    title="Delete Parent">
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
                                                        <i class="fas fa-users fa-3x mb-3"></i>
                                                        <h5>No Parents Found</h5>
                                                        <p>Get started by adding your first parent.</p>
                                                        <a href="{{ route('schooladmin.parents.create') }}" class="btn btn-primary">
                                                            <i class="fas fa-user-plus me-1"></i>Add Parent
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

        // Simple search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }
    });
</script>
@endpush