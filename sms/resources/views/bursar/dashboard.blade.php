@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Bursar Dashboard</div>
                <div class="card-body">
                    <h4>Welcome, {{ Auth::user()->name }} to Axia Bursar Dashboard</h4>
                    <p>You have access to the following  features.</p>
                    
                    <div class="mt-4">
                        <a href="" class="btn btn-primary">Manage Bursary</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
