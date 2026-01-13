@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('teacher.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h3 class="fw-bold mb-4">Assessment Structure</h3>
            <p class="text-muted">These are the active assessment types and weightings defined by the school for this session.</p>

            <div class="row">
                @forelse($assessmentTypes as $type)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100 border-start">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="fw-bold mb-0">{{ $type->name }}</h5>
                                    <span class="badge bg-primary fs-6">{{ $type->weight }}%</span>
                                </div>
                                <p class="text-muted small mb-0">Max Score: {{ $type->max_score }}</p>
                                <hr>
                                <div class="d-grid">
                                    <a href="{{ route('teacher.grades.index') }}" class="btn btn-outline-primary btn-sm">
                                        Enter Grades
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle me-2"></i> No assessment types have been configured by the School Admin yet.
                        </div>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
</div>
@endsection