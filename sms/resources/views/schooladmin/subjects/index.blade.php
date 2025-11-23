@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Subjects Management</h3>
                    <a href="{{ route('schooladmin.subjects.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Add New Subject
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Teachers Assigned</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subjects as $subject)
                                <tr>
                                    <td class="fw-bold">{{ $subject->code }}</td>
                                    <td>{{ $subject->name }}</td>
                                    <td>{{ $subject->description ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $subject->classroom_assignments_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $subject->is_active ? 'success' : 'secondary' }}">
                                            {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('schooladmin.subjects.show', $subject) }}"
                                               class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('schooladmin.subjects.edit', $subject) }}"
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('schooladmin.subjects.toggle-status', $subject) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-{{ $subject->is_active ? 'secondary' : 'success' }}"
                                                        title="{{ $subject->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas fa-{{ $subject->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('schooladmin.subjects.destroy', $subject) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this subject?')"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-book fa-3x mb-3"></i>
                                        <p>No subjects found. <a href="{{ route('schooladmin.subjects.create') }}">Create the first subject</a></p>
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
</div>
@endsection
