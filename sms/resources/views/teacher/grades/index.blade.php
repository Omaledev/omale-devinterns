@extends('layouts.app')

@section('content')

@if(session('error'))
<div class="alert alert-danger">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">Select Class to Grade</div>
        <div class="card-body">
            <form action="{{ route('teacher.grades.create') }}" method="GET">
                <div class="row">
                    <div class="col-md-5">
                        <label>Class</label>
                        <select name="class_level_id" class="form-control" required>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label>Subject</label>
                        <select name="subject_id" class="form-control" required>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Enter Grades</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection