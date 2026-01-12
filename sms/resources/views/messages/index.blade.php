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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">My Messages</h1>
                
                {{-- "New Message" Button (Triggers Modal) --}}
                <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#newMessageModal">
                    New Message
                </button>
            </div>

            {{-- Inbox List --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Inbox</h6>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($threads as $thread)
                        @php
                            // Finding the "Other Person" in the chat
                            $otherUser = $thread->participants->where('id', '!=', auth()->id())->first();
                            
                            // Checking if the latest message is unread (and sent by them, not me)
                            $isUnread = $thread->latestMessage && 
                                        $thread->latestMessage->user_id != auth()->id() && 
                                        $thread->latestMessage->read_at == null;
                        @endphp

                        <a href="{{ route('messages.show', $thread->id) }}" class="list-group-item list-group-item-action p-3 {{ $isUnread ? 'bg-light border-start border-primary border-4' : '' }}">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    {{-- Avatar / Icon --}}
                                    <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fas fa-user {{ $isUnread ? 'text-primary' : 'text-secondary' }}"></i>
                                    </div>
                                    
                                    {{-- Name & Subject --}}
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">
                                            {{ $otherUser ? $otherUser->name : 'Unknown User' }}
                                            <span class="small text-muted fw-normal">({{ $otherUser->role ?? 'N/A' }})</span>
                                        </h6>
                                        <small class="text-muted">{{ $thread->subject ?? 'Conversation' }}</small>
                                    </div>
                                </div>

                                {{-- Date & Action Button --}}
                                <div class="d-flex flex-column align-items-end">
                                    <small class="mb-1 {{ $isUnread ? 'text-primary fw-bold' : 'text-muted' }}">
                                        {{ $thread->updated_at->diffForHumans() }}
                                    </small>
                                    
                                    <span class="badge rounded-pill {{ $isUnread ? 'bg-primary' : 'bg-light text-secondary border' }}">
                                        Reply <i class="fas fa-arrow-right ms-1"></i>
                                    </span>
                                </div>
                            </div>

                            {{-- Message Preview --}}
                            <div class="d-flex justify-content-between align-items-center mt-2 ps-5">
                                <p class="mb-0 text-muted small text-truncate" style="max-width: 80%;">
                                    @if($thread->latestMessage && $thread->latestMessage->user_id == auth()->id())
                                        <i class="fas fa-reply me-1 text-xs"></i> 
                                    @endif
                                    {{ $thread->latestMessage->body ?? 'No messages yet' }}
                                </p>
                                
                                @if($isUnread)
                                    <span class="badge bg-primary rounded-pill">New</span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-inbox fa-3x text-gray-300"></i>
                            </div>
                            <p class="text-muted">You have no messages yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </main>
    </div>
</div>

{{-- MESSAGE MODAL --}}
<div class="modal fade" id="newMessageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Start Conversation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    
                    {{-- RECIPIENT DROPDOWN --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Recipient</label>
                        <select name="recipient_id" class="form-select" required>
                            <option value="">Select User...</option>
                            @foreach($potentialRecipients as $recipient)
                                <option value="{{ $recipient->id }}">
                                    {{ $recipient->name }} ({{ $recipient->role }})
                                </option>
                            @endforeach
                        </select>
                        @if($potentialRecipients->isEmpty())
                            <div class="alert alert-warning mt-2 small">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                No users found that you are allowed to message.
                            </div>
                        @endif
                    </div>

                    {{-- Subject --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Subject (Optional)</label>
                        <input type="text" name="subject" class="form-control" placeholder="e.g. Question about Homework">
                    </div>

                    {{-- Message Body --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Message</label>
                        <textarea name="body" class="form-control" rows="4" placeholder="Type your message..." required></textarea>
                    </div>

                    {{-- Attachment --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Attachment</label>
                        <input type="file" name="attachment" class="form-control">
                        <div class="form-text small">Max size: 2MB. Allowed: PDF, JPG, PNG, DOCX.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection