@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('teacher.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-dark">My Classes</h3>
                    <p class="text-muted">Manage your assigned classes and students</p>
                </div>
                <span class="badge bg-primary fs-6 rounded-pill px-3">{{ $classes->count() }} Classes Assigned</span>
            </div>

            @if($classes->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-chalkboard-teacher fa-4x text-muted opacity-50"></i>
                    </div>
                    <h5 class="text-muted">No classes assigned yet.</h5>
                    <p class="text-muted small">Contact your school administrator for assignments.</p>
                </div>
            @else
                <div class="row g-4">
                    @php
                        // aesthetic colors to cycle through
                        $gradients = [
                            'linear-gradient(135deg, #667eea 0%, #764ba2 100%)', // Purple
                            'linear-gradient(135deg, #2af598 0%, #009efd 100%)', // Green-Blue
                            'linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%)', // Pink
                            'linear-gradient(135deg, #f6d365 0%, #fda085 100%)', // Orange
                        ];
                    @endphp

                    @foreach($classes as $index => $class)
                        <div class="col-xl-4 col-md-6">
                            <div class="card border-0 shadow-sm h-100 overflow-hidden hover-card">
                                <div class="card-header border-0 p-4 text-white position-relative" 
                                     style="background: {{ $gradients[$index % count($gradients)] }}; min-height: 120px;">
                                    
                                    <h4 class="fw-bold mb-1 position-relative z-1">{{ $class->name }}</h4>
                                    <p class="mb-0 opacity-75 position-relative z-1">
                                        {{ $class->sections->pluck('name')->join(', ') }}
                                    </p>
                                    
                                    <i class="fas fa-book position-absolute" 
                                       style="right: 20px; bottom: 10px; font-size: 5rem; opacity: 0.15; transform: rotate(-15deg);"></i>
                                </div>

                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div>
                                            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Total Students</small>
                                            <h3 class="fw-bold text-dark mb-0">{{ $class->students->count() ?? 0 }}</h3>
                                        </div>
                                        <div class="icon-circle bg-light text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-users fa-lg"></i>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <a href="{{ route('teacher.attendance.create', ['class_level_id' => $class->id, 'date' => date('Y-m-d')]) }}" 
                                           class="btn btn-primary py-2 fw-bold shadow-sm">
                                            <i class="fas fa-check-circle me-2"></i> Take Attendance
                                        </a>
                                        <a href="{{ route('teacher.class-students', $class->id) }}" 
                                           class="btn btn-outline-secondary py-2">
                                            <i class="fas fa-list me-2"></i> View Student List
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-0 text-center pb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i> Next Session: Today, 9:00 AM
                                    </small>
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

@push('styles')
<style>
    .hover-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    }
    .z-1 {
        z-index: 1;
    }
</style>
@endpush