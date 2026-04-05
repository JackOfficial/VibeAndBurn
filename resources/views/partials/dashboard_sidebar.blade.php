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
                
                <div class="nk-sidebar-widget">
    <div class="wallet-card p-3" style="background: linear-gradient(135deg, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0.02) 100%); border: 1px solid rgba(255,255,255,0.1); border-radius: 14px; backdrop-filter: blur(8px);">
        <div class="d-flex align-items-center justify-content-between">
            <div class="user-account-info">
                <h6 class="overline-title-alt text-primary-alt mb-1" style="font-size: 9px; letter-spacing: 1px; opacity: 0.8;">CURRENT BALANCE</h6>
                <div class="user-balance text-white fw-bold" style="font-size: 1.25rem; letter-spacing: -0.5px;">
                    <span class="text-primary mr-1">$</span>{{ number_format(Auth::user()->balance, 2) }}
                </div>
            </div>
            
            <a href="{{ route('addFund.create') }}" class="btn btn-primary btn-round shadow-lg" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
                <em class="icon ni ni-plus" style="font-size: 1.2rem;"></em>
            </a>
        </div>
    </div>
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
                        <a href="#" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-reload"></em></span>
                            <span class="nk-menu-text">Refill</span>
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

                    <li class="nk-menu-heading"><h6 class="overline-title text-primary-alt">Account & Billing</h6></li>

                    <li class="nk-menu-item">
                        <a href="{{ route('addFund.create') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-wallet-fill"></em></span>
                            <span class="nk-menu-text">Add Funds</span>
                        </a>
                    </li>

                    <li class="nk-menu-item">
                        <a href="{{ route('mybonus') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-gift"></em></span>
                            <span class="nk-menu-text">My Bonus</span>
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

                    <li class="nk-menu-item">
                        <a href="/terms-and-conditions" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-policy-fill"></em></span>
                            <span class="nk-menu-text">Terms of Use</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>