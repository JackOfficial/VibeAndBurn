@extends('layouts.app')
@section('title', 'Login | Vibe and Burn')

@section('styles')
<style>
    body { background-color: #050505; color: #fff; overflow-x: hidden; }
    
    /* DRAMATIC SPACING: Matching your Register Page */
    .auth-page-wrapper {
        min-height: 100vh; 
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 150px 0 100px 0; 
    }

    .auth-card {
        background: #0d0d0d;
        border: 1px solid #1a1a1a;
        border-radius: 30px;
        padding: 50px;
        box-shadow: 0 30px 60px rgba(0,0,0,0.6);
        width: 100%;
        max-width: 500px; /* Slightly slimmer than register for focus */
    }

    /* Alpine Password Toggle UI */
    .password-wrapper { position: relative; }
    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #444;
        cursor: pointer;
        padding: 0;
        transition: 0.3s;
        z-index: 10;
    }
    .password-toggle:hover { color: #FF0000; }

    .form-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 800;
        color: #555;
        margin-bottom: 10px;
    }

    .auth-input {
        background: #151515 !important;
        border: 1px solid #222 !important;
        color: #fff !important;
        border-radius: 14px !important;
        padding: 14px 18px;
        height: auto;
        transition: all 0.3s ease;
    }

    .auth-input:focus {
        border-color: #FF0000 !important;
        box-shadow: 0 0 20px rgba(255, 0, 0, 0.15) !important;
    }

    .btn-google-auth {
        background: #fff; color: #000; font-weight: 700; border-radius: 14px;
        padding: 14px; display: flex; align-items: center; justify-content: center;
        text-decoration: none !important; transition: 0.3s;
    }
    
    .divider { display: flex; align-items: center; text-align: center; color: #333; margin: 30px 0; font-size: 0.7rem; font-weight: 900; letter-spacing: 2px; }
    .divider::before, .divider::after { content: ''; flex: 1; border-bottom: 1px solid #1a1a1a; }
    .divider:not(:empty)::before { margin-right: 1em; }
    .divider:not(:empty)::after { margin-left: .5em; }

    .btn-login { 
        background: #FF0000; border: none; border-radius: 14px; padding: 16px; 
        font-weight: 800; text-transform: uppercase; letter-spacing: 2px; transition: 0.4s; 
    }
    .btn-login:hover { background: #e60000; transform: translateY(-3px); box-shadow: 0 15px 30px rgba(255,0,0,0.3); }

    /* Custom Checkbox Styling */
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #FF0000;
        border-color: #FF0000;
    }
</style>
@endsection

@section('content')
<div class="auth-page-wrapper">
    <div class="auth-card" x-data="{ showPass: false }">
        <div class="text-center mb-5">
            <h1 class="font-weight-bold text-white mb-2" style="letter-spacing: -1px;">Welcome Back</h1>
            <p class="text-muted">New here? <a href="{{route('register')}}" style="color: #FF0000; font-weight: 700;">Create Account</a></p>
        </div>

        <a href="{{ url('/redirect') }}" class="btn-google-auth mb-2">
            <img src="https://www.google.com/favicon.ico" width="18" class="mr-2"> Continue with Google
        </a>

        <div class="divider">SECURE LOGIN</div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group mb-4">
                <label class="form-label">E-Mail Address</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control auth-input @error('email') is-invalid @enderror" placeholder="name@email.com" required autofocus>
                @error('email') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
            </div>

            <div class="form-group mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label mb-0">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-muted" style="font-weight: 600;">Forgot?</a>
                    @endif
                </div>
                <div class="password-wrapper">
                    <input :type="showPass ? 'text' : 'password'" 
                           name="password" 
                           class="form-control auth-input @error('password') is-invalid @enderror" 
                           placeholder="••••••••" required>
                    <button type="button" class="password-toggle" @click="showPass = !showPass">
                        <i class="fas" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                @error('password') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
            </div>

            <div class="form-group mb-5">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="remember" class="custom-control-input" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="custom-control-label small text-muted" for="remember">Keep me logged in</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-login">
                Log In <i class="fas fa-sign-in-alt ml-2 small"></i>
            </button>
        </form>
    </div>
</div>
@endsection