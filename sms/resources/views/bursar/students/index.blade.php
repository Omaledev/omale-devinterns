@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Sidebar --}}
        @include('bursar.partials.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <h1 class="h2 text-gray-800 fw-bold">Student List</h1>
                <form action="{{ route('bursar.students.index') }}" method="GET" class="d-flex">
                    <div class="input-group input-group-sm">
                        <input type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Search name or email..." 
                            value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3">Name</th>
                                    <th>Class</th>
                                    <th>Parent/Guardian</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px;">
                                                {{ substr($student->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $student->name }}</div>
                                                <div class="small text-muted">{{ $student->email ?? 'No Email' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        
                                        <span class="badge bg-light text-dark border">
                                            {{ $student->studentProfile?->classLevel->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    
                                    <td>
                                        {{ $student->studentProfile?->parents->first()->name ?? 'Not Assigned' }}
                                    </td>

                                    <td><span class="badge bg-success bg-opacity-10 text-success">Active</span></td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('finance.invoices.index', ['student_id' => $student->id]) }}" 
                                           class="btn btn-sm btn-outline-primary rounded-pill">
                                            View Finance
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">No students found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    {{ $students->links() }}
                </div>
            </div>

        </main>
    </div>
</div>
@endsection