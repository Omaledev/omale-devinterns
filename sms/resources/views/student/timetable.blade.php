@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('student.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h3 class="fw-bold mb-4">My Timetable</h3>
            
            <div class="row">
                @php $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']; @endphp
                
                @foreach($days as $day)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0 fw-bold">{{ $day }}</h6>
                            </div>
                            <div class="list-group list-group-flush">
                                @if(isset($timetable[strtolower($day)]))
                                    @foreach($timetable[strtolower($day)] as $slot)
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold">{{ $slot->subject->name }}</span>
                                                <span class="badge bg-light text-dark">{{ $slot->start_time }} - {{ $slot->end_time }}</span>
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-user-tie me-1"></i> {{ $slot->teacher->user->name ?? 'TBA' }}
                                                @if($slot->room_number) • Room {{ $slot->room_number }} @endif
                                            </small>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="list-group-item text-muted text-center py-3">No classes</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </main>
    </div>
</div>
@endsection