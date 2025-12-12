@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('schooladmin.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Timetable Entry Details</h3>
                <a href="{{ route('schooladmin.timetables.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Schedule
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-book-open me-2"></i> {{ $timetable->subject->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 border-end">
                            <h6 class="text-muted text-uppercase mb-3">Academic Info</h6>
                            <dl class="row">
                                <dt class="col-sm-4">Class Level:</dt>
                                <dd class="col-sm-8">{{ $timetable->classLevel->name }}</dd>

                                <dt class="col-sm-4">Section:</dt>
                                <dd class="col-sm-8">{{ $timetable->section->name }}</dd>

                                <dt class="col-sm-4">Teacher:</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-info text-dark">{{ $timetable->teacher->name }}</span>
                                </dd>
                            </dl>
                        </div>

                        <div class="col-md-6 ps-md-4">
                            <h6 class="text-muted text-uppercase mb-3">Schedule Info</h6>
                            <dl class="row">
                                <dt class="col-sm-4">Day:</dt>
                                <dd class="col-sm-8 font-weight-bold">{{ $timetable->weekday }}</dd>

                                <dt class="col-sm-4">Time Slot:</dt>
                                <dd class="col-sm-8">
                                    <i class="far fa-clock text-primary me-1"></i>
                                    {{ \Carbon\Carbon::parse($timetable->start_time)->format('H:i') }} 
                                    - 
                                    {{ \Carbon\Carbon::parse($timetable->end_time)->format('H:i') }}
                                </dd>
                                
                                <dt class="col-sm-4">Created At:</dt>
                                <dd class="col-sm-8 text-muted small">{{ $timetable->created_at->format('M d, Y') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-light d-flex justify-content-end">
                    <form action="{{ route('schooladmin.timetables.destroy', $timetable->id) }}" method="POST" class="me-2">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this entry?')">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </form>
                    <a href="{{ route('schooladmin.timetables.edit', $timetable) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit Entry
                    </a>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection