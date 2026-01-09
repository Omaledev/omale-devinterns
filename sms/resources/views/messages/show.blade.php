@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Inbox
        </a>
        
        {{-- Delete Thread Button --}}
        <form action="{{ route('messages.destroy', $thread->id) }}" method="POST" onsubmit="return confirm('Delete this conversation?');">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i> Delete Chat
            </button>
        </form>
    </div>

    {{-- Chat Box --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">
                Chat with {{ $thread->participants->where('id', '!=', auth()->id())->first()->name ?? 'User' }}
            </h6>
        </div>
        
        <div class="card-body" style="height: 400px; overflow-y: auto; background: #f8f9fa;">
            @foreach($thread->messages as $message)
                @php $isMe = $message->user_id == auth()->id(); @endphp
                
                <div class="d-flex mb-3 {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}">
                    <div class="card {{ $isMe ? 'bg-primary text-white' : 'bg-white border' }}" style="max-width: 75%;">
                        <div class="card-body p-2 px-3">
                            <p class="mb-1">{{ $message->body }}</p>
                            
                            {{-- Attachment Link --}}
                            @if($message->attachment_path)
                                <div class="mt-2 mb-1">
                                    <a href="{{ asset('storage/' . $message->attachment_path) }}" target="_blank" class="{{ $isMe ? 'text-white' : 'text-primary' }} text-decoration-underline small">
                                        <i class="fas fa-paperclip"></i> View Attachment
                                    </a>
                                </div>
                            @endif

                            <small class="{{ $isMe ? 'text-white-50' : 'text-muted' }}" style="font-size: 0.7rem;">
                                {{ $message->created_at->format('M d, h:i A') }}
                                @if($isMe && $message->read_at)
                                    <i class="fas fa-check-double ms-1"></i>
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Reply Form --}}
        <div class="card-footer bg-white">
            <form action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="thread_id" value="{{ $thread->id }}">
                
                <div class="input-group">
                    <input type="text" name="body" class="form-control bg-light border-0 small" placeholder="Type a message..." required>
                    
                    {{-- File Input Toggle --}}
                    <label class="btn btn-outline-secondary border-0" for="attachFile">
                        <i class="fas fa-paperclip"></i>
                    </label>
                    <input type="file" name="attachment" id="attachFile" class="d-none">

                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-paper-plane fa-sm"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection