@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- SIDEBAR --}}
        @include('bursar.partials.sidebar')

        {{-- MAIN CONTENT WRAPPER --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            
            {{-- Header Section --}}
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 text-danger">Outstanding Fees</h1>
                <a href="{{ route('bursar.reports.export_debtors') }}" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-file-export me-1"></i> Export List
                </a>
            </div>

            {{-- Table Section --}}
            <div class="card shadow border-left-danger">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Invoice #</th>
                                    <th>Total Fees</th>
                                    <th>Paid</th>
                                    <th>Balance Due</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($debtors as $invoice)
                                <tr>
                                    <td class="fw-bold">{{ $invoice->student->name ?? 'N/A' }}</td>
                                    <td>{{ $invoice->student->studentProfile->classLevel->name ?? '-' }}</td>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>₦{{ number_format($invoice->total_amount, 2) }}</td>
                                    <td class="text-success">₦{{ number_format($invoice->paid_amount, 2) }}</td>
                                    <td class="text-danger fw-bold">₦{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</td>
                                    <td>
                                        <a href="{{ route('bursar.payments.create', $invoice->id) }}" class="btn btn-sm btn-success">
                                            Pay Now
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-success py-4">
                                        <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                                        No outstanding fees found!
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $debtors->links() }}
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>
@endsection