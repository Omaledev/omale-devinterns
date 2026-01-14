@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('student.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold">Study Materials</h3>
                    <p class="text-muted mb-0">Download books and notes uploaded by your teachers.</p>
                </div>
            </div>

            {{-- Books Grid --}}
            @if($books->isEmpty())
                <div class="alert alert-info py-4 text-center">
                    <i class="fas fa-book-open fa-3x mb-3 opacity-50"></i>
                    <h5>No Books Found</h5>
                    <p class="mb-0">There are no study materials uploaded for your class yet.</p>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($books as $book)
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0 hover-shadow transition-all">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            {{ $book->subject->name ?? 'General' }}
                                        </span>
                                        <small class="text-muted">{{ $book->created_at->diffForHumans() }}</small>
                                    </div>

                                    <h5 class="card-title fw-bold text-dark mb-2">
                                        {{ $book->title }}
                                    </h5>
                                    
                                    <p class="card-text text-muted small mb-3">
                                        {{ Str::limit($book->description ?? 'No description provided.', 80) }}
                                    </p>

                                    <div class="d-flex align-items-center small text-muted mb-3">
                                        <i class="fas fa-user-circle me-1"></i>
                                        <span>{{ $book->teacher->name ?? 'Teacher' }}</span>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-white border-top-0 pt-0 pb-3">
                                    <div class="d-grid">
                                        <a href="{{ route('student.books.download', $book->id) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-download me-2"></i> Download File
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $books->links() }}
                </div>
            @endif

        </main>
    </div>
</div>
@endsection