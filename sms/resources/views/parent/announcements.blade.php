@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('parent.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h3 class="fw-bold mb-4">Announcements & News</h3>

            @if($announcements->isEmpty())
                <div class="alert alert-info text-center py-4">
                    <i class="fas fa-bullhorn fa-2x mb-3 opacity-50"></i>
                    <p class="mb-0">No announcements posted yet.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($announcements as $announcement)
                        <div class="col-lg-12">
                            <div class="card shadow-sm border-0 border-start border-4 border-info">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title fw-bold mb-0">{{ $announcement->title }}</h5>
                                        <small class="text-muted">{{ $announcement->created_at->format('M d, Y') }}</small>
                                    </div>
                                    
                                    <h6 class="text-primary small mb-3">
                                        Target: {{ $announcement->classLevel ? $announcement->classLevel->name : 'General / All School' }}
                                    </h6>
                                    
                                    <p class="card-text">{{ $announcement->message ?? $announcement->content }}</p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-3 border-top pt-3">
                                        <small class="text-muted">Posted by: {{ $announcement->creator->name ?? 'Admin' }}</small>
                                        {{-- If you have attachments --}}
                                        @if($announcement->attachment_path)
                                            <a href="#" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-paperclip me-1"></i> Attachment
                                            </a>
                                        @endif
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