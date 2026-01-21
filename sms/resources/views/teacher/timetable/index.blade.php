@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('teacher.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h3 class="fw-bold mb-4">Class Timetable</h3>

            {{-- Filter Form --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="{{ route('teacher.timetable.index') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Select Class to View</label>
                            <select name="class_level_id" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Choose Class --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Timetable Display --}}
            @if($selectedClassId && !empty($weeklyTimetable))
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center bg-white shadow-sm">
                        <thead class="table-dark">
                            <tr>
                                <th class="align-middle" style="width: 10%">Time</th>
                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                                    <th style="width: 18%">{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Calculatingunique time slots across the week
                                $timeSlots = [];
                                foreach ($weeklyTimetable as $day => $entries) {
                                    $timeSlots = array_merge($timeSlots, $entries->keys()->all());
                                }
                                $timeSlots = array_unique($timeSlots);
                                
                                // Sort times chronologically
                                usort($timeSlots, function($a, $b) {
                                    return strtotime(explode('-', $a)[0]) - strtotime(explode('-', $b)[0]);
                                });
                            @endphp

                            @if(count($timeSlots) > 0)
                                @foreach($timeSlots as $slot)
                                    @php
                                        [$startTime, $endTime] = explode('-', $slot);
                                    @endphp
                                    <tr>
                                        <td class="bg-light align-middle fw-bold">
                                            {{ \Carbon\Carbon::parse($startTime)->format('H:i') }}<br>
                                            -<br>
                                            {{ \Carbon\Carbon::parse($endTime)->format('H:i') }}
                                        </td>

                                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                                            <td>
                                                @if(isset($weeklyTimetable[$day][$slot]))
                                                    @php $entry = $weeklyTimetable[$day][$slot]; @endphp
                                                    <div class="p-2 border rounded bg-info text-white shadow-sm">
                                                        <strong>{{ $entry->subject->name ?? 'Subject' }}</strong><br>
                                                        <small>{{ $entry->section->name ?? 'Section' }}</small><br>
                                                        <small>{{ $entry->teacher->name ?? 'Teacher' }}</small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <p class="text-muted">No timetable entries found for this class.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            @elseif($selectedClassId)
                <div class="alert alert-warning">
                    No timetable has been created for this class yet.
                </div>
            @else
                <div class="alert alert-info">
                    Please select a class above to view the schedule.
                </div>
            @endif
        </main>
    </div>
</div>
@endsection