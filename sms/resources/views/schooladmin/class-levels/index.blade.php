@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Class Levels Management</h3>
                    <a href="{{ route('schooladmin.class-levels.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Add New Class Level
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
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Order</th>
                                    <th>Sections</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($classLevels as $classLevel)
                                <tr>
                                    <td class="fw-bold">{{ $classLevel->name }}</td>
                                    <td>{{ $classLevel->description ?? 'N/A' }}</td>
                                    <td>{{ $classLevel->order }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $classLevel->sections->count() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $classLevel->is_active ? 'success' : 'secondary' }}">
                                            {{ $classLevel->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('schooladmin.class-levels.edit', $classLevel) }}"
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('schooladmin.class-levels.destroy', $classLevel) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this class level?')"
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
                                        <i class="fas fa-door-open fa-3x mb-3"></i>
                                        <p>No class levels found. <a href="{{ route('schooladmin.class-levels.create') }}">Create the first class level</a></p>
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
