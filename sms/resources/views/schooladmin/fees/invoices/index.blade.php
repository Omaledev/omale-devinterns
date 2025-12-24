@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Student Invoices</h1>
        
        <div class="d-flex gap-2">
            <a href="{{ route('finance.invoices.generate') }}" class="btn btn-success btn-sm shadow-sm text-nowrap">
                <i class="fas fa-cogs"></i> Generate
            </a>
            <a href="{{ route('schooladmin.dashboard') }}" class="btn btn-secondary btn-sm shadow-sm text-nowrap">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

   <div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Student</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $inv)
                    <tr>
                        <td class="text-nowrap">{{ $inv->invoice_number }}</td>
                        <td class="text-nowrap">{{ $inv->student->name }}</td>
                        <td>₦{{ number_format($inv->total_amount, 2) }}</td>
                        <td>₦{{ number_format($inv->paid_amount, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $inv->status == 'PAID' ? 'success' : ($inv->status == 'PARTIAL' ? 'warning' : 'danger') }}">
                                {{ $inv->status }}
                            </span>
                        </td>
                        <td>
                            {{-- BUTTON GROUP FOR VIEW AND DELETE --}}
                            <div class="d-flex gap-1">
                                <a href="{{ route('finance.invoices.show', $inv->id) }}" class="btn btn-sm btn-info text-white" title="View">
                                    <i class="fas fa-eye"></i> View
                                </a>

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
        
        <div class="mt-3">
            {{ $invoices->links() }}
        </div>
    </div>
</div>
</div>
@endsection