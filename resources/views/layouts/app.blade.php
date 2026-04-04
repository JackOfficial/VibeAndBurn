<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Premium SMM services provider" />
        <link rel="shortcut icon" href="{{ asset('front/images/v&b project.png') }}"/>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('front/css/boxicons.min.css') }}" />
        <link href="{{ asset('front/css/bootstrap.min.css') }}" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        
        <style>
            :root {
                --vibe-red: #FF0000;
                --vibe-black: #050505;
                --vibe-dark-grey: #111111;
                --vibe-text-muted: #888888;
            }

            body { 
                font-family: 'Inter', sans-serif; 
                background-color: var(--vibe-black); 
                color: #ffffff; 
                overflow-x: hidden;
            }
            
            /* --- Navbar Styling --- */
            .navbar {
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                padding: 20px 0;
                background: transparent;
            }
            .navbar.sticky {
                background: rgba(0, 0, 0, 0.8) !important;
                backdrop-filter: blur(20px);
                border-bottom: 1px solid #222;
                padding: 12px 0;
            }

            .nav-link { 
                font-weight: 600; 
                font-size: 0.9rem; 
                color: #bbb !important; 
                margin: 0 12px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                transition: 0.3s;
            }
            .nav-link:hover, .nav-link.active { color: var(--vibe-red) !important; }

            .btn-nav-signup {
                background: var(--vibe-red);
                border: none;
                border-radius: 8px;
                padding: 10px 24px;
                color: white !important;
                font-weight: 700;
                box-shadow: 0 4px 15px rgba(255, 0, 0, 0.2);
                transition: 0.3s;
            }
            .btn-nav-signup:hover {
                background: #cc0000;
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(255, 0, 0, 0.4);
            }

            /* --- Floating Support (Brand Matched) --- */
            .floating-support {
                position: fixed;
                bottom: 30px;
                right: 30px;
                background: var(--vibe-red);
                color: white;
                width: 55px;
                height: 55px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 10px 25px rgba(255, 0, 0, 0.3);
                z-index: 1000;
                transition: 0.3s;
            }
            .floating-support:hover { transform: scale(1.1) rotate(5deg); color: white; }

            /* --- Footer Styling --- */
            .footer { 
                background: #000 !important; 
                border-top: 1px solid #1a1a1a; 
                padding: 80px 0 0; 
            }
            .footer-link-v2 {
                color: var(--vibe-text-muted);
                text-decoration: none !important;
                transition: 0.3s ease;
                font-size: 0.95rem;
                display: inline-block;
                margin-bottom: 12px;
            }
            .footer-link-v2:hover { color: var(--vibe-red); transform: translateX(5px); }
            
            .cta-footer-card {
                background: var(--vibe-dark-grey);
                border: 1px solid #222;
                border-radius: 20px;
                padding: 40px;
            }

            .social-btn {
                width: 40px; height: 40px;
                display: inline-flex;
                align-items: center; justify-content: center;
                background: #111;
                border: 1px solid #222;
                border-radius: 8px;
                color: #fff;
                margin-right: 10px;
                transition: 0.3s;
            }
            
            .social-btn:hover { background: var(--vibe-red); border-color: var(--vibe-red); color: white; }

            /* --- Custom Elements --- */
            .text-vibe-red { color: var(--vibe-red); }
            .bg-vibe-red { background-color: var(--vibe-red); }
            .dropdown-menu {
                background: #111;
                border: 1px solid #222;
            }
            .dropdown-item { color: #ccc; }
            .dropdown-item:hover { background: #1a1a1a; color: var(--vibe-red); }
            
       
/* --- Ultra-Responsive Subscribe Field --- */
.subscribe-form-wrapper {
    width: 100%;
}

.subscription-pill {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid #222;
    border-radius: 60px;
    padding: 6px 6px 6px 20px;
    transition: all 0.3s ease;
}

.subscription-pill:focus-within {
    border-color: var(--vibe-red);
    box-shadow: 0 0 15px rgba(255, 0, 0, 0.1);
}

.subscribe-input {
    background: transparent !important;
    border: none !important;
    color: white !important;
    font-size: 0.9rem;
    outline: none !important;
    width: 100%;
}

.subscribe-action-btn {
    background: var(--vibe-red);
    color: white !important;
    border: none;
    border-radius: 50px;
    padding: 10px 20px;
    font-weight: 700;
    font-size: 0.75rem;
    text-transform: uppercase;
    white-space: nowrap;
    transition: 0.3s;
}

/* Mobile Breakpoint Fix */
@media (max-width: 480px) {
    .subscription-pill {
        flex-direction: column; /* Stack vertically on very small screens */
        border-radius: 15px;
        padding: 10px;
        background: transparent;
        border: none;
    }

    .subscribe-input {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid #222 !important;
        border-radius: 10px !important;
        padding: 12px 15px !important;
        margin-bottom: 10px;
        text-align: center;
    }

    .subscribe-action-btn {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
    }
    
    .error-message {
        text-align: center;
        padding-left: 0;
    }
}

    .error-message {
        color: #FF0000;
        font-size: 0.75rem;
        margin-top: 8px;
        padding-left: 20px;
        font-weight: 600;
    }

    /* Handle Bootstrap's invalid class for dark mode */
    .subscribe-input.is-invalid {
        color: #FF4444 !important;
    }
    
    /* Premium Google Button Styling */
    .google-btn-structured {
        border-radius: 50px !important;
        border: 2px solid transparent !important;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
    }

    .google-btn-structured:hover {
        transform: translateY(-4px);
        background-color: #ffffff !important;
        border-color: var(--vibe-red) !important;
        box-shadow: 0 15px 30px rgba(255, 0, 0, 0.2) !important;
    }

    /* Dashboard Button Refinement */
    .btn-vibe {
        background: var(--vibe-red);
        color: white !important;
        border-radius: 50px !important;
        border: none;
        letter-spacing: 1.5px;
        transition: 0.3s;
    }

    .btn-vibe:hover {
        background: #cc0000;
        transform: scale(1.02);
        box-shadow: 0 10px 25px rgba(255, 0, 0, 0.4);
    }

    /* Subtle helper utility */
    .opacity-75 { opacity: 0.75; }
        </style>

        @yield('styles')
        @livewireStyles
    </head>

    <body>
        <a href="https://wa.me/yournumber" class="floating-support shadow-lg">
            <i class='bx bxl-whatsapp fs-2'></i>
        </a>

        <nav class="navbar navbar-expand-lg fixed-top"> 
            <div class="container">
                <a class="navbar-brand" href="/">
                    <img src="{{ asset('front/images/v&b project.png') }}" alt="VibeandBurn Logo" height="32" />
                </a>
                
                <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <i class="bx bx-menu-alt-right text-white" style="font-size: 2rem;"></i>
                </button>

         <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link active" href="/">Home</a></li>
        <li class="nav-item"><a class="nav-link active" href="#">About</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('our-services.index') }}">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('faq.index') }}">Help</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('contactus.create') }}">Contact</a></li>
    </ul>

    <div class="nav-btns d-flex align-items-center">
        @guest
            <a href="{{route('login')}}" class="nav-link mr-3">Sign In</a>
            <a href="{{ route('register') }}" class="btn btn-nav-signup">Get Famous</a>
        @endguest

        @auth
            <div class="dropdown">
                <a href="#" class="btn btn-outline-light rounded-pill dropdown-toggle px-4 border-secondary" data-toggle="dropdown">
                    <i class="bx bx-user-circle mr-1"></i> {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow-lg mt-3">
                    
                    {{-- 1. ADMIN OPTIONS (Shown to Admin or User Admin) --}}
                    @hasanyrole('Admin|Super Admin')
                        <a class="dropdown-item py-2 font-weight-bold text-primary" href="/admin/dashboard">
                            <i class="bx bx-shield-quarter mr-2"></i> Admin Panel
                        </a>
                    @endhasanyrole

                    {{-- 2. USER DASHBOARD (Shown to everyone logged in) --}}
                    <a class="dropdown-item py-2" href="/home">
                        <i class="bx bx-grid-alt mr-2"></i> User Dashboard
                    </a>

                    <div class="dropdown-divider border-secondary"></div>
                    
                    <a class="dropdown-item text-vibe-red py-2" href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bx bx-log-out mr-2"></i> Logout
                    </a>
                </div>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        @endauth
    </div>
</div>
                </div>
        </nav>

        <main>
            @yield('content')
        </main>

        <footer class="footer mt-5" style="background: #050505; border-top: 1px solid #111; padding-top: 80px;">
    <div class="container mb-5">
        <div class="cta-footer-card p-5 shadow-lg" style="background: linear-gradient(90deg, #0d0d0d 0%, #1a0000 100%); border: 1px solid #222; border-radius: 30px;">
            <div class="row align-items-center">
                <div class="col-lg-7 text-center text-lg-left">
                    <h2 class="font-weight-bold text-white mb-2">Ready to ignite your <span class="text-vibe">Vibe?</span></h2>
                    <p class="text-muted mb-0">Join 50,000+ users boosting their social presence daily.</p>
                </div>
                <div class="col-lg-5 text-center text-lg-right mt-4 mt-lg-0">
                    <a href="{{ route('register') }}" class="btn btn-vibe btn-lg px-5 shadow-sm">
                        <i class="fas fa-rocket mr-2"></i> Ignite Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-5">
                <img src="{{ asset('front/images/logo.png') }}" height="35" class="mb-4" alt="Vibe and Burn">
                <p class="text-muted mb-4" style="line-height: 1.8; font-size: 0.9rem;">
                    VibeAndBurn is the premium standard for SMM. High-speed delivery, verified providers, and 24/7 technical support for resellers and influencers.
                </p>
                <div class="social-links d-flex">
                    <a href="#" class="social-btn-vibe mr-2"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-btn-vibe mr-2"><i class="fab fa-telegram-plane"></i></a>
                    <a href="#" class="social-btn-vibe"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            
            <div class="col-lg-2 col-6 mb-4">
                <h6 class="text-white font-weight-bold text-uppercase small mb-4" style="letter-spacing: 1px;">Platform</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('our-services.index') }}" class="text-muted small hover-red">Price List</a></li>
                    <li class="mb-2"><a href="{{ route('faq.index') }}" class="text-muted small hover-red">Help Center</a></li>
                    <li class="mb-2"><a href="/api-docs" class="text-muted small hover-red">Developers API</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-6 mb-4">
                <h6 class="text-white font-weight-bold text-uppercase small mb-4" style="letter-spacing: 1px;">Legal</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/terms-and-conditions" class="text-muted small hover-red">Terms of Service</a></li>
                    <li class="mb-2"><a href="#" class="text-muted small hover-red">Privacy Policy</a></li>
                    <li class="mb-2"><a href="{{ route('contactus.create') }}" class="text-muted small hover-red">Contact Support</a></li>
                </ul>
            </div>

            <div class="col-lg-4">
                <div class="p-4 rounded-lg" style="background: #0a0a0a; border: 1px solid #151515;">
                    <h6 class="text-white font-weight-bold mb-2">The Weekly Alpha</h6>
                    <p class="text-muted small mb-3">Get secret SMM strategies and price drops directly to your inbox.</p>
                    
                    <livewire:subscribe-component />

                    <div class="d-flex align-items-center mt-3 pt-3" style="border-top: 1px solid #1a1a1a;">
                        <span class="badge badge-success mr-2" style="height: 8px; width: 8px; border-radius: 50%; padding: 0; display: inline-block;"></span>
                        <small class="text-white-50" style="font-size: 0.7rem;">API Servers: 100% Operational</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-4 mt-5" style="background: #000; border-top: 1px solid #111;">
        <div class="container text-center">
            <p class="text-muted mb-0 small">&copy; {{date('Y')}} <strong>VibeAndBurn</strong>. Crafted for the Elite.</p>
        </div>
    </div>
</footer>

        <script src="{{ asset('front/js/jquery-3.5.1.min.js') }}"></script>
        <script src="{{ asset('front/js/bootstrap.bundle.min.js') }}"></script>
        <script>
            $(window).scroll(function() {
                if ($(this).scrollTop() > 50) {
                    $('.navbar').addClass('sticky');
                } else {
                    $('.navbar').removeClass('sticky');
                }
            });
        </script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @livewireScripts
    </body>
</html>