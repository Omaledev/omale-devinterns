@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        
        {{-- Sidebar --}}
        @if(auth()->user()->hasRole('Bursar'))
            @include('bursar.partials.sidebar')
        @else
            @include('schooladmin.partials.sidebar')
        @endif

        {{-- Main Content Area --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Generate Invoices</h1>
                <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Invoices
                </a>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="alert alert-info shadow-sm border-left-info">
                        <i class="fas fa-info-circle me-2"></i>
                        This tool will create invoices for <strong>every student</strong> in the selected Class for the selected Term.
                    </div>

                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Bulk Generation</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('finance.invoices.store') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label font-weight-bold">Target Class</label>
                                    <select name="class_level_id" class="form-select form-control" required>
                                        <option value="">Select Class</option>
                                        @foreach($classes as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label font-weight-bold">For Term</label>
                                    <select name="term_id" class="form-select form-control" required>
                                        <option value="">Select Term</option>
                                        @foreach($terms as $t)
                                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button class="btn btn-success w-100 py-2">
                                    <i class="fas fa-cogs me-1"></i> Generate Invoices
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