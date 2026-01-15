@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('parent.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h3 class="fw-bold mb-4">Attendance Records</h3>

            {{-- Child Selector Tabs --}}
            <ul class="nav nav-tabs mb-4" id="attendanceTabs" role="tablist">
                @foreach($children as $index => $child)
                    <li class="nav-item">
                        <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                id="tab-{{ $child->id }}" 
                                data-bs-toggle="tab" 
                                data-bs-target="#content-{{ $child->id }}" 
                                type="button">
                            {{ $child->name }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach($children as $index => $child)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="content-{{ $child->id }}">
                        
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Summary</h5>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ $child->attendance_rate }}%;" 
                                         aria-valuenow="{{ $child->attendance_rate }}" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        {{ $child->attendance_rate }}% Present
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover bg-white">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Recorded By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($child->studentProfile->attendances->sortByDesc('date') as $attendance)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                                            <td>
                                                @if($attendance->status === 'PRESENT')
                                                    <span class="badge bg-success">Present</span>
                                                @elseif($attendance->status === 'ABSENT')
                                                    <span class="badge bg-danger">Absent</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Late</span>
                                                @endif
                                            </td>
                                            <td class="small text-muted">
                                                {{ $attendance->teacher->name ?? 'Teacher' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-3 text-muted">
                                                No attendance records found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </main>
    </div>
</div>
@endsection