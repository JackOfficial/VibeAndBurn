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
                
                <div class="nk-sidebar-widget d-none d-xl-block mx-3 my-4">
                    <div class="user-card user-card-s2" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 15px; padding: 1.25rem;">
                        <div class="user-avatar lg bg-primary-dim mb-3">
                            <span class="text-primary fw-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            <div class="status dot dot-lg dot-success"></div>
                        </div>
                        <div class="user-info">
                            <span class="lead-text text-white fw-bold">{{ Auth::user()->name }}</span>
                            <span class="sub-text text-soft mb-2">{{ Auth::user()->email }}</span>
                            <span class="badge badge-outline-primary badge-pill" style="font-size: 10px; text-transform: uppercase; letter-spacing: 1px;">VIP Member</span>
                        </div>
                        
                        <div class="user-balance-card mt-3 pt-3 border-top border-light">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="overline-title-alt text-soft mb-0">Balance</h6>
                                    <div class="user-balance text-white h4 mb-0 fw-bold">
                                        <small class="text-primary">$</small>{{ number_format(Auth::user()->balance, 2) }}
                                    </div>
                                </div>
                                <a href="{{ route('addFund.create') }}" class="btn btn-icon btn-trigger btn-tooltip" title="Quick Deposit">
                                    <em class="icon ni ni-plus-circle-fill text-primary" style="font-size: 1.5rem;"></em>
                                </a>
                            </div>
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