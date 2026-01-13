@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('teacher.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">Edit Assignment</h3>
                <a href="{{ route('teacher.assignments.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Cancel
                </a>
            </div>

            <div class="card shadow-sm col-md-9 mx-auto">
                <div class="card-body">
                    <form action="{{ route('teacher.assignments.update', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') 
                        
                        {{-- Title --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Assignment Title</label>
                            <input type="text" name="title" class="form-control" 
                                   value="{{ old('title', $assignment->title) }}" required>
                        </div>

                        {{-- Class & Subject Selection --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Target Class & Subject</label>
                            <select name="classroom_assignment_id" class="form-select" required>
                                <option value="">-- Choose Class --</option>
                                @foreach($assignedClasses as $allocation)
                                    @php
                                        // Check if this allocation matches the assignment's current data
                                        $isSelected = 
                                            $allocation->class_level_id == $assignment->class_level_id &&
                                            $allocation->subject_id == $assignment->subject_id &&
                                            $allocation->section_id == $assignment->section_id;
                                    @endphp
                                    <option value="{{ $allocation->id }}" {{ $isSelected ? 'selected' : '' }}>
                                        {{ $allocation->classLevel->name }} 
                                        @if($allocation->section) ({{ $allocation->section->name }}) @endif
                                        - {{ $allocation->subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">
                                Changing this will move the assignment to the new class/subject.
                            </div>
                        </div>

                        {{-- Deadline --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Submission Deadline</label>
                            <input type="date" name="deadline" class="form-control" 
                                   value="{{ old('deadline', $assignment->deadline) }}" required>
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Instructions / Questions</label>
                            <textarea name="description" class="form-control" rows="6" required>{{ old('description', $assignment->description) }}</textarea>
                        </div>

                        {{-- File Attachment --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Update Attachment (Optional)</label>
                            <input type="file" name="file" class="form-control">
                            @if($assignment->file_path)
                                <div class="mt-2 small">
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Current File:</span> 
                                    <a href="{{ asset('storage/' . $assignment->file_path) }}" target="_blank">View File</a>
                                </div>
                            @endif
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="fas fa-save me-2"></i> Update Assignment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection