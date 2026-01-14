@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('student.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold">My Subjects</h3>
                    <p class="text-muted mb-0">
                        You are enrolled in <span class="fw-bold text-primary">{{ $subjects->count() }}</span> subjects this session.
                    </p>
                </div>
            </div>

            @if($subjects->isEmpty())
                <div class="alert alert-warning py-4 text-center">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3 opacity-50"></i>
                    <h5>No Subjects Assigned</h5>
                    <p class="mb-0">It looks like your class timetable hasn't been set up yet.</p>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($subjects as $subject)
                        @php
                            $colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'];
                            $randomColor = $colors[$loop->index % count($colors)];
                        @endphp

                        <div class="col">
                            <div class="card h-100 shadow-sm border-0 hover-shadow">
                                <div class="card-body text-center pt-4">
                                    {{-- Icon Circle --}}
                                    <div class="d-inline-flex align-items-center justify-content-center bg-{{ $randomColor }} bg-opacity-10 text-{{ $randomColor }} rounded-circle mb-3" 
                                         style="width: 70px; height: 70px;">
                                        <i class="fas fa-book fa-2x"></i>
                                    </div>

                                    {{-- Subject Details --}}
                                    <h5 class="card-title fw-bold text-dark mb-1">{{ $subject->name }}</h5>
                                    <span class="badge bg-light text-dark border mb-3">
                                        {{ $subject->code ?? 'SUB-' . $subject->id }}
                                    </span>

                                    {{-- Optional: --}}
                                    <p class="small text-muted">{{ Str::limit($subject->description, 50) }}</p>
                                </div>

                                {{-- Footer Actions --}}
                                <div class="card-footer bg-white border-top-0 pb-4 d-flex justify-content-center gap-2">
                                    {{-- Link to view assignments for this subject --}}
                                    <a href="{{ route('student.assignments') }}" class="btn btn-outline-{{ $randomColor }} btn-sm rounded-pill px-3">
                                        <i class="fas fa-tasks me-1"></i> Assignments
                                    </a>
                                    
                                    {{-- Link to view study materials --}}
                                    <a href="{{ route('student.books') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                        <i class="fas fa-book-open me-1"></i> Books
                                    </a>
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