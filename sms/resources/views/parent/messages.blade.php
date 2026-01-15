@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('parent.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">Messages</h3>
                <button class="btn btn-primary">
                    <i class="fas fa-pen me-2"></i> Compose New
                </button>
            </div>

            <div class="card shadow-sm">
                <div class="list-group list-group-flush">
                    @forelse($messages as $message)
                        <a href="#" class="list-group-item list-group-item-action p-3 {{ is_null($message->read_at) && $message->recipient_id == auth()->id() ? 'bg-light fw-bold' : '' }}">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 text-primary">
                                    {{ $message->sender_id == auth()->id() ? 'Me' : $message->sender->name }}
                                    <i class="fas fa-arrow-right mx-2 text-muted small"></i>
                                    {{ $message->recipient_id == auth()->id() ? 'Me' : $message->recipient->name }}
                                </h6>
                                <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 text-dark">{{ Str::limit($message->subject ?? $message->body, 80) }}</p>
                            <small class="text-muted">Click to view conversation</small>
                        </a>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted">Your inbox is empty.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</div>
@endsection