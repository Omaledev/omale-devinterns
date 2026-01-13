@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('teacher.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="mb-4">Student Directory</h2>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Select Class to View Students
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.students.list') }}" method="GET">
                        <div class="row align-items-end">
                            <div class="col-md-5">
                                <label class="form-label fw-bold">Class Level</label>
                                <select name="class_level_id" class="form-select" required>
                                    <option value="">-- Select Class --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-bold">Section (Optional)</label>
                                <select name="section_id" class="form-select">
                                    <option value="">-- All Sections --</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}">
                                            {{ $section->name }} ({{ $section->classLevel->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    View List
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