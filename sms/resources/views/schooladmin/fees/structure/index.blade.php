@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Responsive Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Fee Structures</h1>
    
    <div class="d-flex gap-2">
        {{-- Removed 'd-none' so text always shows --}}
        <a href="{{ route('finance.fee-structures.create') }}" class="btn btn-primary btn-sm shadow-sm text-nowrap">
            <i class="fas fa-plus"></i> Add Fee
        </a>
        <a href="{{ route('schooladmin.dashboard') }}" class="btn btn-secondary btn-sm shadow-sm text-nowrap">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Current Fee List</h6>
        </div>
        <div class="card-body">
            {{-- Responsive Table Wrapper --}}
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Term</th>
                            <th>Amount</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fees as $fee)
                        <tr>
                            <td class="align-middle">{{ $fee->name }}</td>
                            <td class="align-middle">{{ $fee->classLevel->name }}</td>
                            <td class="align-middle">{{ $fee->term->name }}</td>
                            <td class="align-middle fw-bold text-success">₦{{ number_format($fee->amount, 2) }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    {{-- Edit Button --}}
                                    <a href="{{ route('finance.fee-structures.edit', $fee->id) }}" class="btn btn-sm btn-info text-white" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    {{-- Delete Button --}}
                                    <form action="{{ route('finance.fee-structures.destroy', $fee->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger rounded-0 rounded-end" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No fee structures defined yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection