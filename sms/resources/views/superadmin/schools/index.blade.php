@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        
        {{-- Sidebar --}}
        @include('superadmin.partials.sidebar')

        {{-- Main Content Area --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            {{-- Header --}}
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center mb-4">
                <h2 class="mb-2 mb-md-0 me-md-3">Schools Management</h2>
                <div class="ms-md-auto d-flex flex-wrap gap-2">
                    <a href="{{ route('superadmin.dashboard') }}" class="btn btn-dark ms-2">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                    <a href="{{ route('superadmin.schools.create') }}" class="btn btn-primary"> 
                    <i class="fas fa-plus me-1"></i>Add New School</a>
                </div>
            </div>

            {{-- School Switcher --}}
            @if(auth()->user()->hasRole('SuperAdmin'))
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Active School: {{ session('active_school') ? App\Models\School::find(session('active_school'))->name : 'All Schools' }}</h5>
                            <form method="GET" class="d-inline">
                                <select name="school_id" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                                    <option value="">All Schools</option>
                                    @foreach(App\Models\School::all() as $school)
                                        <option value="{{ $school->id }}" {{ session('active_school') == $school->id ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Table --}}
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th> 
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schools as $school)
                                    <tr class="{{ !$school->is_active ? 'table-secondary text-muted' : '' }}">
                                        <td>{{ $school->id }}</td>
                                        <td>
                                            {{ $school->name }}
                                            @if(!$school->is_active)
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $school->email }}</td>
                                        <td>{{ $school->phone ?? 'N/A' }}</td>
                                        
                                        {{-- Status Badge Column --}}
                                        <td>
                                            @if($school->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('superadmin.schools.show', $school) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('superadmin.schools.edit', $school) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                {{-- Toggle Status Button--}}
                                                <form action="{{ route('superadmin.schools.toggle-status', $school->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    
                                                    @if($school->is_active)
                                                        {{-- Button to Deactivate --}}
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('Are you sure you want to deactivate this school? Users will not be able to log in, but data will be kept.')"
                                                                title="Deactivate School">
                                                            <i class="fas fa-power-off"></i> Disable
                                                        </button>
                                                    @else
                                                        {{-- Button to Activate --}}
                                                        <button type="submit" class="btn btn-sm btn-success" 
                                                                title="Activate School">
                                                            <i class="fas fa-power-off"></i> Enable
                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No schools found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>
@endsection