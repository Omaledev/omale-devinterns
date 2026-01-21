@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('parent.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h3 class="fw-bold mb-4">Class Timetables</h3>

            {{-- Child Selector Tabs --}}
            <ul class="nav nav-tabs mb-4" id="timetableTabs" role="tablist">
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
                        
                        @php
                            // Getting the schedule specifically for this child
                            $childSchedule = $timetableData[$child->id] ?? null;
                            
                            // Calculating unique time slots for this specific child
                            $timeSlots = [];
                            if($childSchedule) {
                                foreach ($childSchedule as $day => $entries) {
                                    $timeSlots = array_merge($timeSlots, $entries->keys()->all());
                                }
                            }
                            $timeSlots = array_unique($timeSlots);
                            
                            // Sorting times chronologically
                            usort($timeSlots, function($a, $b) {
                                return strtotime(explode('-', $a)[0]) - strtotime(explode('-', $b)[0]);
                            });
                        @endphp

                        @if(empty($timeSlots))
                            <div class="alert alert-warning text-center p-4">
                                <i class="fas fa-calendar-times fa-2x mb-3"></i><br>
                                No timetable has been uploaded for {{ $child->name }}'s class yet.
                            </div>
                        @else
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
                                                        @if(isset($childSchedule[$day][$slot]))
                                                            @php $entry = $childSchedule[$day][$slot]; @endphp
                                                            <div class="p-2 border rounded bg-primary text-white shadow-sm">
                                                                <div class="fw-bold">{{ $entry->subject->name ?? 'Subject' }}</div>
                                                                <div class="small mt-1">
                                                                    {{ $entry->teacher->name ?? 'Teacher' }}
                                                                </div>
                                                                @if($entry->room_number)
                                                                    <span class="badge bg-light text-dark mt-1">
                                                                        Room {{ $entry->room_number }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </main>
    </div>
</div>
@endsection