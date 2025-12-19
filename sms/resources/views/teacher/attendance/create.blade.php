@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Mark Attendance: {{ $date }}</h3>
    <form action="{{ route('teacher.attendance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="class_level_id" value="{{ $classId }}">
        <input type="hidden" name="date" value="{{ $date }}">

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Student Name</th>
                                <th class="text-center">Present</th>
                                <th class="text-center">Absent</th>
                                <th class="text-center">Late</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                @php
                                    // Check if status exists, default to PRESENT if new
                                    $currentStatus = $existingAttendance[$student->id]->status ?? 'PRESENT'; 
                                @endphp
                                <tr>
                                    <td class="align-middle">
                                        <strong>{{ $student->user->name }}</strong><br>
                                        <small class="text-muted">{{ $student->registration_number ?? 'No ID' }}</small>
                                    </td>
                                    
                                    <td class="text-center">
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio" 
                                                   name="attendances[{{ $student->id }}]" 
                                                   value="PRESENT" 
                                                   {{ $currentStatus == 'PRESENT' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio" 
                                                   name="attendances[{{ $student->id }}]" 
                                                   value="ABSENT"
                                                   {{ $currentStatus == 'ABSENT' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio" 
                                                   name="attendances[{{ $student->id }}]" 
                                                   value="LATE"
                                                   {{ $currentStatus == 'LATE' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">No students found in this class.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">Save Attendance</button>
                <a href="{{ route('teacher.attendance.select') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection