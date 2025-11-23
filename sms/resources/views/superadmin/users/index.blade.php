@extends('layouts.app')

@section('title', 'All Users')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        All Users Management
                       
                    </h5>
                    <span class="badge bg-info">{{ $users->count() }}</span>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="roleFilter" class="form-label">Filter by Role</label>
                            <select class="form-select" id="roleFilter">
                                <option value="">All Roles</option>
                                <option value="superadmin">Super Admin</option>
                                <option value="schooladmin">School Admin</option>
                                <option value="teacher">Teacher</option>
                                <option value="student">Student</option>
                                <option value="parent">Parent</option>
                                <option value="bursar">Bursar</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="schoolFilter" class="form-label">Filter by School</label>
                            <select class="form-select" id="schoolFilter">
                                <option value="">All Schools</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" placeholder="Search by name, email, phone...">
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2 mt-3 mt-md-0">
                            <button class="btn btn-outline-secondary h-auto" id="resetFilters">
                                <i class="fas fa-refresh me-1"></i> Reset
                            </button>
                            <a href="{{ route('superadmin.dashboard') }}" class="btn btn-outline-primary h-auto">
                                <i class="fas fa-arrow-left me-1"></i> Dashboard
                            </a>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="usersTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>School</th>
                                    <th>Registration Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title bg-primary rounded-circle text-white">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                                @if($user->id === auth()->id())
                                                    <span class="badge bg-info ms-1">You</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->phone)
                                            <span class="text-nowrap">{{ $user->phone }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $role = $user->roles->first();
                                            $roleName = $role ? $role->name : 'No Role';
                                            $roleColors = [
                                                'superadmin' => 'danger',
                                                'schooladmin' => 'warning',
                                                'teacher' => 'info',
                                                'student' => 'success',
                                                'parent' => 'primary',
                                                'bursar' => 'secondary'
                                            ];
                                            $roleColor = $roleColors[$roleName] ?? 'dark';
                                        @endphp
                                        <span class="badge bg-{{ $roleColor }}">{{ ucfirst($roleName) }}</span>
                                    </td>
                                    <td>
                                        @if($user->school)
                                            <span class="badge bg-light text-dark">{{ $user->school->name }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $user->created_at->format('M d, Y') }}<br>
                                            <span class="text-xs">{{ $user->created_at->format('h:i A') }}</span>
                                        </small>
                                    </td>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Verified
                                            </span>
                                        @else
                                            <span class="badge bg-dark text-white">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" 
                                                    data-bs-toggle="tooltip" 
                                                    title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if(!$user->hasRole('superadmin') && $user->id !== auth()->id())
                                            <button class="btn btn-outline-danger" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Delete User">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fas fa-users fa-2x mb-2"></i>
                                        <p>No users found</p>
                                        <a href="{{ route('superadmin.dashboard') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body py-3">
                                    <div class="row text-center">
                                        <div class="col-md-2">
                                            <h6 class="mb-0">Total Users</h6>
                                            <h4 class="text-primary mb-0">{{ $users->count() }}</h4>
                                        </div>
                                        @php
                                            $roleCounts = [
                                                'schooladmin' => 0,
                                                'teacher' => 0,
                                                'student' => 0,
                                                'parent' => 0,
                                                'bursar' => 0,
                                                'superadmin' => 0
                                            ];
                                            
                                            foreach($users as $user) {
                                                $role = $user->roles->first();
                                                if ($role && isset($roleCounts[$role->name])) {
                                                    $roleCounts[$role->name]++;
                                                }
                                            }
                                        @endphp
                                        @foreach($roleCounts as $role => $count)
                                        <div class="col-md-2">
                                            <h6 class="mb-0">{{ ucfirst($role) }}s</h6>
                                            <h4 class="text-info mb-0">{{ $count }}</h4>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Back to Dashboard Button -->
                    <div class="row mt-3">
                        <div class="col-md-12 text-center">
                            <a href="{{ route('superadmin.dashboard') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}
.table th {
    border-top: none;
    font-weight: 600;
    background-color: #f8f9fa;
}
.badge {
    font-size: 0.75em;
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

    // Filter functionality
    const roleFilter = document.getElementById('roleFilter');
    const schoolFilter = document.getElementById('schoolFilter');
    const searchInput = document.getElementById('search');
    const resetBtn = document.getElementById('resetFilters');
    const tableRows = document.querySelectorAll('#usersTable tbody tr');

    function filterTable() {
        const roleValue = roleFilter.value.toLowerCase();
        const schoolValue = schoolFilter.value;
        const searchValue = searchInput.value.toLowerCase();

        tableRows.forEach(row => {
            const roleCell = row.cells[4].textContent.toLowerCase();
            const schoolCell = row.cells[5].textContent;
            const nameCell = row.cells[1].textContent.toLowerCase();
            const emailCell = row.cells[2].textContent.toLowerCase();
            const phoneCell = row.cells[3].textContent.toLowerCase();

            const roleMatch = !roleValue || roleCell.includes(roleValue);
            const schoolMatch = !schoolValue || schoolCell.includes(schoolFilter.options[schoolFilter.selectedIndex].text);
            const searchMatch = !searchValue || 
                               nameCell.includes(searchValue) || 
                               emailCell.includes(searchValue) || 
                               phoneCell.includes(searchValue);

            row.style.display = (roleMatch && schoolMatch && searchMatch) ? '' : 'none';
        });
    }

    roleFilter.addEventListener('change', filterTable);
    schoolFilter.addEventListener('change', filterTable);
    searchInput.addEventListener('input', filterTable);

    resetBtn.addEventListener('click', function() {
        roleFilter.value = '';
        schoolFilter.value = '';
        searchInput.value = '';
        filterTable();
    });

    // Add some interactivity
    document.querySelectorAll('.btn-outline-primary').forEach(btn => {
        btn.addEventListener('click', function() {
            const userName = this.closest('tr').cells[1].textContent.trim();
            alert('View details for: ' + userName);
        });
    });
});
</script>
@endpush