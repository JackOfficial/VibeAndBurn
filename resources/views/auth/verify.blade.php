@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center my-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    
                     @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success small">
                <i class="fas fa-check-circle"></i>  
                A new verification link has been sent to your email!
            </div>
        @endif
        
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="/email/verification-notification">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form>
                    
                    <div class="mt-3">
            <a href="/login" class="text-decoration-none">
                <i class="fas fa-sign-in-alt"></i> Back to Login
            </a>
        </div>
        
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
