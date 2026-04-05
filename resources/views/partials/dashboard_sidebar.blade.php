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
                
                <div class="nk-sidebar-widget mx-3 my-2">
                    <div class="user-card-s2 p-3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="user-avatar sm bg-primary-dim">
                                <span class="text-primary fw-bold" style="font-size: 11px;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                <div class="status dot dot-xs dot-success"></div>
                            </div>
                            <div class="user-info ml-2" style="overflow: hidden;">
                                <span class="lead-text text-white fw-bold fs-13px text-truncate" style="display: block;">{{ Auth::user()->name }}</span>
                                <span class="badge badge-dim badge-primary badge-pill" style="font-size: 9px; padding: 0 6px;">VIP MEMBER</span>
                            </div>
                        </div>

                        <div class="pt-2 border-top border-light">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="overline-title-alt text-soft mb-0" style="font-size: 9px; letter-spacing: 0.5px;">AVAILABLE BALANCE</h6>
                                    <div class="user-balance text-white fw-bold" style="font-size: 1.15rem; line-height: 1;">
                                        <small class="text-primary" style="font-size: 0.7em;">$</small>{{ number_format(Auth::user()->balance, 2) }}
                                    </div>
                                </div>
                                <a href="{{ route('addFund.create') }}" class="btn btn-icon btn-sm btn-primary btn-round shadow-sm">
                                    <em class="icon ni ni-plus"></em>
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