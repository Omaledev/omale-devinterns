@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('teacher.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h3 class="fw-bold mb-4">Meeting Requests</h3>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    @if($meetings->isEmpty())
                        <div class="alert alert-info">No meeting requests found.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Scheduled For</th>
                                        <th>Parent</th>
                                        <th>Student</th>
                                        <th>Reason</th>
                                        <th>Current Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($meetings as $meeting)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-primary">
                                                    {{ $meeting->scheduled_at->format('M d, Y') }}
                                                </div>
                                                <small>{{ $meeting->scheduled_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                {{ $meeting->parent->name ?? 'Unknown' }}<br>
                                                <small class="text-muted">{{ $meeting->parent->email ?? '' }}</small>
                                            </td>
                                            <td>{{ $meeting->student->name ?? '-' }}</td>
                                            <td>{{ $meeting->subject }}</td>
                                            <td>
                                                @if($meeting->status == 'pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif($meeting->status == 'approved')
                                                    <span class="badge bg-success">Confirmed</span>
                                                @elseif($meeting->status == 'declined')
                                                    <span class="badge bg-danger">Declined</span>
                                                @else
                                                    <span class="badge bg-secondary">Completed</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($meeting->status === 'pending')
                                                    <div class="btn-group btn-group-sm">
                                                        <form action="{{ route('teacher.meetings.update', $meeting->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="status" value="approved">
                                                            <button class="btn btn-success" title="Approve">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('teacher.meetings.update', $meeting->id) }}" method="POST" class="ms-1">
                                                            @csrf
                                                            <input type="hidden" name="status" value="declined">
                                                            <button class="btn btn-danger" title="Decline">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <button class="btn btn-sm btn-outline-secondary" disabled>Processed</button>
                                                @endif
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
@endsection