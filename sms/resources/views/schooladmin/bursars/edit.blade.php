@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        @include('schooladmin.partials.sidebar')

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Header -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">Edit Bursar</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schooladmin.bursars.index') }}">Bursars</a></li>
                            <li class="breadcrumb-item active">Edit {{ $bursar->name }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('schooladmin.bursars.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to List
                    </a>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                Edit Bursar Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('schooladmin.bursars.update', $bursar) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Full Name *</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   id="name" name="name" value="{{ old('name', $bursar->name) }}"
                                                   placeholder="Enter bursar's full name" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address *</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                   id="email" name="email" value="{{ old('email', $bursar->email) }}"
                                                   placeholder="Enter email address" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="employee_id" class="form-label">Employee ID *</label>
                                            <input type="text" class="form-control @error('employee_id') is-invalid @enderror"
                                                   id="employee_id" name="employee_id" 
                                                   value="{{ old('employee_id', $bursar->employee_id) }}"
                                                   placeholder="e.g., BUR2024001" required>
                                            @error('employee_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                   id="phone" name="phone" value="{{ old('phone', $bursar->phone) }}"
                                                   placeholder="Enter phone number">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror"
                                              id="address" name="address" rows="3"
                                              placeholder="Enter bursar's address">{{ old('address', $bursar->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved"
                                               value="1" {{ old('is_approved', $bursar->is_approved) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_approved">
                                            Active Bursar
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('schooladmin.bursars.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        Update Bursar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection