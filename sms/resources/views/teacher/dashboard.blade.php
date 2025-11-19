@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Teacher Dashboard</div>
                <div class="card-body">
                    <h4>Welcome, {{ Auth::user()->name }} to Axia SMS Teachers Dashboard</h4>
                    <p>You have access to the following features.</p>
                     <p>Select your school below to continue</p>
                    <div class="mt-4">
                        <a href="" class="btn btn-primary">Manage Students</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
