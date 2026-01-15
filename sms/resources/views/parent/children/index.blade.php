@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('parent.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h3 class="fw-bold mb-4">My Children</h3>

            @if($children->isEmpty())
                <div class="alert alert-info">
                    No children are linked to your account yet. Please contact the school administrator.
                </div>
            @else
                <div class="row g-4">
                    @foreach($children as $child)
                        <div class="col-md-6 col-xl-4">
                            <div class="card shadow-sm h-100 border-0">
                                <div class="card-body text-center p-4">
                                    {{-- Avatar --}}
                                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" 
                                         style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
                                        {{ substr($child->name, 0, 1) }}
                                    </div>

                                    <h5 class="card-title fw-bold mb-1">{{ $child->name }}</h5>
                                    
                                    {{-- Class Info --}}
                                    <p class="text-muted small mb-3">
                                        {{ $child->studentProfile->classLevel->name ?? 'No Class' }} 
                                        {{ $child->studentProfile->section->name ? ' - ' . $child->studentProfile->section->name : '' }}
                                    </p>

                                    <hr class="my-3">

                                    {{-- Quick Stats --}}
                                    <div class="row mb-3 small">
                                        <div class="col-6 border-end">
                                            <div class="fw-bold text-dark">{{ $child->attendance_rate ?? 0 }}%</div>
                                            <div class="text-muted">Attendance</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="fw-bold text-dark">{{ $child->average_grade ?? 'N/A' }}</div>
                                            <div class="text-muted">Avg Grade</div>
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('parent.results', $child->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-chart-bar me-1"></i> View Results
                                        </a>
                                        <a href="{{ route('parent.attendance', $child->id) }}" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-calendar-check me-1"></i> View Attendance
                                        </a>
                                        <a href="{{ route('parent.child-fees', $child->id) }}" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-money-bill me-1"></i> Fee History
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