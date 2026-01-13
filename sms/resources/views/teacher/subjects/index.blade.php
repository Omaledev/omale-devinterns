@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('teacher.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h3 class="fw-bold mb-4">My Assigned Subjects</h3>

            @if($assignments->isEmpty())
                <div class="alert alert-info d-flex align-items-center">
                    <i class="fas fa-info-circle fa-2x me-3"></i>
                    <div>
                        <h5>No Subjects Assigned</h5>
                        <p class="mb-0">You have not been assigned to any classes yet. Please contact the School Administrator.</p>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach($assignments as $assignment)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-start ">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        {{-- Subject Icon & Name --}}
                                        <div>
                                            <h5 class="card-title fw-bold mb-0">{{ $assignment->subject->name }}</h5>
                                            <span class="badge bg-light text-dark border mt-2">{{ $assignment->subject->code ?? 'SUB' }}</span>
                                        </div>
                                    </div>
                                    
                                    <hr>

                                    {{-- Class Details --}}
                                    <div class="mb-3">
                                        <p class="mb-1 text-muted small text-uppercase fw-bold">Class Level</p>
                                        <h6 class="fw-bold">{{ $assignment->classLevel->name }}</h6>
                                    </div>

                                    <div class="mb-4">
                                        <p class="mb-1 text-muted small text-uppercase fw-bold">Section</p>
                                        @if($assignment->section)
                                            <span class="badge bg-info text-dark">{{ $assignment->section->name }}</span>
                                        @else
                                            <span class="text-muted fst-italic">All Sections</span>
                                        @endif
                                    </div>

                                    {{-- Quick Actions --}}
                                    <div class="d-grid gap-2">
                                        {{-- Link to Student List filtering by this class & section --}}
                                        <a href="{{ route('teacher.students.list', [
                                            'class_level_id' => $assignment->class_level_id, 
                                            'section_id' => $assignment->section_id
                                            ]) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-users me-2"></i> View Students
                                        </a>

                                        {{-- Link to Post Assignment pre-filled for this class --}}
                                        <a href="{{ route('teacher.assignments.create') }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-plus me-2"></i> Post Homework
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>
</div>
@endsection