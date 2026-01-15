@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('parent.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">Parent-Teacher Meetings</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestMeetingModal">
                    <i class="fas fa-plus me-2"></i> Request Meeting
                </button>
            </div>

            {{-- Meetings List --}}
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    @if($meetings->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-handshake fa-3x text-muted mb-3 opacity-25"></i>
                            <h5 class="text-muted">No meetings scheduled</h5>
                            <p class="text-muted small">You haven't scheduled any meetings with teachers yet.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Teacher (Attendee)</th> {{-- Clarified Header --}}
                                        <th>Student (Context)</th>   {{-- Clarified Header --}}
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($meetings as $meeting)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $meeting->scheduled_at->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $meeting->scheduled_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-primary">{{ $meeting->teacher->user->name ?? 'Unknown' }}</span>
                                            </td>
                                            <td>{{ $meeting->student->name ?? '-' }}</td>
                                            <td>
                                                @if($meeting->status === 'approved')
                                                    <span class="badge bg-success">Confirmed</span>
                                                @elseif($meeting->status === 'pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @else
                                                    <span class="badge bg-secondary">Completed</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-info">Details</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>

{{-- REQUEST MEETING MODAL (FIXED) --}}
<div class="modal fade" id="requestMeetingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Request Meeting with Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('parent.meetings.store') }}" method="POST"> 
                @csrf
                <div class="modal-body">
                    
                    {{-- 1. SELECT TEACHER (Grouped by Child) --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Teacher to Meet</label>
                        <select class="form-select" name="teacher_data" required>
                            <option value="">Choose a teacher...</option>
                            
                            @foreach($children as $child)
                                <optgroup label="Teachers for: {{ $child->name }}">
                                    @php
                                        // Get teachers for this child's class
                                        $assignments = $child->studentProfile->classLevel->classroomAssignments ?? collect();
                                    @endphp

                                    @foreach($assignments as $assignment)
                                        {{-- 
                                            We pass a combined value so the backend knows 
                                            BOTH the teacher and which student it is about 
                                        --}}
                                        <option value="{{ $assignment->teacher->id }}|{{ $child->id }}">
                                            {{ $assignment->teacher->user->name ?? 'Unknown' }} 
                                            ({{ $assignment->subject->name ?? 'Class Teacher' }})
                                        </option>
                                    @endforeach
                                    
                                    @if($assignments->isEmpty())
                                        <option disabled>No teachers assigned yet</option>
                                    @endif
                                </optgroup>
                            @endforeach
                        </select>
                        <div class="form-text text-muted">
                            Selecting a teacher automatically links the meeting to the specific child.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subject / Reason</label>
                        <input type="text" name="subject" class="form-control" placeholder="e.g. Discuss Math Grades" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Preferred Date & Time</label>
                        <input type="datetime-local" name="scheduled_at" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Additional Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection