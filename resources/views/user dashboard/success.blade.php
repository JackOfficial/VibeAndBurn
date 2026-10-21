@extends('user dashboard.dashboard')
@section('title', 'Vibe and burn | Payment Successful')
@section('content')

<div class="nk-content-body">
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">Fund Addition Successful</h3>
            </div>
        </div>
    </div><!-- .nk-block-head -->

    <div class="nk-block">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <div class="card card-bordered shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="text-success mb-3">
                            <em class="icon ni ni-check-circle" style="font-size: 3rem;"></em>
                        </div>
                        <h4 class="title text-dark">Payment Request Initiated!</h4>
                        <p class="text-muted mt-2">
                            @if(session()->has('message'))
                                {{ session()->get('message') }}
                            @else
                                Your payment request has been sent to your phone. Please check your device to authorize and complete the transaction.
                            @endif
                        </p>

                        <div class="alert alert-warning mt-4 text-left small" role="alert">
                            <em class="icon ni ni-alert-circle mr-1"></em> 
                            Funds will automatically reflect in your wallet balance once the mobile money authorization is completed.
                        </div>

                        <div class="nk-block-actions mt-4">
                            <a href="{{ route('intouch.sample.checkout') }}" class="btn btn-primary mr-2">
                                <em class="icon ni ni-plus-circle mr-1"></em> Add More Funds
                            </a>
                            <a href="/newOrder/create" class="btn btn-outline-light">
                                <em class="icon ni ni-dashboard mr-1"></em> Make a New Order
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection