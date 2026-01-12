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

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Inbox
                </a>
                
                {{-- Delete Thread Button --}}
                <form action="{{ route('messages.destroy', $thread->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this conversation?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm shadow-sm">
                        <i class="fas fa-trash me-1"></i> Delete Chat
                    </button>
                </form>
            </div>

            {{-- Chat Box Card --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                        <i class="fas fa-user text-primary"></i>
                    </div>
                    <h6 class="m-0 font-weight-bold text-primary">
                        {{ $thread->participants->where('id', '!=', auth()->id())->first()->name ?? 'Unknown User' }}
                    </h6>
                </div>
                
                {{-- Messages Area --}}
                <div class="card-body" style="height: 500px; overflow-y: auto; background: #f8f9fa;">
                    @foreach($thread->messages as $message)
                        @php $isMe = $message->user_id == auth()->id(); @endphp
                        
                        <div class="d-flex mb-3 {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}">
                            <div class="card shadow-sm {{ $isMe ? 'bg-primary text-white border-0' : 'bg-white border' }}" style="max-width: 75%; border-radius: 15px; {{ $isMe ? 'border-bottom-right-radius: 2px;' : 'border-bottom-left-radius: 2px;' }}">
                                <div class="card-body p-2 px-3">
                                    <p class="mb-1">{{ $message->body }}</p>
                                    
                                    {{-- Attachment Link --}}
                                    @if($message->attachment_path)
                                        <div class="mt-2 mb-1 p-2 rounded bg-white bg-opacity-25">
                                            <a href="{{ asset('storage/' . $message->attachment_path) }}" target="_blank" class="{{ $isMe ? 'text-white' : 'text-primary' }} text-decoration-none small">
                                                <i class="fas fa-paperclip me-1"></i> View Attachment
                                            </a>
                                        </div>
                                    @endif

                                    <div class="d-flex align-items-center justify-content-end">
                                        <small class="{{ $isMe ? 'text-white-50' : 'text-muted' }}" style="font-size: 0.7rem;">
                                            {{ $message->created_at->format('M d, h:i A') }}
                                        </small>
                                        @if($isMe && $message->read_at)
                                            <i class="fas fa-check-double ms-1 text-white-50" style="font-size: 0.6rem;"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Reply Form --}}
                <div class="card-footer bg-white py-3">
                    <form action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="thread_id" value="{{ $thread->id }}">
                        
                        <div class="input-group">
                            {{-- Attachment Toggle --}}
                            <label class="btn btn-light border" for="attachFile" title="Attach File">
                                <i class="fas fa-paperclip text-secondary"></i>
                            </label>
                            <input type="file" name="attachment" id="attachFile" class="d-none" onchange="this.previousElementSibling.classList.add('bg-info', 'text-white')">

                            <input type="text" name="body" class="form-control bg-light border-0" placeholder="Type a message..." required autocomplete="off">
                            
                            <button class="btn btn-primary px-4" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection