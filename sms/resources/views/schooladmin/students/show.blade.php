@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">Student Details</h1>
                <div>
                    <a href="{{ route('schooladmin.students.index') }}" class="btn btn-secondary">Back to Students</a>
                    <a href="{{ route('schooladmin.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ $student->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Admission Number:</th>
                                    <td>{{ $student->admission_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $student->email }}</td>
                                </tr>
                                <tr>
                                    <th>School:</th>
                                    <td>{{ $student->school->name }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($student->is_approved)
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-warning">Pending Approval</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('schooladmin.students.edit', $student) }}" class="btn btn-warning">Edit Student</a>
                        <form action="{{ route('schooladmin.students.destroy', $student) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete Student</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection