@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('schooladmin.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <h1 class="h2 mb-4">Assessment Configuration</h1>

            {{-- 1. PROGRESS BAR SECTION --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Total Weight Distribution</h5>
                    <p class="text-muted small">The total weight of all assessments must equal 100%.</p>

                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar {{ $totalWeight == 100 ? 'bg-success' : 'bg-primary' }}" 
                             role="progressbar" 
                             style="width: {{ $totalWeight }}%;" 
                             aria-valuenow="{{ $totalWeight }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ $totalWeight }}% Used
                        </div>
                    </div>
                    
                    @if($totalWeight < 100)
                        <div class="mt-2 text-warning fw-bold">
                            <i class="fas fa-exclamation-circle"></i> You still need to allocate {{ 100 - $totalWeight }}%
                        </div>
                    @elseif($totalWeight == 100)
                        <div class="mt-2 text-success fw-bold">
                            <i class="fas fa-check-circle"></i> Configuration Complete
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                {{-- 2. ADD FORM (Only visible if total < 100%) --}}
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0 fw-bold">Add New Assessment</h6>
                        </div>
                        <div class="card-body">
                            @if($totalWeight >= 100)
                                <div class="alert alert-success">
                                    <i class="fas fa-check"></i> Total weight has reached 100%. You cannot add more assessments unless you delete one.
                                </div>
                            @else
                                <form action="{{ route('schooladmin.assessments.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Assessment Name</label>
                                        <input type="text" name="name" class="form-control" placeholder="e.g. 1st C.A Test" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Weight (%)</label>
                                        <div class="input-group">
                                            <input type="number" name="weight" class="form-control" 
                                                   min="1" max="{{ 100 - $totalWeight }}" placeholder="Max: {{ 100 - $totalWeight }}" required>
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <small class="text-muted">Remaining: {{ 100 - $totalWeight }}%</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Max Score</label>
                                        <input type="number" name="max_score" class="form-control" value="100" required>
                                        <small class="text-muted">Maximum obtainable score for this test.</small>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-plus-circle me-1"></i> Add Assessment
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- 3. DISPLAY TABLE --}}
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0 fw-bold">Current Assessments</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Weight</th>
                                            <th>Max Score</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($assessments as $assessment)
                                            <tr>
                                                <td class="fw-bold">{{ $assessment->name }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $assessment->weight }}%</span>
                                                </td>
                                                <td>{{ $assessment->max_score }}</td>
                                                <td class="text-end">
                                                    <form action="{{ route('schooladmin.assessments.destroy', $assessment->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    No assessment types configured yet.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="table-light fw-bold">
                                        <tr>
                                            <td>TOTAL</td>
                                            <td class="{{ $totalWeight > 100 ? 'text-danger' : ($totalWeight == 100 ? 'text-success' : '') }}">
                                                {{ $totalWeight }}%
                                            </td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection