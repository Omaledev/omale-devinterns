@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('teacher.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">Post New Assignment</h3>
                <a href="{{ route('teacher.assignments.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Cancel
                </a>
            </div>

            <div class="card shadow-sm col-md-9 mx-auto">
                <div class="card-body">
                    {{-- Form Starts Here --}}
                    <form action="{{ route('teacher.assignments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Title --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Assignment Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Algebra Homework #3" required>
                        </div>

                        {{-- Target Audience (Using AssignedClasses variable) --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Target Class & Subject <span class="text-danger">*</span></label>
                            <select name="classroom_assignment_id" class="form-select" required>
                                <option value="">-- Choose Class --</option>
                                {{-- Loop through the specific allocations for this teacher --}}
                                @foreach($assignedClasses as $allocation)
                                    <option value="{{ $allocation->id }}">
                                        {{ $allocation->classLevel->name }} 
                                        @if($allocation->section) 
                                            ({{ $allocation->section->name }}) 
                                        @endif
                                        - {{ $allocation->subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">You can only post assignments for classes you are assigned to teach.</div>
                            
                            @if($assignedClasses->isEmpty())
                                <div class="alert alert-warning mt-2 small">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    You have not been assigned to any subjects/classes yet. Please contact the admin.
                                </div>
                            @endif
                        </div>

                        {{-- Deadline --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Submission Deadline <span class="text-danger">*</span></label>
                            <input type="date" name="deadline" class="form-control" required>
                        </div>

                        {{-- Description Area --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Instructions / Questions <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="6" placeholder="Paste your questions or instructions here..." required></textarea>
                        </div>

                        {{-- Optional File --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Attachment (Optional)</label>
                            <input type="file" name="file" class="form-control">
                            <div class="form-text">PDF, DOCX, PPTX, Images (Max 5MB)</div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" {{ $assignedClasses->isEmpty() ? 'disabled' : '' }}>
                                Post Assignment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection