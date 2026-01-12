@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        
        {{-- Sidebar --}}
        
        @if(auth()->user()->hasRole('SuperAdmin'))
            @include('superadmin.partials.sidebar') 

        @elseif(auth()->user()->hasRole('SchoolAdmin'))
            @include('schooladmin.partials.sidebar')

        @elseif(auth()->user()->hasRole('Teacher'))
            @include('teacher.partials.sidebar')

        @elseif(auth()->user()->hasRole('Student'))
            @include('student.partials.sidebar')

        @elseif(auth()->user()->hasRole('Parent'))
            @include('parent.partials.sidebar')

        @elseif(auth()->user()->hasRole('Bursar'))
            @include('bursar.partials.sidebar')

        @endif


        {{-- Main Content Area --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Announcement Board</h1>
            </div>

            {{-- FORM (Only for SuperAdmin, SchoolAdmin, and Teacher) --}}
            @if(auth()->user()->hasRole(['SuperAdmin', 'SchoolAdmin', 'Teacher']))
            <div class="card shadow mb-4 border-start ">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 fw-bold text-primary">Post New Announcement</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('announcements.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="small fw-bold">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="e.g. Assignment Due Date" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="small fw-bold">Target Audience</label>
                                <select name="target_role" class="form-select">
                                    <option value="">Everyone (Public)</option>
                                    <option value="Student">Students Only</option>
                                    <option value="Parent">Parents Only</option>
                                    <option value="Teacher">Staff Only</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold">Message <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="3" placeholder="Type your message here..." required></textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_scheduled" id="scheduleCheck" 
                                       onclick="document.getElementById('dateBox').classList.toggle('d-none')">
                                <label class="form-check-label small" for="scheduleCheck">Schedule for later?</label>
                            </div>
                            <button type="submit" class="btn btn-primary px-4">
                                Post
                            </button>
                        </div>

                        {{-- Hidden Date Box --}}
                        <div id="dateBox" class="mt-3 d-none">
                            <label class="small text-muted">Publish Date</label>
                            <input type="datetime-local" name="publish_at" class="form-control w-50" value="{{ now()->format('Y-m-d\TH:i') }}">
                        </div>
                    </form>
                </div>
            </div>
            @endif

            {{-- ANNOUNCEMENT LIST --}}
            <h5 class="mb-3 text-gray-800">Recent Updates</h5>
            
            <div class="row">
                @forelse($announcements as $announcement)
                <div class="col-lg-6 mb-4">
                    <div class="card shadow h-100 {{ $announcement->target_role ? 'border-start border-info border-4' : 'border-start' }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    {{-- Badge showing who it is for --}}
                                    <div class="text-xs fw-bold text-uppercase mb-1 {{ $announcement->target_role ? 'text-info' : 'text-warning' }}">
                                        <i class="fas {{ $announcement->target_role ? 'fa-user-tag' : 'fa-broadcast-tower' }} me-1"></i>
                                        {{ $announcement->target_role ? $announcement->target_role . 's' : 'General Announcement' }}
                                    </div>
                                    <h5 class="fw-bold text-gray-800 mb-1">{{ $announcement->title }}</h5>
                                </div>

                                {{-- DROPDOWN ACTIONS --}}
                                @if(auth()->id() == $announcement->created_by || auth()->user()->hasRole('SuperAdmin'))
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle text-muted" href="#" role="button" id="dropdownMenuLink{{ $announcement->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in">
                                        {{-- EDIT LINK --}}
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $announcement->id }}">
                                            <i class="fas fa-edit fa-sm fa-fw text-gray-400 me-2"></i> Edit
                                        </a>
                                        {{-- DELETE FORM --}}
                                        <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" class="d-inline">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash fa-sm fa-fw text-danger me-2"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <p class="mb-3 text-dark">{{ $announcement->message }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                <small class="text-muted">
                                    <i class="fas fa-user-circle me-1"></i> {{ $announcement->author->name ?? 'Unknown' }}
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i> {{ $announcement->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- EDIT MODAL --}}
                @if(auth()->id() == $announcement->created_by || auth()->user()->hasRole('SuperAdmin'))
                <div class="modal fade" id="editModal{{ $announcement->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Announcement</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('announcements.update', $announcement->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="fw-bold small">Title</label>
                                        <input type="text" name="title" class="form-control" value="{{ $announcement->title }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="fw-bold small">Target Audience</label>
                                        <select name="target_role" class="form-select">
                                            <option value="" {{ $announcement->target_role == null ? 'selected' : '' }}>Everyone</option>
                                            <option value="Student" {{ $announcement->target_role == 'Student' ? 'selected' : '' }}>Students Only</option>
                                            <option value="Parent" {{ $announcement->target_role == 'Parent' ? 'selected' : '' }}>Parents Only</option>
                                            <option value="Teacher" {{ $announcement->target_role == 'Teacher' ? 'selected' : '' }}>Staff Only</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="fw-bold small">Message</label>
                                        <textarea name="message" class="form-control" rows="4" required>{{ $announcement->message }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                @empty
                <div class="col-12 text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-bullhorn fa-3x text-gray-300"></i>
                    </div>
                    <h5 class="text-muted">No announcements yet.</h5>
                </div>
                @endforelse
            </div>
            
        </main>
    </div>
</div>
@endsection