@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- 1. INCLUDE SIDEBAR --}}
        @include('bursar.partials.sidebar')

        {{-- 2. MAIN CONTENT WRAPPER --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            
            {{-- Header Section --}}
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Collection Report</h1>
                <form action="{{ route('bursar.reports.collections') }}" method="GET" class="d-flex">
                    <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="this_year" {{ $filter == 'this_year' ? 'selected' : '' }}>This Year</option>
                        <option value="this_month" {{ $filter == 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="last_month" {{ $filter == 'last_month' ? 'selected' : '' }}>Last Month</option>
                    </select>
                </form>
            </div>

            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-success text-white shadow h-100">
                        <div class="card-body">
                            <div class="text-uppercase small fw-bold text-white-50">Total Period Collection</div>
                            <div class="h2 mb-0 fw-bold">₦{{ number_format($totalCollected, 2) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-primary text-white shadow h-100">
                        <div class="card-body">
                            <div class="text-uppercase small fw-bold text-white-50">Transactions Count</div>
                            <div class="h2 mb-0 fw-bold">{{ $transactionCount }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Chart Section --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-white">
                    <h6 class="m-0 fw-bold text-primary">Revenue Chart</h6>
                </div>
                <div class="card-body">
                    <div style="height: 400px;">
                        <canvas id="collectionsChart"></canvas>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('collectionsChart');
    const chartLabels = {!! json_encode($chartData->keys()) !!};
    const chartValues = {!! json_encode($chartData->values()) !!};

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Revenue (₦)',
                data: chartValues,
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush