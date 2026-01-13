@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('teacher.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold">Assignments</h3>
                    <p class="text-muted mb-0">Manage homework and tasks for your classes.</p>
                </div>
                <a href="{{ route('teacher.assignments.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus me-2"></i> Post New Assignment
                </a>
            </div>

            {{-- Table Card --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-uppercase small fw-bold text-muted">
                                <tr>
                                    <th class="ps-4">Assignment Details</th>
                                    <th>Target Audience</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $assignment)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded p-2 me-3 text-primary">
                                                    <i class="fas fa-book-open"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $assignment->title }}</div>
                                                    <small class="text-muted">{{ Str::limit($assignment->description, 40) }}</small>
                                                    @if($assignment->file_path)
                                                        <i class="fas fa-paperclip text-muted ms-1" title="Has attachment"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark">
                                                    {{ $assignment->classLevel->name }}
                                                    @if($assignment->section)
                                                        <span class="text-muted small">({{ $assignment->section->name }})</span>
                                                    @endif
                                                </span>
                                                <span class="badge bg-secondary w-auto align-self-start mt-1">
                                                    {{ $assignment->subject->name }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <i class="far fa-calendar-alt me-1 text-muted"></i> 
                                                {{ \Carbon\Carbon::parse($assignment->deadline)->format('M d, Y') }}
                                            </div>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($assignment->deadline)->diffForHumans() }}
                                            </small>
                                        </td>
                                        <td>
                                            @if(\Carbon\Carbon::parse($assignment->deadline)->isPast())
                                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Closed</span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Active</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group btn-group-sm">
                                                {{-- Edit Button --}}
                                                <a href="{{ route('teacher.assignments.edit', $assignment) }}" 
                                                   class="btn btn-outline-secondary" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Edit Assignment">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                {{-- Delete Button --}}
                                                <form action="{{ route('teacher.assignments.destroy', $assignment) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to delete this assignment? This cannot be undone.');"
                                                      class="d-inline">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-outline-danger" 
                                                            data-bs-toggle="tooltip" 
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-clipboard-list fa-3x mb-3 opacity-50"></i>
                                                <h5>No Assignments Found</h5>
                                                <p class="mb-3">You haven't posted any homework yet.</p>
                                                <a href="{{ route('teacher.assignments.create') }}" class="btn btn-sm btn-primary">
                                                    Create First Assignment
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- Pagination Links --}}
                @if($assignments->hasPages())
                    <div class="card-footer bg-white py-3">
                        <div class="d-flex justify-content-center">
                            {{ $assignments->links() }}
                        </div>
                    </div>
                @endif
            </div>

        </main>
    </div>
</div>

{{-- Initialize Tooltips --}}
@push('scripts')
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush
@endsection