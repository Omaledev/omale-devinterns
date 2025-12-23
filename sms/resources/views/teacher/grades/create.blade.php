@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Grading: {{ $subject->name }} - {{ $class->name }}</h3>
        <div>
            <span class="badge bg-info">{{ $session->name }}</span>
            <span class="badge bg-secondary">{{ $term->name }}</span>
            @if($isLocked)
                <span class="badge bg-danger"><i class="fas fa-lock"></i> LOCKED</span>
            @endif
        </div>
    </div>

    <form action="{{ route('teacher.grades.store') }}" method="POST">
        @csrf
        {{-- Hidden Fields for Context --}}
        <input type="hidden" name="class_level_id" value="{{ $class->id }}">
        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
        <input type="hidden" name="academic_session_id" value="{{ $session->id }}">
        <input type="hidden" name="term_id" value="{{ $term->id }}">

        <div class="card shadow">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 250px;">Student Name</th>
                                
                                {{-- Loop through Assessment Columns (e.g., CA1, Exam) --}}
                                @foreach($assessmentTypes as $type)
                                    <th class="text-center">
                                        {{ $type->name }} <br>
                                        <small class="text-white-50">({{ $type->weight }}%)</small>
                                    </th>
                                @endforeach
                                
                                <th class="text-center bg-secondary">Total (100%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr class="student-row">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $student->name }}</strong><br>
                                    <small class="text-muted">{{ $student->admission_number }}</small>
                                </td>

                                {{-- Inputs for Scores --}}
                                @foreach($assessmentTypes as $type)
                                    @php
                                        // Find existing score for this student & assessment type
                                        $score = $existingGrades->where('student_id', $student->id)
                                                                ->where('assessment_type_id', $type->id)
                                                                ->first()
                                                                ->score ?? '';
                                    @endphp
                                    <td>
                                        <input type="number" 
                                               name="grades[{{ $student->id }}][{{ $type->id }}]" 
                                               class="form-control text-center score-input" 
                                               value="{{ $score }}" 
                                               min="0" 
                                               max="{{ $type->max_score }}"
                                               {{ $isLocked ? 'disabled' : '' }}>
                                    </td>
                                @endforeach

                                {{-- Auto-calculated Total --}}
                                <td class="text-center fw-bold fs-5 total-score">0</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if(!$isLocked)
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" name="action" value="save" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Draft
                        </button>
                        <button type="submit" name="action" value="lock" class="btn btn-danger" 
                                onclick="return confirm('Are you sure? Once locked, you cannot edit grades.')">
                            <i class="fas fa-lock me-1"></i> Save & Lock Results
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </form>
</div>

{{-- JavaScript for Auto-Calculation --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to calculate totals
        function calculateTotals() {
            document.querySelectorAll('.student-row').forEach(row => {
                let total = 0;
                row.querySelectorAll('.score-input').forEach(input => {
                    let val = parseFloat(input.value) || 0;
                    total += val;
                });
                
                // Update the total cell
                row.querySelector('.total-score').textContent = total;
                
                // Optional: Color code score (Red if below 50)
                if(total < 50) {
                    row.querySelector('.total-score').classList.add('text-danger');
                } else {
                    row.querySelector('.total-score').classList.remove('text-danger');
                }
            });
        }

        // Run initially
        calculateTotals();

        // Listen for changes
        document.querySelectorAll('.score-input').forEach(input => {
            input.addEventListener('input', calculateTotals);
        });
    });
</script>
@endsection