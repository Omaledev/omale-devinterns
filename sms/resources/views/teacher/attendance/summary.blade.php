@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h3>Attendance History</h3>
        <a href="{{ route('teacher.attendance.select') }}" class="btn btn-primary">Take New Attendance</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendanceHistory as $record)
                        <tr>
                            <td>{{ $record->date }}</td>
                            <td>{{ $record->student->user->name ?? 'N/A' }}</td>
                            <td>{{ $record->classLevel->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $record->status == 'PRESENT' ? 'success' : ($record->status == 'ABSENT' ? 'danger' : 'warning') }}">
                                    {{ $record->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $attendanceHistory->links() }}
        </div>
    </div>
</div>
@endsection