@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('student.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h3 class="fw-bold mb-4">My Teachers</h3>
            
            <div class="row g-4">
                @forelse($teachers as $assign)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body text-center">
                                <div class="mb-3 mx-auto bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <span class="fs-2 text-primary">{{ substr($assign->teacher->user->name, 0, 1) }}</span>
                                </div>
                                <h5 class="fw-bold">{{ $assign->teacher->user->name }}</h5>
                                <p class="text-primary mb-2">{{ $assign->subject->name }}</p>
                                <p class="small text-muted mb-3">{{ $assign->teacher->user->email }}</p>
                                
                                {{-- Link to Message System --}}
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newMessageModal">
                                    <i class="fas fa-envelope me-1"></i> Send Message
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted">No teachers assigned to your class yet.</div>
                @endforelse
            </div>
        </main>
    </div>
</div>
@endsection