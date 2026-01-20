@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('teacher.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Attendance History</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('teacher.attendance.select') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Take New Attendance
                    </a>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendanceHistory as $record)
                                    <tr>
                                        <td>
                                            {{-- Format date if it's a Carbon instance --}}
                                            {{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}
                                        </td>
                                        <td>{{ $record->student->user->name ?? 'N/A' }}</td>
                                        <td>{{ $record->classLevel->name ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                $statusColor = match($record->status) {
                                                    'PRESENT' => 'success',
                                                    'ABSENT' => 'danger',
                                                    'LATE' => 'warning',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusColor }}">
                                                {{ ucfirst(strtolower($record->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            No attendance records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $attendanceHistory->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('styles')
<style>
    .sidebar {
        min-height: calc(100vh - 56px);
        box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
    }
    .sidebar .nav-link {
        padding: .5rem 1rem;
        color: #333;
    }
    .sidebar .nav-link.active {
        color: #0d6efd;
    }
</style>
@endpush