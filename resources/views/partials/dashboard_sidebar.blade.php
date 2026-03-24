<div class="nk-sidebar nk-sidebar-fixed is-dark" data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">
            <a href="{{ url('/') }}" class="logo-link nk-sidebar-logo">
                <img class="logo-light logo-img" src="{{ asset('front/images/logo white.png') }}" alt="logo">
                <img class="logo-dark logo-img" src="{{ asset('front/images/logo white.png') }}" alt="logo-dark">
            </a>
        </div>
        <div class="nk-menu-trigger mr-n2">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
        </div>
    </div>
    
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <div class="nk-sidebar-widget d-none d-xl-block">
                    <div class="user-account-info">
                        <h6 class="overline-title-alt">Current Balance</h6>
                        <div class="user-balance text-white">${{ number_format($wallet ?? 0, 2) }}</div>
                    </div>
                    <a href="{{ route('addFund.create') }}" class="btn btn-primary btn-block mt-2">
                        <em class="icon ni ni-wallet-in"></em> <span>Add Funds</span>
                    </a>
                </div>

                <ul class="nk-menu">
                    <li class="nk-menu-heading"><h6 class="overline-title text-primary-alt">Main Menu</h6></li>
                    
                    <li class="nk-menu-item">
                        <a href="{{ route('newOrder.create') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-plus-circle-fill"></em></span>
                            <span class="nk-menu-text">New Order</span>
                        </a>
                    </li>

                    <li class="nk-menu-item">
                        <a href="{{ route('ourservices') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-layers-fill"></em></span>
                            <span class="nk-menu-text">Services</span>
                        </a>
                    </li>

                    <li class="nk-menu-item">
                        <a href="{{ route('order.index') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-list-check"></em></span>
                            <span class="nk-menu-text">Order History</span>
                        </a>
                    </li>

                    <li class="nk-menu-heading"><h6 class="overline-title text-primary-alt">Support</h6></li>
                    
                    <li class="nk-menu-item">
                        <a href="{{ route('faqs.index') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-help-fill"></em></span>
                            <span class="nk-menu-text">FAQs</span>
                        </a>
                    </li>

                    <li class="nk-menu-item">
                        <a href="/tickets" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-ticket-fill"></em></span>
                            <span class="nk-menu-text">Support Tickets</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>