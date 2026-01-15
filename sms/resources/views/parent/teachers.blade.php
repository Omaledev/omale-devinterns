@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('parent.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h3 class="fw-bold mb-4">My Children's Teachers</h3>

            @if($children->isEmpty())
                <div class="alert alert-info">No children found linked to your account.</div>
            @else
                <div class="accordion" id="teachersAccordion">
                    @foreach($children as $index => $child)
                        <div class="accordion-item mb-3 border shadow-sm rounded overflow-hidden">
                            <h2 class="accordion-header" id="heading-{{ $child->id }}">
                                <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }} bg-white fw-bold" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#collapse-{{ $child->id }}" 
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                    <span class="text-primary me-2"><i class="fas fa-user-graduate"></i></span>
                                    Teachers for: {{ $child->name }} 
                                    <small class="text-muted ms-2 fw-normal">
                                        ({{ $child->studentProfile->classLevel->name ?? 'No Class Assigned' }})
                                    </small>
                                </button>
                            </h2>
                            <div id="collapse-{{ $child->id }}" 
                                 class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                                 data-bs-parent="#teachersAccordion">
                                <div class="accordion-body bg-light">
                                    
                                    @php
                                        // Get assignments safely
                                        $assignments = $child->studentProfile->classLevel->classroomAssignments ?? collect();
                                    @endphp

                                    @if($assignments->isEmpty())
                                        <div class="text-muted fst-italic p-3">
                                            No teachers have been assigned to this class yet.
                                        </div>
                                    @else
                                        <div class="row g-3">
                                            @foreach($assignments as $assignment)
                                                <div class="col-md-6 col-lg-4">
                                                    <div class="card h-100 border-0 shadow-sm">
                                                        <div class="card-body d-flex align-items-center">
                                                            {{-- Teacher Avatar / Icon --}}
                                                            <div class="me-3">
                                                                @if($assignment->teacher->user->profile_photo_path ?? false)
                                                                    <img src="{{ asset('storage/' . $assignment->teacher->user->profile_photo_path) }}" 
                                                                         class="rounded-circle" width="50" height="50">
                                                                @else
                                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                                        {{ substr($assignment->teacher->user->name ?? 'T', 0, 1) }}
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            {{-- Info --}}
                                                            <div>
                                                                <h6 class="mb-0 fw-bold text-dark">
                                                                    {{ $assignment->teacher->user->name ?? 'Unknown Teacher' }}
                                                                </h6>
                                                                <small class="text-primary fw-bold d-block mb-1">
                                                                    {{ $assignment->subject->name ?? 'General Subject' }}
                                                                </small>
                                                                
                                                                @if($assignment->teacher->user->email ?? false)
                                                                    <a href="mailto:{{ $assignment->teacher->user->email }}" class="text-muted small text-decoration-none">
                                                                        <i class="far fa-envelope me-1"></i> {{ $assignment->teacher->user->email }}
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        {{-- Footer Action --}}
                                                        <div class="card-footer bg-white border-0 pt-0">
                                                            {{-- <div class="d-grid">
                                                                <a href="{{ route('parent.messages') }}" class="btn btn-sm btn-outline-secondary">
                                                                    <i class="fas fa-comment-alt me-1"></i> Send Message
                                                                </a>
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
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