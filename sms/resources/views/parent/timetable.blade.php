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
                            {{ $child->name }} <small class="text-muted">({{ $child->studentProfile->classLevel->name ?? '' }})</small>
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach($children as $index => $child)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="content-{{ $child->id }}">
                        
                        @php
                            $schedule = $timetableData[$child->id] ?? collect();
                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                        @endphp

                        @if($schedule->isEmpty())
                            <div class="alert alert-warning">No timetable uploaded for this class yet.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle bg-white shadow-sm">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 15%">Day</th>
                                            <th>Schedule</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($days as $day)
                                            <tr>
                                                <td class="fw-bold bg-light">{{ $day }}</td>
                                                <td class="text-start p-3">
                                                    @if(isset($schedule[$day]))
                                                        <div class="row g-2">
                                                            @foreach($schedule[$day] as $slot)
                                                                <div class="col-md-4 col-lg-3">
                                                                    <div class="border rounded p-2 h-100 border-start border-4 border-primary">
                                                                        <div class="fw-bold text-primary">{{ $slot->subject->name }}</div>
                                                                        <div class="small text-muted">
                                                                            {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - 
                                                                            {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                                                        </div>
                                                                        <div class="small text-dark mt-1">
                                                                            <i class="fas fa-user-tie me-1"></i> {{ $slot->teacher->user->name ?? 'Staff' }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-muted fst-italic">No classes</span>
                                                    @endif
                                                </td>
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