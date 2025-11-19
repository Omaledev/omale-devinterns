{{-- @extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">Student Management</h1>
                <a href="{{ route('schooladmin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Students</h5>
                    <a href="{{ route('schooladmin.students.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Student
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Admission No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td>{{ $student->admission_number ?? 'N/A' }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>
                                            @if($student->is_approved)
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('schooladmin.students.show', $student) }}" class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('schooladmin.students.edit', $student) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('schooladmin.students.destroy', $student) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No students found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}

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
                        <a class="nav-link active text-white" href="{{ route('schooladmin.students.index') }}">
                            <i class="fas fa-user-graduate me-2"></i>
                            Students
                            <span class="badge bg-primary float-end">{{ $students->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            Teachers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-users me-2"></i>
                            Parents
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-door-open me-2"></i>
                            Classes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
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
                    <h1 class="h2">Student Management</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Students</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('schooladmin.students.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i>Add New Student
                    </a>
                </div>
            </div>

            <!-- Students Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-user-graduate me-2"></i>All Students
                            </h6>
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control form-control-sm" placeholder="Search students..." id="searchInput">
                                <span class="badge bg-primary align-self-center">{{ $students->count() }} students</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Admission No.</th>
                                            <th>Name</th>
                                            <th>Class</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($students as $student)
                                            <tr>
                                                <td>
                                                    <strong>{{ $student->admission_number ?? 'N/A' }}</strong>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                             style="width: 35px; height: 35px;">
                                                            <span class="text-white fw-bold small">
                                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $student->first_name }} {{ $student->last_name }}</div>
                                                            <small class="text-muted">Roll: {{ $student->roll_number ?? 'N/A' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($student->class)
                                                        <span class="badge bg-info">{{ $student->class }}</span>
                                                        @if($student->section)
                                                            <small class="text-muted">({{ $student->section }})</small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Not assigned</span>
                                                    @endif
                                                </td>
                                                <td>{{ $student->email }}</td>
                                                <td>{{ $student->phone ?? 'N/A' }}</td>
                                                <td>
                                                    @if($student->is_active)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('schooladmin.students.show', $student) }}"
                                                           class="btn btn-outline-info"
                                                           data-bs-toggle="tooltip"
                                                           title="View Student">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('schooladmin.students.edit', $student) }}"
                                                           class="btn btn-outline-warning"
                                                           data-bs-toggle="tooltip"
                                                           title="Edit Student">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('schooladmin.students.destroy', $student) }}"
                                                              method="POST"
                                                              class="d-inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this student?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="btn btn-outline-danger"
                                                                    data-bs-toggle="tooltip"
                                                                    title="Delete Student">
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
                                                        <i class="fas fa-user-graduate fa-3x mb-3"></i>
                                                        <h5>No Students Found</h5>
                                                        <p>Get started by adding your first student.</p>
                                                        <a href="{{ route('schooladmin.students.create') }}" class="btn btn-primary">
                                                            <i class="fas fa-user-plus me-1"></i>Add Student
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            {{-- @if($students->hasPages())
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="text-muted">
                                        Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} entries
                                    </div>
                                    <nav>
                                        {{ $students->links() }}
                                    </nav>
                                </div>
                            @endif --}}
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
