@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">School Details - {{ $school->name }}</h4>
                <a href="{{ route('superadmin.schools.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Basic Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">ID:</th>
                                <td>{{ $school->id }}</td>
                            </tr>
                            <tr>
                                <th>Name:</th>
                                <td>{{ $school->name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $school->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $school->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Principal:</th>
                                <td>{{ $school->principal_name ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Address</h5>
                        <div class="border p-3 rounded bg-light">
                            {{ $school->address }}
                        </div>

                        <div class="mt-4">
                            <h5>User Management</h5>
                            <div class="d-grid gap-2">
                                <a href="{{ route('superadmin.schools.create-user', $school) }}" class="btn btn-success">
                                    <i class="fas fa-user-plus"></i> Create School User
                                </a>
                                <a href="{{ route('superadmin.schools.users', $school) }}" class="btn btn-info">
                                    <i class="fas fa-users"></i> View School Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <h5>School Actions</h5>
                    <div class="btn-group">
                        <a href="{{ route('superadmin.schools.edit', $school) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit School
                        </a>
                        <form action="{{ route('superadmin.schools.destroy', $school) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this school?')">
                                <i class="fas fa-trash"></i> Delete School
                            </button>
                        </form>
                        <a href="{{ route('superadmin.schools.index') }}" class="btn btn-primary">
                            <i class="fas fa-list"></i> View All Schools
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
