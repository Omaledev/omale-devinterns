@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('student.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h3 class="fw-bold mb-4">My Academic Results</h3>

            @if($results->isEmpty())
                <div class="alert alert-info d-flex align-items-center">
                    <i class="fas fa-info-circle fa-2x me-3"></i>
                    <div>
                        <h5 class="mb-0">No Results Found</h5>
                        <p class="mb-0 small">Your grades have not been uploaded or published yet.</p>
                    </div>
                </div>
            @else
                <div class="accordion shadow-sm" id="resultsAccordion">
                    {{-- Loop through Results grouped by Term --}}
                    @foreach($results as $termName => $grades)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ Str::slug($termName) }}">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#collapse{{ Str::slug($termName) }}" 
                                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}" 
                                        aria-controls="collapse{{ Str::slug($termName) }}">
                                    <span class="fw-bold">{{ $termName }}</span> 
                                    <span class="text-muted ms-2 small">({{ $grades->first()->academicSession->name ?? 'Current Session' }})</span>
                                </button>
                            </h2>
                            <div id="collapse{{ Str::slug($termName) }}" 
                                 class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" 
                                 aria-labelledby="heading{{ Str::slug($termName) }}" 
                                 data-bs-parent="#resultsAccordion">
                                
                                <div class="accordion-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover mb-0">
                                            <thead class="bg-light text-uppercase small fw-bold">
                                                <tr>
                                                    <th class="ps-4">Subject</th>
                                                    <th class="text-center">C.A. Score</th>
                                                    <th class="text-center">Exam Score</th>
                                                    <th class="text-center">Total</th>
                                                    <th class="text-center">Grade</th>
                                                    <th class="pe-4">Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($grades as $grade)
                                                    <tr>
                                                        <td class="ps-4 fw-bold">{{ $grade->subject->name }}</td>
                                                        <td class="text-center">{{ $grade->ca_score ?? '-' }}</td>
                                                        <td class="text-center">{{ $grade->exam_score ?? '-' }}</td>
                                                        <td class="text-center fw-bold">{{ $grade->total_score }}</td>
                                                        <td class="text-center">
                                                            @php
                                                                // Determining Badge Color based on Score
                                                                $badgeColor = 'secondary';
                                                                if($grade->total_score >= 70) $badgeColor = 'success';
                                                                elseif($grade->total_score >= 50) $badgeColor = 'primary';
                                                                elseif($grade->total_score >= 40) $badgeColor = 'warning';
                                                                else $badgeColor = 'danger';
                                                            @endphp
                                                            <span class="badge bg-{{ $badgeColor }} rounded-pill px-3">
                                                                {{ $grade->grade }}
                                                            </span>
                                                        </td>
                                                        <td class="pe-4 small text-muted">{{ $grade->remark ?? 'No remark' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                                        
                                        {{-- Left Side: Statistics --}}
                                        <div>
                                            <div class="small text-muted">
                                                Total Subjects: <strong>{{ $grades->count() }}</strong>
                                            </div>
                                            <div class="small">
                                                Average: <strong>{{ number_format($grades->avg('total_score'), 1) }}%</strong>
                                            </div>
                                        </div>

                                        {{-- Right Side: Download Button --}}
                                        @php
                                            $firstGrade = $grades->first();
                                        @endphp
                                        
                                        @if($firstGrade)
                                            <a href="{{ route('student.results.print', [
                                                    'term_id' => $firstGrade->term_id, 
                                                    'session_id' => $firstGrade->academic_session_id
                                                ]) }}" 
                                               target="_blank" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-print me-1"></i> Download / Print
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