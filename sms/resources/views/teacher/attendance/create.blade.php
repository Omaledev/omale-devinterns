@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Sidebar --}}
        @include('teacher.partials.sidebar')

        {{-- Main Content --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Mark Attendance: {{ $date }}</h3>
                @if(isset($sectionId))
                    <span class="badge bg-info">Section Filter Applied</span>
                @endif
            </div>

            <form action="{{ route('teacher.attendance.store') }}" method="POST">
                @csrf
                <input type="hidden" name="class_level_id" value="{{ $classId }}">
                {{-- FIX: Add hidden Section ID so it persists on Save --}}
                <input type="hidden" name="section_id" value="{{ $sectionId }}">
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
                                            $currentStatus = $existingAttendance[$student->id]->status ?? 'PRESENT'; 
                                        @endphp
                                        <tr>
                                            <td class="align-middle">
                                                <strong>{{ $student->user->name }}</strong><br>
                                                <small class="text-muted">
                                                    {{ $student->registration_number ?? 'No ID' }}
                                                    @if($student->section)
                                                        - <span class="text-info">{{ $student->section->name }}</span>
                                                    @endif
                                                </small>
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
                                            <td colspan="4" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-users-slash fa-2x mb-2"></i><br>
                                                    No students found in this Section.
                                                </div>
                                            </td>
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

        </main>
    </div>
</div>
@endsection