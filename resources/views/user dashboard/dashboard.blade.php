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

