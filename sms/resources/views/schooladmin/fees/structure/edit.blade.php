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
                <h1 class="h3 mb-0 text-gray-800">Edit Fee Structure</h1>
                <a href="{{ route('finance.fee-structures.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>

            {{-- Content --}}
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-white">
                            <h6 class="m-0 fw-bold text-primary">Edit Details</h6>
                        </div>
                        <div class="card-body">
                            {{-- Note the route uses update and passing the ID --}}
                            <form action="{{ route('finance.fee-structures.update', $feeStructure->id) }}" method="POST">
                                @csrf 
                                @method('PUT') 

                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Fee Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $feeStructure->name }}" placeholder="e.g. Tuition Fee" required>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-bold">Class Level</label>
                                        <select name="class_level_id" class="form-select" required>
                                            @foreach($classes as $c)
                                                <option value="{{ $c->id }}" {{ $feeStructure->class_level_id == $c->id ? 'selected' : '' }}>
                                                    {{ $c->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-bold">Term</label>
                                        <select name="term_id" class="form-select" required>
                                            @foreach($terms as $t)
                                                <option value="{{ $t->id }}" {{ $feeStructure->term_id == $t->id ? 'selected' : '' }}>
                                                    {{ $t->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label font-weight-bold">Amount (₦)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₦</span>
                                        <input type="number" name="amount" class="form-control" value="{{ $feeStructure->amount }}" min="0" step="0.01" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    Update Fee
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection