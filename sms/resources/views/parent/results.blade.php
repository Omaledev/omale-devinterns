@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('parent.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h3 class="fw-bold mb-4">Academic Results</h3>

            {{-- Child Selector Tabs --}}
            <ul class="nav nav-tabs mb-4" id="resultsTabs" role="tablist">
                @foreach($children as $index => $child)
                    <li class="nav-item">
                        <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                id="tab-{{ $child->id }}" 
                                data-bs-toggle="tab" 
                                data-bs-target="#content-{{ $child->id }}" 
                                type="button">
                            {{ $child->name }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach($children as $index => $child)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="content-{{ $child->id }}">
                        
                        @if($child->grades->isEmpty())
                            <div class="alert alert-info">
                                No academic records found for {{ $child->name }} yet.
                            </div>
                        @else
                            {{-- Grouping Grades by Session/Term --}}
                            @php
                                $groupedGrades = $child->grades->groupBy(function($grade) {
                                    return ($grade->academicSession->name ?? 'Unknown Session') . ' - ' . ($grade->term->name ?? 'Unknown Term');
                                });
                            @endphp

                            <div class="accordion" id="accordion-{{ $child->id }}">
                                @foreach($groupedGrades as $groupName => $grades)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-{{ Str::slug($groupName) }}-{{ $child->id }}">
                                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#collapse-{{ Str::slug($groupName) }}-{{ $child->id }}">
                                                <strong>{{ $groupName }}</strong>
                                            </button>
                                        </h2>
                                        <div id="collapse-{{ Str::slug($groupName) }}-{{ $child->id }}" 
                                             class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}">
                                            <div class="accordion-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table table-striped mb-0">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th>Subject</th>
                                                                <th class="text-center">CA</th>
                                                                <th class="text-center">Exam</th>
                                                                <th class="text-center">Total</th>
                                                                <th class="text-center">Grade</th>
                                                                <th>Remark</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($grades as $grade)
                                                                <tr>
                                                                    <td class="fw-bold">{{ $grade->subject->name ?? 'N/A' }}</td>
                                                                    <td class="text-center">{{ $grade->ca_score ?? '-' }}</td>
                                                                    <td class="text-center">{{ $grade->exam_score ?? '-' }}</td>
                                                                    <td class="text-center fw-bold">{{ $grade->total_score }}</td>
                                                                    <td class="text-center">
                                                                        <span class="badge bg-{{ $grade->total_score >= 50 ? 'success' : 'danger' }}">
                                                                            {{ $grade->grade }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="small text-muted">{{ $grade->remark ?? '-' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </main>
    </div>
</div>
@endsection