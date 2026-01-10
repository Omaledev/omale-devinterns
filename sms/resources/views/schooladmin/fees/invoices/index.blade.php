@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        
        {{-- 1. Include the Sidebar --}}
        @include('schooladmin.partials.sidebar')

        {{-- 2. Main Content Area --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Student Invoices</h1>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('finance.invoices.generate') }}" class="btn btn-success btn-sm shadow-sm text-nowrap">
                        <i class="fas fa-cogs me-1"></i> Generate
                    </a>
                    <a href="{{ route('schooladmin.dashboard') }}" class="btn btn-secondary btn-sm shadow-sm text-nowrap">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>

            {{-- Content Card --}}
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" width="100%" cellspacing="0">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Student</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Status</th>
                                    <th style="width: 150px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $inv)
                                <tr>
                                    <td class="text-nowrap align-middle">{{ $inv->invoice_number }}</td>
                                    <td class="text-nowrap align-middle">{{ $inv->student->name }}</td>
                                    <td class="align-middle">₦{{ number_format($inv->total_amount, 2) }}</td>
                                    <td class="align-middle">₦{{ number_format($inv->paid_amount, 2) }}</td>
                                    <td class="align-middle">
                                        <span class="badge bg-{{ $inv->status == 'PAID' ? 'success' : ($inv->status == 'PARTIAL' ? 'warning' : 'danger') }}">
                                            {{ $inv->status }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        {{-- BUTTON GROUP --}}
                                        <div class="d-flex gap-1">
                                            
                                            {{-- (Only show if not fully paid) --}}
                                            @if($inv->status !== 'PAID')
                                                <a href="{{ route('bursar.payments.create', $inv->id) }}" class="btn btn-sm btn-success text-white" title="Record Payment">
                                                    <i class="fas fa-credit-card"></i>
                                                </a>
                                            @endif

                                            {{-- View Button --}}
                                            <a href="{{ route('finance.invoices.show', $inv->id) }}" class="btn btn-sm btn-info text-white" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            {{-- Delete Button --}}
                                            <form action="{{ route('finance.invoices.destroy', $inv->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> 
                    
                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection