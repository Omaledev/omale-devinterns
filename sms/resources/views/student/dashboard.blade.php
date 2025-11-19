@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Students Dashboard</div>
                <div class="card-body">
                    <h4>Welcome, {{ Auth::user()->name }} to Axia SMS Students Dashboard</h4>
                    <p>Select your school below to continue</p>
                    <a href="">Select School</a>
                    <div class="mt-4">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
