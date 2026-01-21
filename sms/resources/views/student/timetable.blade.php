@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('student.partials.sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h3 class="fw-bold mb-4">My Class Timetable</h3>
            
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center mb-0">
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
                                    // Collecting all unique time slots from the database
                                    $timeSlots = [];
                                    if(isset($weeklyTimetable)) {
                                        foreach ($weeklyTimetable as $day => $entries) {
                                            $timeSlots = array_merge($timeSlots, $entries->keys()->all());
                                        }
                                    }
                                    $timeSlots = array_unique($timeSlots);
                                    
                                    // Sorting them chronologically
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
                                            {{-- Time Column --}}
                                            <td class="bg-light align-middle fw-bold text-nowrap">
                                                {{ \Carbon\Carbon::parse($startTime)->format('H:i') }}<br>
                                                -<br>
                                                {{ \Carbon\Carbon::parse($endTime)->format('H:i') }}
                                            </td>

                                            {{-- Days Columns --}}
                                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                                                <td class="align-middle">
                                                    @if(isset($weeklyTimetable[$day][$slot]))
                                                        @php $entry = $weeklyTimetable[$day][$slot]; @endphp
                                                        <div class="p-2 border rounded bg-primary text-white shadow-sm">
                                                            <div class="fw-bold">{{ $entry->subject->name ?? 'Subject' }}</div>
                                                            <div class="small mt-1">
                                                                {{ $entry->teacher->name ?? 'Teacher' }}
                                                            </div>
                                                            @if($entry->room_number)
                                                                <div class="badge bg-light text-dark mt-1">
                                                                    Room {{ $entry->room_number }}
                                                                </div>
                                                            @endif
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
                                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No timetable has been published for your class yet.</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection