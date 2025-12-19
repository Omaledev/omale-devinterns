@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('teacher.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Attendance Management</h3>
            </div>

            <div class="row justify-content-center"> <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-calendar-check me-1"></i> Take Attendance
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('teacher.attendance.create') }}" method="POST">
                                @csrf
                                
                                {{-- Class Dropdown --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Select Class</label>
                                    <select name="class_level_id" class="form-control" required>
                                        <option value="">-- Choose Class --</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- NEW: Section Dropdown --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Select Section</label>
                                    <select name="section_id" class="form-control" required>
                                        <option value="">-- Choose Section --</option>
                                        {{-- We use $sections variable here --}}
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Date Input --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        Load Student List <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                    
                                    <a href="{{ route('teacher.attendance.summary') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-history me-1"></i> View History
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>
@endsection