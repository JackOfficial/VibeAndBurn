@extends('layouts.app')
@section('title', 'Vibe and Burn | Contact Us')

@section('styles')
<style>
    /* Dark Theme Setup */
    body { background-color: #050505; color: #fff; }

    .contact-section {
        background: radial-gradient(circle at bottom left, #1a0000, #000000);
        padding: 100px 0;
        min-height: 100vh;
    }

    /* Glass Card Style */
    .contact-card {
        background: #0d0d0d;
        border: 1px solid #1a1a1a;
        border-radius: 20px !important;
        padding: 40px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }

    .text-vibe-red { color: #FF0000; text-shadow: 0 0 10px rgba(255, 0, 0, 0.3); }

    /* Modern Form Styling */
    .form-group label {
        color: #888;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .form-control {
        background: #111 !important;
        border: 1px solid #222 !important;
        color: #fff !important;
        border-radius: 10px;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #FF0000 !important;
        box-shadow: 0 0 15px rgba(255, 0, 0, 0.1);
        background: #151515 !important;
    }

    /* Custom Submit Button */
    .btn-submit {
        background: #FF0000;
        color: #fff;
        border: none;
        padding: 15px 40px;
        border-radius: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
        width: 100%;
    }

    .btn-submit:hover {
        background: #cc0000;
        box-shadow: 0 8px 25px rgba(255, 0, 0, 0.4);
        transform: translateY(-2px);
        color: #fff;
    }

    /* Info Icons */
    .info-box {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid rgba(255, 0, 0, 0.1);
    }
    
    .info-box i {
        font-size: 1.5rem;
        color: #FF0000;
        margin-bottom: 10px;
    }

    /* Alert Styling */
    .alert-custom {
        border-radius: 10px;
        background: #1a1a1a;
        border: 1px solid #333;
        color: #fff;
    }
    .alert-success-vibe { border-left: 4px solid #00FF00; }
    .alert-danger-vibe { border-left: 4px solid #FF0000; }
</style>
@endsection

@section('content')
<section class="contact-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 my-5 mb-lg-0">
                <h6 class="text-vibe-red text-uppercase font-weight-bold letter-spacing-3 mb-3">Get in Touch</h6>
                <h1 class="text-white font-weight-bold display-4 mb-4">Let's Ignite Your Growth</h1>
                <p class="text-muted mb-5" style="font-size: 1.1rem;">Have a question about our services or need a custom API solution? Send us a message and our team will get back to you within 12 hours.</p>
                
                <div class="info-box">
                    <i class="fas fa-headset"></i>
                    <h5 class="text-white">24/7 Priority Support</h5>
                    <p class="text-muted small mb-0">Our support engineers are always online for resellers.</p>
                </div>

                <div class="info-box">
                    <i class="fab fa-telegram-plane"></i>
                    <h5 class="text-white">Join our Channel</h5>
                    <p class="text-muted small mb-0">Get instant updates on service status and new drops.</p>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="contact-card">
                    @if (Session::has('sendMessageSuccess'))
                        <div class="alert alert-custom alert-success-vibe alert-dismissible fade show mb-4">
                            <strong>Success!</strong> {{ Session::get('sendMessageSuccess') }}
                            <button type="button" class="close text-white" data-dismiss="alert">&times;</button>
                        </div>
                    @elseif(Session::has('sendMessageFail'))
                        <div class="alert alert-custom alert-danger-vibe alert-dismissible fade show mb-4">
                            <strong>Error:</strong> {{ Session::get('sendMessageFail') }}
                            <button type="button" class="close text-white" data-dismiss="alert">&times;</button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contactus.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="name">Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" 
                                           @auth value="{{ Auth::user()->name }}" @else value="{{ old('name') }}" @endauth 
                                           placeholder="Enter your name" id="name" required />
                                    @error('name') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="email">Email Address</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           @auth value="{{ Auth::user()->email }}" @else value="{{ old('email') }}" @endauth 
                                           placeholder="mail@example.com" id="email" required />
                                    @error('email') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="subject">Subject</label>
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" 
                                   placeholder="How can we help?" value="{{ old('subject') }}" id="subject" required />
                            @error('subject') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="message">Message</label>
                            <textarea class="form-control" name="message" required id="message" 
                                      placeholder="Describe your issue or question in detail..." rows="5">{{ old('message') }}</textarea>
                            @error('message') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                        </div>

                        <button type="submit" class="btn btn-submit shadow-lg">
                            <i class="fas fa-paper-plane mr-2"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection