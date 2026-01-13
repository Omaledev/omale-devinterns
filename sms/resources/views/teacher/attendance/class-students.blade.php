@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Sidebar --}}
        @include('teacher.partials.sidebar')

        {{-- Main Content --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('teacher.my-classes') }}">My Classes</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $class->name }}</li>
                        </ol>
                    </nav>
                    <h1 class="h3 mb-0 text-gray-800">Students in {{ $class->name }}</h1>
                </div>
                <a href="{{ route('teacher.my-classes') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Classes
                </a>
            </div>

            {{-- Students Card --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 fw-bold text-primary">Class List ({{ $students->count() }} Students)</h6>
                </div>
                <div class="card-body">
                    @if($students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" width="100%" cellspacing="0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Student Name</th>
                                        <th>Admission No.</th>
                                        <th>Guardian Contact</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $index => $student)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2" style="width: 35px; height: 35px;">
                                                    {{ substr($student->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $student->name }}</div>
                                                    <div class="small text-muted">{{ $student->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $student->studentProfile->admission_number ?? 'N/A' }}</td>
                                        <td>
                                            {{-- Assuming parent relationship exists, otherwise generic placeholder --}}
                                            @if($student->parents && $student->parents->count() > 0)
                                                {{ $student->parents->first()->phone ?? 'N/A' }}
                                            @else
                                                <span class="text-muted small">Not Linked</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-info text-white">
                                                <i class="fas fa-eye"></i> Profile
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-graduate fa-3x text-gray-300 mb-3"></i>
                            <p class="text-gray-500 mb-0">No students found in this class yet.</p>
                        </div>
                    @endif
                </div>
            </div>

        </main>
    </div>
</div>
@endsection