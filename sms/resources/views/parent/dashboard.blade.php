@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Parents Dashboard</div>
                <div class="card-body">
                    <h4>Welcome, {{ Auth::user()->name }} to Axia SMS Parents Dashboard</h4>
                    <p>You have access to the following features.</p>
                    <div class="mt-4">
                        <a href="" class="btn btn-primary">Select your child School</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
