@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('student.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h3 class="fw-bold mb-4">My Attendance History</h3>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendanceRecords as $record)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($record->date)->format('l, F j, Y') }}</td>
                                    <td>
                                        @if($record->status === 'present')
                                            <span class="badge bg-success">Present</span>
                                        @elseif($record->status === 'absent')
                                            <span class="badge bg-danger">Absent</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ ucfirst($record->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ $record->remark ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">No attendance records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white">
                    {{ $attendanceRecords->links() }}
                </div>
            </div>
        </main>
    </div>
</div>
@endsection