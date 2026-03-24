
@extends('layouts.app')
@section('content')

<div class="container mt-5 mb-5" style="padding-top: 70px;">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12 col-12">

        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                <h2 class="title">Register Account</h2>
                <p>If you already have an account with us, please <a href="{{route('login')}}">Login</a></p>
     <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="form-horizontal account-register clearfix">
        @csrf
        <p>
            <a href="{{ url('/redirect') }}" class="btn btn-success btn-sm btn-block"> <i class="fa fa-google"></i> &nbsp; Continue with
                Google</a>
        </p>
        <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-5 col-5">
          <hr>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-2 text-center">
         OR
        </div>
        <div class="col-lg-5 col-md-5 col-sm-5 col-5">
            <hr>
        </div>
        </div>
                        <div class="form-group required">
                            <label class="control-label" for="name">Full Name</label>
                                <input type="text" name="name" value="{{old('name')}}" placeholder="Full Name" id="name" class="form-control @error('name') is-invalid @enderror" required autocomplete="name">
                                @error('name')
                                <span class="invalid-feedback" role="alert" style="color:red;">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group required">
                            <label class="control-label" for="input-email">E-Mail</label>
                            <input type="email" name="email" value="{{old('email')}}"  placeholder="E-Mail" id="email" class="form-control @error('email') is-invalid @enderror" required autocomplete="email">
                                @error('email')
                                <span class="invalid-feedback" role="alert" style="color:red;">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
    
                        <div class="row">
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label" for="password">Password</label>
            <input type="password" name="password" value="{{old('password')}}"  placeholder="Password" id="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
                @error('password')
                <span class="invalid-feedback" role="alert" style="color:red;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label" for="input-confirm">{{ __('Confirm Password') }}</label>
            <div class="">
                <input type="password" name="password_confirmation" value="{{old('confirm')}}"  placeholder="Password Confirm" id="password-confirm" class="form-control" required autocomplete="new-password">
            </div>
        </div>
    </div>
                        </div>
                        <div class="form-group">
                                <label>Subscribe</label> &nbsp;
                                <label class="radio-inline">
                                    <input type="radio" name="subscribe" value="1" checked="checked"> Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="subscribe" value="0"> No
                                </label>
                        </div>
                        <div class="form-group">
                        <div class="pull-right">By signing up, you agree to our <a role="button" data-toggle="modal" data-target="#terms" class="agree"><b>terms of use</b></a>
                        </div>
                        <hr>
                        <div class="form-group">
                            <input type="submit" name="signup" value="Sign Up" class="btn btn-sm btn-primary btn-block">
                        </div>
                    </div>
                </form>
            </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-12">

        </div>
    </div>
</div>
<!-- //Main Container -->

<!-- The Modal -->
<div class="modal fade" id="terms">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Terms and Conditions</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        {!! $terms->terms !!}
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

@endsection
