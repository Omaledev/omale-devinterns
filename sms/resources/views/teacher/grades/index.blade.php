@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Sidebar --}}
        @include('teacher.partials.sidebar')

        {{-- Main Content --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            {{-- Header --}}
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Enter Grades</h1>
            </div>

            {{-- Alerts --}}
            @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Selection Card --}}
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Select Class to Grade
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.grades.create') }}" method="GET">
                        <div class="row align-items-end">
                            <div class="col-md-5 mb-3 mb-md-0">
                                <label class="form-label fw-bold">Class Level</label>
                                <select name="class_level_id" class="form-select" required>
                                    <option value="">-- Select Class --</option>
                                    @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5 mb-3 mb-md-0">
                                <label class="form-label fw-bold">Subject</label>
                                <select name="subject_id" class="form-select" required>
                                    <option value="">-- Select Subject --</option>
                                    @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-arrow-right me-1"></i> Enter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection