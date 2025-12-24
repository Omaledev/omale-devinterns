@extends('layouts.app')

@section('content')
<div class="container-fluid">
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-600">Student Report Cards</h1>
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Select Criteria</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('teacher.reports.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <label>Academic Session</label>
                        <select name="session_id" id="session_id" class="form-control" required>
                            <option value="">Select Session</option>
                            @foreach($sessions as $sess)
                                <option value="{{ $sess->id }}" {{ $selectedSession == $sess->id ? 'selected' : '' }}>
                                    {{ $sess->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Term</label>
                        <select name="term_id" id="term_id" class="form-control" required>
                             {{-- You might need JS to populate this based on session, or just load all active terms --}}
                             @foreach(\App\Models\Term::where('school_id', auth()->user()->school_id)->get() as $t)
                                <option value="{{ $t->id }}" {{ $selectedTerm == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                             @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Class</label>
                        <select name="class_level_id" class="form-control" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ $selectedClass == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Load Students</button>
                </div>
            </form>
        </div>
    </div>

    @if(isset($students) && count($students) > 0)
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Student List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Admission No</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->studentProfile->student_id }}</td>
                            <td>
                                <a href="{{ route('teacher.reports.download', ['student' => $student->id, 'session_id' => $selectedSession, 'term_id' => $selectedTerm]) }}" 
                                   class="btn btn-sm btn-danger" target="_blank">
                                    <i class="fas fa-file-pdf"></i> Download PDF
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection