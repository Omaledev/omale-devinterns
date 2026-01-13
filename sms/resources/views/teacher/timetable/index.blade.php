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
                            <label class="form-label fw-bold">Select Class</label>
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
            @if($selectedClassId && $timetable)
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        Timetable for Class
                    </div>
                    <div class="card-body text-center">
                        {{-- Logic depends on how you store timetables (File upload vs Database Rows) --}}
                        
                        {{-- CASE A: If Timetable is a file upload --}}
                        @if(!empty($timetable->file_path))
                            <div class="mb-3">
                                <i class="fas fa-file-pdf fa-5x text-danger"></i>
                            </div>
                            <h5>Download Timetable</h5>
                            <a href="{{ asset('storage/' . $timetable->file_path) }}" class="btn btn-primary" target="_blank">
                                <i class="fas fa-download me-2"></i> View / Download
                            </a>

                        {{-- CASE B: If Timetable is just text/description --}}
                        @elseif(!empty($timetable->description))
                            <p class="lead">{{ $timetable->description }}</p>

                        {{-- CASE C: Empty --}}
                        @else
                            <p class="text-muted">No details available for this timetable.</p>
                        @endif
                    </div>
                </div>
            @elseif($selectedClassId)
                <div class="alert alert-warning">
                    No timetable has been uploaded/set for this class by the Admin yet.
                </div>
            @else
                <div class="alert alert-info">
                    Please select a class to view its timetable.
                </div>
            @endif
        </main>
    </div>
</div>
@endsection