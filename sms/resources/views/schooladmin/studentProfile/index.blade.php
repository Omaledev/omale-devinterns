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
                    <h1 class="h2">Student Management</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Students</li>
                        </ol>
                    </nav>
                </div>
                <div class="row g-2 mb-2 mb-md-0">
                    <!-- Export Button -->
                    <div class="col-6 col-sm-4 col-md-auto">
                        <a href="{{ route('schooladmin.students.export') }}" class="btn btn-success btn-sm w-100 text-nowrap">
                            <i class="fas fa-download me-1"></i>
                            <span class="d-none d-md-inline">Export Students</span>
                            <span class="d-inline d-md-none">Export</span>
                        </a>
                    </div>
                    
                    <!-- Import Button -->
                    <div class="col-6 col-sm-4 col-md-auto">
                        <button type="button" class="btn btn-info btn-sm w-100 text-nowrap" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="fas fa-upload me-1"></i>
                            <span class="d-none d-md-inline">Import Students</span>
                            <span class="d-inline d-md-none">Import</span>
                        </button>
                    </div>
                    
                    <!-- Add Student Button -->
                    <div class="col-12 col-sm-4 col-md-auto">
                        <a href="{{ route('schooladmin.students.create') }}" class="btn btn-primary btn-sm w-100 text-nowrap">
                            <i class="fas fa-user-plus me-1"></i>
                            <span class="d-none d-md-inline">Add New Student</span>
                            <span class="d-inline d-md-none">Add Student</span>
                        </a>
                    </div>
                </div>
            </div>
            

            <!-- Students Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-primary">
                                All Students
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
                                                    @if($student->admission_number)
                                                        <strong>{{ $student->admission_number }}</strong>
                                                    @else
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="fas fa-exclamation-circle me-1"></i>Pending ID
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                      <div class="d-flex align-items-center">
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                            style="width: 35px; height: 35px;">
                                                            <span class="text-white fw-bold small">
                                                                {{ substr($student->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $student->name }}</div>
                                                            <small class="text-muted">Joined: {{ $student->created_at->format('M Y') }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($student->studentProfile && $student->studentProfile->classLevel)
                                                        
                                                        <span class="badge bg-info">
                                                            {{ $student->studentProfile->classLevel->name }}
                                                        </span>

                                                        {{-- Check if section exists --}}
                                                        @if($student->studentProfile->section)
                                                            <small class="text-muted">
                                                                ({{ $student->studentProfile->section->name }})
                                                            </small>
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
            <!-- Import Students Modal -->
            <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="importModalLabel">
                                <i class="fas fa-upload me-2"></i>Import Students from Excel
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('schooladmin.students.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <!-- Success Message -->
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <!-- Error Message -->
                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        {!! session('error') !!}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label for="file" class="form-label">Select Excel/CSV File</label>
                                    <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                                    <div class="form-text">
                                        Supported formats: .xlsx, .xls, .csv (Max: 10MB)
                                    </div>
                                </div>

                                <!-- Download Template -->
                                <div class="mb-3">
                                    <a href="{{ route('schooladmin.students.download-template') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-file-download me-1"></i>Download Template
                                    </a>
                                    <small class="text-muted ms-2">Use our template for correct formatting</small>
                                </div>

                                <!-- Required Columns Info -->
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">Required Columns:</h6>
                                    <ul class="mb-0 small">
                                        <li><strong>email</strong> - Student's email (must be unique)</li>
                                        <li><strong>name</strong> - Student's full name</li>
                                        <li><strong>class_level_id</strong> - Class ID number</li>
                                        <li><strong>student_id</strong> - School admission number</li>
                                        <li><strong>Optional:</strong> admission_date, date_of_birth, gender, contact, address</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload me-1"></i>Import Students
                                </button>
                            </div>
                        </form>
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
