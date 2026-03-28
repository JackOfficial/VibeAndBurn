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

    @include('partials.crisp_chat')
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
</body>
</html>