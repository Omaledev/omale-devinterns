@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('teacher.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">My Digital Library</h3>
                <a href="{{ route('teacher.books.create') }}" class="btn btn-primary">
                    <i class="fas fa-upload me-2"></i> Upload New Material
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Document</th>
                                <th>Class & Subject</th>
                                <th>Size</th>
                                <th>Date Uploaded</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($books as $book)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 text-danger">
                                                <i class="fas fa-file-{{ $book->file_type == 'pdf' ? 'pdf' : 'alt' }} fa-2x"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $book->title }}</h6>
                                                <small class="text-muted">{{ Str::limit($book->description, 40) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $book->classLevel->name }}</span>
                                        <span class="badge bg-secondary">{{ $book->subject->name }}</span>
                                    </td>
                                    <td class="small">{{ $book->file_size }} KB</td>
                                    <td class="small">{{ $book->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ asset('storage/' . $book->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <form action="{{ route('teacher.books.destroy', $book) }}" method="POST" onsubmit="return confirm('Delete this file?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No books uploaded yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $books->links() }}
                </div>
            </div>
        </main>
    </div>
</div>
@endsection