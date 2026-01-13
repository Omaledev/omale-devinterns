@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- SIDEBAR --}}
        @include('teacher.partials.sidebar')

        {{-- MAIN CONTENT AREA --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Student Report Cards</h1>
                <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Select Criteria</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.reports.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="fw-bold small text-muted">Academic Session</label>
                                <select name="session_id" id="session_id" class="form-control" required>
                                    <option value="">Select Session</option>
                                    @foreach($sessions as $sess)
                                        <option value="{{ $sess->id }}" {{ $selectedSession == $sess->id ? 'selected' : '' }}>
                                            {{ $sess->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="fw-bold small text-muted">Term</label>
                                <select name="term_id" id="term_id" class="form-control" required>
                                     @foreach(\App\Models\Term::where('school_id', auth()->user()->school_id)->get() as $t)
                                        <option value="{{ $t->id }}" {{ $selectedTerm == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                                     @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="fw-bold small text-muted">Class</label>
                                <select name="class_level_id" class="form-control" required>
                                    <option value="">Select Class</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ $selectedClass == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-2 text-end">
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-search me-1"></i> Load Students</button>
                        </div>
                    </form>
                </div>
            </div>

            @if(isset($students) && count($students) > 0)
            <div class="card shadow">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Student List</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Admission No</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <td class="align-middle fw-bold text-dark">{{ $student->name }}</td>
                                    <td class="align-middle">{{ $student->studentProfile->admission_number ?? 'N/A' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('teacher.reports.download', ['student' => $student->id, 'session_id' => $selectedSession, 'term_id' => $selectedTerm]) }}" 
                                           class="btn btn-sm btn-danger shadow-sm" target="_blank">
                                            <i class="fas fa-file-pdf me-1"></i> Download PDF
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

        </main>
    </div>
</div>
@endsection