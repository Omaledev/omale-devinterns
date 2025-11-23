@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Sections Management</h3>
                    <a href="{{ route('schooladmin.sections.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Add New Section
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
                                    <th>Class Level</th>
                                    <th>Capacity</th>
                                    <th>Students</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sections as $section)
                                <tr>
                                    <td class="fw-bold">{{ $section->name }}</td>
                                    <td>{{ $section->classLevel->name }}</td>
                                    <td>{{ $section->room_number ?? 'N/A' }}</td>
                                    <td>
                                        @if($section->capacity)
                                            {{ $section->students_count ?? 0 }}/{{ $section->capacity }}
                                            @if($section->capacity)
                                                <div class="progress mt-1" style="height: 5px;">
                                                    <div class="progress-bar bg-{{ ($section->students_count / $section->capacity * 100) > 90 ? 'danger' : (($section->students_count / $section->capacity * 100) > 75 ? 'warning' : 'success') }}"
                                                         style="width: {{ ($section->students_count / $section->capacity * 100) }}%">
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            {{ $section->students_count ?? 0 }} students
                                        @endif
                                    </td>
                                    <td>{{ $section->students->count() }}</td>
                                    <td>
                                        <span class="badge bg-{{ $section->is_active ? 'success' : 'secondary' }}">
                                            {{ $section->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('schooladmin.sections.show', $section) }}"
                                               class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('schooladmin.sections.edit', $section) }}"
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('schooladmin.sections.destroy', $section) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this section?')"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-door-open fa-3x mb-3"></i>
                                        <p>No sections found. <a href="{{ route('schooladmin.sections.create') }}">Create the first section</a></p>
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
