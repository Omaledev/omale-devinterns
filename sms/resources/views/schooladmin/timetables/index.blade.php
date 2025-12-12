@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('schooladmin.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Timetable Schedule</h3>
                <div>
                    <a href="{{ route('schooladmin.timetables.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Entry
                    </a>
                    <button type="button" class="btn btn-secondary" onclick="window.print()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">Filter Timetable View</div>
                <div class="card-body">
                    <form method="GET" action="{{ route('schooladmin.timetables.index') }}">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="filter_by">Filter By</label>
                                <select name="filter_by" id="filter_by" class="form-control">
                                    <option value="class" @selected($filterBy == 'class')>Class/Section</option>
                                    <option value="teacher" @selected($filterBy == 'teacher')>Teacher</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="filter_id">Select Value</label>
                                <select name="filter_id" id="filter_id" class="form-control">
                                    <option value="">-- View All --</option>
                                    @if($filterBy == 'class')
                                        @foreach($classes as $class)
                                            @foreach($class->sections as $section)
                                                <option value="{{ $class->id }}" @selected($filterId == $class->id)>
                                                    {{ $class->name }} ({{ $section->name }})
                                                </option>
                                            @endforeach
                                        @endforeach
                                    @elseif($filterBy == 'teacher')
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" @selected($filterId == $teacher->id)>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Apply Filter</button>
                                <a href="{{ route('schooladmin.timetables.index') }}" class="btn btn-outline-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center bg-white shadow-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th class="align-middle" style="width: 10%">Time</th>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                                <th style="width: 18%">{{ $day }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $timeSlots = [];
                            foreach ($weeklyTimetable as $day => $entries) {
                                // Use Laravel's ->keys()->all() instead of PHP's array_keys()
                                $timeSlots = array_merge($timeSlots, $entries->keys()->all());
                            }
                            
                            $timeSlots = array_unique($timeSlots);
                            
                            usort($timeSlots, function($a, $b) {
                                return strtotime(explode('-', $a)[0]) - strtotime(explode('-', $b)[0]);
                            });
                        @endphp

                        @foreach($timeSlots as $slot)
                            @php
                                [$startTime, $endTime] = explode('-', $slot);
                            @endphp
                            <tr>
                                <td class="bg-light align-middle font-weight-bold">
                                    {{ \Carbon\Carbon::parse($startTime)->format('H:i') }}<br>
                                    -<br>
                                    {{ \Carbon\Carbon::parse($endTime)->format('H:i') }}
                                </td>

                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                                    <td>
                                        @if(isset($weeklyTimetable[$day][$slot]))
                                            @php $entry = $weeklyTimetable[$day][$slot]; @endphp
                                            <div class="p-2 border rounded bg-info text-white shadow-sm">
                                                <strong>{{ $entry->subject->name }}</strong><br>
                                                <small>{{ $entry->classLevel->name }} ({{ $entry->section->name }})</small><br>
                                                <small>{{ $entry->teacher->name }}</small>
                                                <div class="mt-2">
                                                    <a href="{{ route('schooladmin.timetables.show', $entry->id) }}" class="btn btn-sm btn-light py-0 px-1" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <a href="{{ route('schooladmin.timetables.edit', $entry->id) }}" class="btn btn-sm btn-light py-0 px-1" title="Edit"><i class="fas fa-edit"></i></a>
                                                    
                                                    <form action="{{ route('schooladmin.timetables.destroy', $entry->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger py-0 px-1" onclick="return confirm('Delete this entry?')" title="Delete"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
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
        </main>
    </div>
</div>
@endsection