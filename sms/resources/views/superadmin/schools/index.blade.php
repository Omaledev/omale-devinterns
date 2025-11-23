@extends('layouts.app')

@section('content')
<div class="row">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center mb-4">
                <h2 class=" ms-2 mb-2 mb-md-2 me-md-3">Schools Management</h2>
                <div class="ms-md-auto d-flex flex-wrap gap-2">
                    <a href="{{ route('superadmin.dashboard') }}" class="btn btn-dark ms-2">Back to Dashboard</a>
                    <a href="{{ route('superadmin.schools.create') }}" class="btn btn-primary">Add New School</a>
                </div>
        </div>

         {{-- School Switcher for SuperAdmin --}}
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
                                <th>Principal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schools as $school)
                                <tr>
                                    <td>{{ $school->id }}</td>
                                    <td>{{ $school->name }}</td>
                                    <td>{{ $school->email }}</td>
                                    <td>{{ $school->phone ?? 'N/A' }}</td>
                                    <td>{{ $school->principal_name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('superadmin.schools.show', $school) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('superadmin.schools.edit', $school) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('superadmin.schools.destroy', $school) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
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
    </div>
</div>
@endsection
