@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('student.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h3 class="fw-bold mb-4">Assignments</h3>
            
            
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Subject</th>
                                <th>Assignment Details</th>
                                <th>Deadline</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $assignment)
                                {{-- Check if the current user has submitted --}}
                                @php
                                    $submission = $assignment->submissions->first(); 
                                    $isSubmitted = $submission !== null;
                                @endphp

                                <tr>
                                    <td><span class="badge bg-secondary">{{ $assignment->subject->name }}</span></td>
                                    <td>
                                        <div class="fw-bold">{{ $assignment->title }}</div>
                                        <small class="text-muted">{{ Str::limit($assignment->description, 50) }}</small>
                                        
                                        {{-- Download Teacher's File --}}
                                        @if($assignment->file_path)
                                            <div class="mt-1">
                                                <a href="{{ asset('storage/'.$assignment->file_path) }}" class="btn btn-xs btn-outline-primary" target="_blank">
                                                    <i class="fas fa-paperclip"></i> Download Resource
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-danger fw-bold">{{ \Carbon\Carbon::parse($assignment->deadline)->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($assignment->deadline)->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        @if($isSubmitted)
                                            <button class="btn btn-success btn-sm" disabled>
                                                <i class="fas fa-check-circle me-1"></i> Submitted
                                            </button>
                                            <div class="small text-muted mt-1">
                                                {{ $submission->created_at->format('M d, H:i') }}
                                            </div>
                                        @else
                                            {{-- Trigger Modal Button --}}
                                            <button type="button" 
                                                    class="btn btn-primary btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#submitModal" 
                                                    data-id="{{ $assignment->id }}"
                                                    data-title="{{ $assignment->title }}">
                                                <i class="fas fa-upload me-1"></i> Submit
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4">No assignments found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

{{-- SUBMISSION MODAL --}}
<div class="modal fade" id="submitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Submit Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('student.assignments.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    {{-- Hidden Input for Assignment ID --}}
                    <input type="hidden" name="assignment_id" id="modalAssignmentId">
                    
                    <p class="text-muted">Submitting for: <strong id="modalAssignmentTitle" class="text-primary"></strong></p>

                    <div class="mb-3">
                        <label class="form-label">Upload Work (PDF, Doc, Image)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Comments (Optional)</label>
                        <textarea name="comments" class="form-control" rows="3" placeholder="Any notes for the teacher..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Work</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    var submitModal = document.getElementById('submitModal');
    submitModal.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = event.relatedTarget;
        
        // Extracting info from data-* attributes
        var assignmentId = button.getAttribute('data-id');
        var assignmentTitle = button.getAttribute('data-title');
        
        // Updating the modal's content
        var modalIdInput = submitModal.querySelector('#modalAssignmentId');
        var modalTitleText = submitModal.querySelector('#modalAssignmentTitle');
        
        modalIdInput.value = assignmentId;
        modalTitleText.textContent = assignmentTitle;
    });
</script>
@endpush
@endsection