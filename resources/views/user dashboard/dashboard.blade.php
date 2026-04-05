<!DOCTYPE html>
<html lang="en" class="js">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Tonny Jack">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Premium SMM services provider">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('front/images/v&b project.png') }}">
    <title>@yield('title', 'Vibe & Burn | SMM Panel')</title>

    <link rel="stylesheet" href="{{ asset('user dashboard/assets/css/dashlite.css?ver=2.2.0') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('user dashboard/assets/css/theme.css?ver=2.2.0') }}">
    
    <link rel="stylesheet" href="{{ asset('back/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('back/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('back/plugins/toastr/toastr.min.css') }}">

   <style>
    .ticket-float {
        position: fixed;
        width: 60px;
        height: 60px;
        bottom: 40px;
        right: 40px;
        background-color: #007bff; /* Professional Support Blue */
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 24px; /* Slightly smaller for the ticket icon to fit well */
        box-shadow: 2px 2px 10px rgba(0,0,0,0.2);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .ticket-float:hover {
        background-color: #0056b3;
        color: #FFF;
        transform: translateY(-5px); /* Gentle lift effect */
        box-shadow: 2px 5px 15px rgba(0,0,0,0.3);
    }

    /* Mobile adjustments */
    @media screen and (max-width: 767px) {
        .ticket-float {
            width: 50px;
            height: 50px;
            bottom: 20px;
            right: 20px;
            font-size: 20px;
        }
    }

   /* 1. The Profile Card Glassmorphism Effect */
    .user-card-s2 {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        backdrop-filter: blur(4px); /* Modern glass effect */
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .user-card-s2:hover {
        background: rgba(255, 255, 255, 0.08) !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    /* 2. Avatar & Status Dot Styling */
    .user-avatar.bg-primary-dim {
        background-color: rgba(127, 103, 255, 0.15) !important;
        box-shadow: 0 0 10px rgba(127, 103, 255, 0.1);
    }

    .dot-xs {
        width: 8px;
        height: 8px;
    }

    /* 3. Balance Typography & Glow */
    .user-balance {
        color: #ffffff;
        letter-spacing: -0.02em;
        text-shadow: 0 0 12px rgba(255, 255, 255, 0.1);
    }

    .user-balance small {
        font-weight: 500;
        margin-right: 1px;
    }

    /* 4. Overline Titles (Labels) */
    .overline-title-alt {
        font-family: 'Roboto', sans-serif;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1.2px;
        color: #8094ae !important; /* Soft gray-blue */
    }

    /* 5. Mobile Sidebar Specific Fixes */
    @media (max-width: 1199px) {
        .nk-sidebar-widget {
            display: block !important; /* Forces visibility on mobile */
            margin-top: 20px !important;
            margin-bottom: 15px !important;
        }

        .user-card-s2 {
            padding: 1rem !important; /* Slightly tighter on mobile */
        }

        .user-balance {
            font-size: 1.1rem !important;
        }
    }

    /* 6. Button Polish */
    .btn-round.btn-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-primary.shadow-sm {
        box-shadow: 0 4px 12px rgba(127, 103, 255, 0.3) !important;
    }

    /* 7. General Sidebar Menu Spacing */
    .nk-menu-link {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
        transition: all 0.2s ease;
    }

    .nk-menu-link:hover {
        background: rgba(127, 103, 255, 0.05);
    }
    
</style>

    {{-- @include('partials.crisp_chat') --}}
    @livewireStyles
    @stack('styles')
</head>

<body class="nk-body bg-lighter npc-general has-sidebar">
    <div class="nk-app-root">
        <div class="nk-main">
            @include('partials.dashboard_sidebar')

            <div class="nk-wrap">
                @include('partials.dashboard_header')

                <div class="nk-content">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            @yield('content')
                        </div>
                    </div>
                </div>

                @include('partials.dashboard_footer')
            </div>
        </div>
    </div>

    <script src="{{ asset('user dashboard/assets/js/bundle.js') }}"></script>
    <script src="{{ asset('user dashboard/assets/js/scripts.js') }}"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
       <script>
        // Auto-close modals on Livewire events
        window.addEventListener('hide-modal', () => {
            $('.modal').modal('hide');
        });
    </script>
    @livewireScripts
    @stack('scripts')

   <a href="{{ url('/tickets') }}"
   class="ticket-float" 
   target="_blank" 
   title="Open Support Ticket">
    <i class="fas fa-ticket-alt"></i>
</a>

</body>
</html>

