@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('bursar.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-gray-800">Verify Payment</h1>
                <a href="{{ route('bursar.payments.index') }}" class="btn btn-secondary btn-sm">Back</a>
            </div>

            <div class="row">
                {{-- DETAILS COLUMN --}}
                <div class="col-md-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-warning text-dark">
                            <h6 class="m-0 fw-bold">Payment Details</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Student:</td>
                                    <td>{{ $payment->invoice->student->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Amount Claimed:</td>
                                    <td class="text-success h5">₦{{ number_format($payment->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Reference:</td>
                                    <td>{{ $payment->reference_number }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Date Uploaded:</td>
                                    <td>{{ $payment->created_at->format('d M, Y h:i A') }}</td>
                                </tr>
                            </table>

                            <hr>

                            <form action="{{ route('bursar.payments.approve', $payment->id) }}" method="POST" class="d-grid gap-2 mb-2">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Are you sure you want to approve this payment? It will update the student invoice.')">
                                    <i class="fas fa-check-circle me-2"></i> Approve Payment
                                </button>
                            </form>

                            <form action="{{ route('bursar.payments.decline', $payment->id) }}" method="POST" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Reject this payment?')">
                                    Decline
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- PROOF COLUMN --}}
                <div class="col-md-7">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 fw-bold text-primary">Uploaded Proof</h6>
                        </div>
                        <div class="card-body text-center">
                            @if(Str::endsWith($payment->proof_file_path, '.pdf'))
                                <div class="alert alert-info">File is a PDF.</div>
                                <iframe src="{{ asset('storage/' . $payment->proof_file_path) }}" style="width:100%; height:500px;" frameborder="0"></iframe>
                            @else
                                <img src="{{ asset('storage/' . $payment->proof_file_path) }}" class="img-fluid rounded border" alt="Payment Proof">
                            @endif
                            
                            <div class="mt-3">
                                <a href="{{ asset('storage/' . $payment->proof_file_path) }}" target="_blank" class="btn btn-link">
                                    <i class="fas fa-external-link-alt me-1"></i> Open Original File
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection