@extends('layouts.app')
@section('title', 'Vibe and Burn | SMM Panel - We Make You Famous')

@section('styles')
<style>
    :root { 
        --vibe-red: #FF0000; 
        --vibe-dark: #050505;
        --vibe-card: #0d0d0d; 
    }

    body { background-color: var(--vibe-dark); overflow-x: hidden; }

    /* Hero & Layout Spacing */
    .hero-gradient { 
        background: radial-gradient(circle at top right, #1a0000 0%, #050505 100%); 
        padding: 160px 0 120px; 
    }

    .text-vibe { 
        color: var(--vibe-red);
        text-shadow: 0 0 20px rgba(255, 0, 0, 0.4);
    }

    /* Trust Bar */
    .trust-bar { 
        background: #0d0d0d; 
        border: 1px solid #1a1a1a;
        border-radius: 30px; 
        margin-top: -60px; 
        position: relative; 
        z-index: 10; 
        padding: 40px 20px; 
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
    }
    .trust-item { border-right: 1px solid #222; }

    /* SMM Cards & Testimonials */
    .smm-card { 
        border: 1px solid #1a1a1a; 
        border-radius: 24px; 
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); 
        background: var(--vibe-card); 
    }
    .smm-card:hover { 
        transform: translateY(-12px); 
        border-color: var(--vibe-red);
        box-shadow: 0 20px 40px rgba(255, 0, 0, 0.1); 
    }

    .testimonial-quote { font-style: italic; color: #aaa; line-height: 1.6; font-size: 0.95rem; }
    
    .avatar-circle {
        width: 50px; height: 50px; border-radius: 50%; 
        background: linear-gradient(45deg, #FF0000, #800000);
        font-weight: 900; color: white;
    }

    /* Buttons */
    .btn-vibe { 
        background: var(--vibe-red); color: white !important; border: none;
        border-radius: 50px; padding: 16px 35px; font-weight: 800;
        text-transform: uppercase; letter-spacing: 1px; transition: 0.3s;
        box-shadow: 0 10px 20px rgba(255, 0, 0, 0.3);
    }
    .btn-vibe:hover { transform: scale(1.05); background: #e60000; }

    /* Carousel Controls */
    .control-btn {
        background: #111; border: 1px solid #222; width: 45px; height: 45px;
        border-radius: 50%; color: white; transition: 0.3s;
    }
    .control-btn:hover { border-color: var(--vibe-red); color: var(--vibe-red); }
    
      .subscribe-form-wrapper {
        max-width: 500px;
        width: 100%;
    }

    /* The outer container that creates the "nested" look */
    .subscription-pill {
        background: rgba(255, 255, 255, 0.03); /* Subtle dark glass effect */
        border: 1px solid #222;
        border-radius: 60px;
        padding: 6px 6px 6px 20px; /* More padding on left for text, less on right for button */
        transition: all 0.3s ease;
    }

    .subscription-pill:focus-within {
        border-color: var(--vibe-red);
        background: rgba(255, 0, 0, 0.02);
        box-shadow: 0 0 20px rgba(255, 0, 0, 0.1);
    }

    /* Invisible input to let the pill background show through */
    .subscribe-input {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        color: white !important;
        font-size: 0.95rem;
        outline: none !important;
        height: 45px;
    }

    .subscribe-input::placeholder {
        color: #555;
    }

    /* The actual nested button */
    .subscribe-action-btn {
        background: var(--vibe-red);
        color: white !important;
        border: none;
        border-radius: 50px;
        padding: 0 25px;
        height: 45px; /* Matches input height for perfect alignment */
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
    }

    .subscribe-action-btn:hover {
        background: #e60000;
        transform: scale(1.02);
        box-shadow: 0 5px 15px rgba(255, 0, 0, 0.3);
    }

    /* Responsive adjustments for very small screens */
    @media (max-width: 575.98px) {
        .subscription-pill {
            padding: 4px 15px; /* Centered padding for mobile */
        }
        .subscribe-input {
            text-align: center;
        }
    }

    @media (max-width: 991.98px) {
        .hero-gradient { padding: 120px 0 80px; text-align: center; }
        .trust-item { border-right: none; border-bottom: 1px solid #222; padding: 20px 0; }
        .trust-item:last-child { border-bottom: none; }
    }
</style>
@endsection

@section('content')
<section class="hero-gradient position-relative overflow-hidden">
    <div class="container position-relative z-index-1">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="mb-4">
                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(255,0,0,0.1); color: #FF0000; border: 1px solid rgba(255,0,0,0.3); font-size: 0.7rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 800;">
                        <i class="fas fa-fire-alt mr-2"></i> Elite SMM Solutions 2026
                    </span>
                </div>
                
                <h1 class="display-4 mb-3 font-weight-bold text-white">
                    Social visibility <br><span class="text-vibe">without the nightmare.</span>
                </h1>

                <p class="lead mb-5 text-muted">
                    The world's most reliable SMM Panel. Get high-quality engagement for 
                    <span class="text-white font-weight-bold">Instagram, TikTok, and YouTube</span> in seconds.
                </p>

               <div class="d-flex flex-column align-items-center align-items-lg-start mb-4">
  <div class="w-100" style="max-width: 400px;">
    @guest
        {{-- Google Login Button --}}
        <a href="{{ url('/redirect') }}" class="btn btn-light btn-block shadow-lg d-flex align-items-center p-2 google-btn-structured">
            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                <img src="https://www.google.com/favicon.ico" alt="Google" width="18">
            </div>
            <div class="flex-grow-1 text-center">
                <span class="d-block font-weight-bold text-dark mb-0" style="letter-spacing: 0.5px; font-size: 0.95rem;">Continue with Google</span>
            </div>
        </a>

        <div class="d-flex align-items-center mt-3 ml-lg-2 opacity-75">
            <span class="badge badge-pill badge-dark border border-secondary py-1 px-2 mr-2">
                <i class="fas fa-shield-alt text-success"></i>
            </span>
            <p class="small text-muted mb-0 font-weight-normal">
                Secure 1-click access. <span class="text-white-50">No password needed.</span>
            </p>
        </div>
    @else
        {{-- Logged In Options --}}
        @hasanyrole('Admin|User Admin')
            {{-- Primary Button for Admins --}}
            <a href="/admin/dashboard" class="btn btn-primary btn-block shadow-lg py-3 mb-2">
                <i class="fas fa-user-shield mr-2"></i> 
                <span class="font-weight-bold text-uppercase">Admin Control Panel</span>
            </a>
            
            {{-- Secondary link for Admins to access User Side --}}
            <a href="/home" class="btn btn-outline-light btn-block border-secondary py-2 small opacity-75">
                <i class="fas fa-user mr-2"></i> Switch to User Dashboard
            </a>
        @else
            {{-- Standard Button for Normal Users --}}
            <a href="/home" class="btn btn-vibe btn-block shadow-lg py-3">
                <i class="fas fa-rocket mr-2"></i> 
                <span class="font-weight-bold text-uppercase">Enter Dashboard</span>
            </a>
        @endhasanyrole
    @endguest
</div>
</div>
            </div>

            <div class="col-lg-6 d-none d-lg-block">
                <img class="img-fluid" src="{{ asset('front/images/hero-2-img.png') }}" style="border-radius: 40px; border: 1px solid rgba(255,255,255,0.05);">
            </div>
        </div>
    </div>
</section>

<div class="container">
    <div class="trust-bar row no-gutters text-center">
        <div class="col-lg-4 trust-item"><h2 class="mb-0 font-weight-bold text-white">12,000+</h2><small class="text-uppercase text-vibe font-weight-bold">Active Services</small></div>
        <div class="col-lg-4 trust-item"><h2 class="mb-0 font-weight-bold text-white">40M+</h2><small class="text-uppercase text-vibe font-weight-bold">Orders Delivered</small></div>
        <div class="col-lg-4 trust-item"><h2 class="mb-0 font-weight-bold text-white">0.01s</h2><small class="text-uppercase text-vibe font-weight-bold">Start Time</small></div>
    </div>
</div>

<section class="py-5 mt-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-vibe text-uppercase font-weight-bold">The Marketplace</h6>
            <h2 class="display-5 font-weight-bold text-white">Everything you need to go viral</h2>
        </div>
        <div class="row">
            @php $features = [['title'=>'Instant Delivery','icon'=>'fas fa-bolt'],['title'=>'Cheapest API','icon'=>'fas fa-code'],['title'=>'Elite Support','icon'=>'fas fa-headset']]; @endphp
            @foreach($features as $f)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card smm-card h-100 p-4">
                    <div class="card-body">
                        <div class="d-inline-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px; background: rgba(255,0,0,0.1); border-radius: 20px;">
                            <i class="{{ $f['icon'] }} fa-2x text-vibe"></i>
                        </div>
                        <h4 class="font-weight-bold text-white mb-3">{{ $f['title'] }}</h4>
                        <p class="text-muted mb-0">High-performance services starting at just $0.01 per 1,000 units.</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-5" style="background-color: #080808; border-top: 1px solid #111; border-bottom: 1px solid #111;">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-vibe text-uppercase font-weight-bold">Wall of Fame</h6>
            <h2 class="display-5 font-weight-bold text-white">Trusted by <span class="text-vibe">Thousands</span></h2>
        </div>

        <div id="testiCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card smm-card p-4 h-100">
                                <div class="card-body">
                                    <div class="mb-3 text-vibe small"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                                    <p class="testimonial-quote mb-4">"Vibe and Burn is the only panel I trust for my clients. The speed is unmatched and the drop rate is virtually zero."</p>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle d-flex align-items-center justify-content-center">A</div>
                                        <div class="ml-3"><h6 class="mb-0 text-white font-weight-bold">Alex Rivera</h6><small class="text-muted">Agency CEO</small></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card smm-card p-4 h-100">
                                <div class="card-body">
                                    <div class="mb-3 text-vibe small"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                                    <p class="testimonial-quote mb-4">"I went from 100 followers to 50k in a month using their TikTok engagement services. Best investment ever!"</p>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle d-flex align-items-center justify-content-center">S</div>
                                        <div class="ml-3"><h6 class="mb-0 text-white font-weight-bold">Sarah Jenkins</h6><small class="text-muted">Influencer</small></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <button class="control-btn mr-2" data-target="#testiCarousel" data-slide="prev"><i class="fas fa-chevron-left"></i></button>
                <button class="control-btn" data-target="#testiCarousel" data-slide="next"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-dark-section">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0">
                <h6 class="text-vibe text-uppercase font-weight-bold">Get Started</h6>
                <h2 class="display-5 font-weight-bold mb-5 text-white">Launch in <span class="text-vibe">3 Steps</span></h2>
                @foreach(['Register','Deposit','Grow'] as $index => $step)
                <div class="d-flex mb-4">
                    <div class="d-flex align-items-center justify-content-center mr-4" style="min-width: 45px; height: 45px; border: 2px solid var(--vibe-red); border-radius: 50%; color: var(--vibe-red); font-weight: 900;">{{$index+1}}</div>
                    <div><h5 class="text-white font-weight-bold mb-1">{{$step}}</h5><p class="text-muted small">Instant setup for high-speed delivery.</p></div>
                </div>
                @endforeach
                <a href="{{ route('register') }}" class="btn btn-vibe mt-4"><i class="fas fa-rocket mr-2"></i> Get Started Now</a>
            </div>
            <div class="col-lg-7">
                <div class="embed-responsive embed-responsive-16by9 shadow-lg" style="border-radius: 30px; border: 5px solid #1a1a1a; overflow: hidden;">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/OcTjCVNMcCo?rel=0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection