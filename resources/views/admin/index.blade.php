@extends('admin.layouts.app')

@section('css')
<style>
    /* Executive Theme Overrides */
    :root { 
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
        --success-gradient: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); 
        --dark-glass: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
    }
    
    .content-wrapper { background-color: #f4f7fe; }
    .card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
    
    /* Interactive Stats Boxes */
    .small-box { border-radius: 20px; background: #fff !important; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid rgba(0,0,0,0.02); }
    .small-box:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.06); }
    
    .table thead th { 
        background: transparent; 
        text-transform: uppercase; 
        font-size: 10px; 
        font-weight: 800; 
        color: #94a3b8; 
        letter-spacing: 1px;
        border-top: none;
    }

    .pulse-live {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse-green 2s infinite;
    }

    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
</style>
@endsection

@section('content')
<div class="content-wrapper" x-data="{ 
    refreshing: false, 
    currentTime: '{{ now()->format('H:i:s') }}' 
}" x-init="setInterval(() => { currentTime = new Date().toLocaleTimeString() }, 1000)">
    
    <div class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="font-weight-bold text-dark mb-1" style="letter-spacing: -1.5px;">VibeAndBurn Executive View</h1>
                    <p class="text-muted small mb-0">Platform Overview & Financial Intelligence</p>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-white shadow-sm border-0 px-4 py-2 mr-3" style="border-radius: 12px;">
                        <i class="fas fa-file-export mr-2 text-primary"></i> <span class="small font-weight-bold">Reports</span>
                    </button>
                    <div class="bg-white shadow-sm py-2 px-3 border-0 d-flex align-items-center" style="border-radius: 12px;">
                        <span class="pulse-live mr-2"></span>
                        <span class="small font-weight-bold text-dark" x-text="currentTime"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box p-4 shadow-sm">
                        <p class="text-muted mb-1 small font-weight-bold text-uppercase">Total Orders</p>
                        <h2 class="font-weight-bold mb-2">{{ number_format($orderStats->total) }}</h2>
                        <span class="badge badge-soft-warning px-2 py-1"><i class="fas fa-clock mr-1"></i> {{ $orderStats->pending }} Pending</span>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box p-4 shadow-sm border-left border-success" style="border-left-width: 4px !important;">
                        <p class="text-muted mb-1 small font-weight-bold text-uppercase">User Growth</p>
                        <h2 class="font-weight-bold mb-2">{{ number_format($usersCounter) }}</h2>
                        <span class="text-success small font-weight-bold"><i class="fas fa-arrow-up mr-1"></i> +{{ $newUsersThisWeek }} this week</span>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box p-4 shadow-sm text-white" style="background: var(--primary-gradient) !important;">
                        <p class="text-white-50 mb-1 small font-weight-bold text-uppercase">Revenue</p>
                        <h2 class="font-weight-bold mb-2">${{ number_format($orderStats->total_revenue ?? 0, 2) }}</h2>
                        <small class="text-white-50">Est. Profit: ${{ number_format($orderStats->total_revenue * 0.4, 2) }}</small>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box p-4 shadow-sm bg-dark text-white">
                        <p class="text-muted mb-1 small font-weight-bold text-uppercase">Market Reach</p>
                        <h2 class="font-weight-bold mb-2">{{ number_format($subscribersCounter) }}</h2>
                        <small class="text-muted">Direct Email Subs</small>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-lg-8" x-data="{ tableSearch: '' }">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 py-4 d-flex justify-content-between align-items-center">
                            <h5 class="font-weight-bold text-dark mb-0">Live Order Stream</h5>
                            <div class="d-flex">
                                <input type="text" x-model="tableSearch" placeholder="Search orders..." class="form-control form-control-sm border-0 bg-light mr-3" style="border-radius: 8px; width: 200px;">
                                <button @click="refreshing = true; setTimeout(() => { refreshing = false; window.location.reload() }, 1000)" 
                                        class="btn btn-sm btn-light border-0" 
                                        :disabled="refreshing">
                                    <i class="fas fa-sync-alt" :class="refreshing ? 'fa-spin' : ''"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="pl-4">ID</th>
                                            <th>Service</th>
                                            <th class="text-center">Qty</th>
                                            <th>Status</th>
                                            <th class="text-right pr-4">Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $order)
                                        <tr x-show="tableSearch === '' || '{{ $order->service_name }}'.toLowerCase().includes(tableSearch.toLowerCase())">
                                            <td class="pl-4 py-3">
                                                <span class="font-weight-bold text-primary">#{{ $order->id }}</span>
                                                <div class="text-muted x-small" style="font-size: 10px;">{{ $order->created_at->diffForHumans() }}</div>
                                            </td>
                                            <td>
                                                <div class="font-weight-600 text-dark small">{{ Str::limit($order->service_name, 40) }}</div>
                                                <div class="text-muted" style="font-size: 10px;"><i class="fas fa-link mr-1"></i>{{ Str::limit($order->link, 30) }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-light py-1 px-2 border" style="font-weight: 500;">{{ number_format($order->quantity) }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusMap = [
                                                        'completed' => ['bg' => '#e6fffa', 'color' => '#047857', 'label' => 'DONE'],
                                                        'pending'   => ['bg' => '#fffbeb', 'color' => '#b45309', 'label' => 'WAIT'],
                                                        'canceled'  => ['bg' => '#fef2f2', 'color' => '#b91c1c', 'label' => 'VOID'],
                                                    ];
                                                    $style = $statusMap[$order->status] ?? ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => 'INFO'];
                                                @endphp
                                                <span class="badge border-0" style="background: {{ $style['bg'] }}; color: {{ $style['color'] }}; font-size: 10px; padding: 5px 10px;">
                                                    {{ $style['label'] }}
                                                </span>
                                            </td>
                                            <td class="text-right pr-4 font-weight-bold text-dark">${{ number_format($order->charge, 3) }}</td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="5" class="text-center py-5 text-muted">No active transmissions.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center pb-4">
                            <a href="{{ route('admin.clientOrders.index') }}" class="btn btn-sm btn-light px-4 font-weight-bold" style="border-radius: 8px;">View Full History</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card text-white mb-4 shadow-lg border-0" style="background: var(--dark-glass); border-radius: 24px;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div class="bg-success rounded-lg p-2"><i class="fas fa-vault text-white"></i></div>
                                <span class="badge badge-pill badge-light-50 small" style="background: rgba(255,255,255,0.1)">SECURE GATEWAY</span>
                            </div>
                            <label class="text-white-50 small mb-0 font-weight-bold">LIQUIDITY POOL</label>
                            <h2 class="font-weight-bold mb-4">${{ number_format($walletsTotal, 2) }}</h2>
                            
                            <div class="mb-2 d-flex justify-content-between small">
                                <span class="text-white-50">Deposit Volume</span>
                                <span class="font-weight-bold text-success">${{ number_format($fundsTotal, 2) }}</span>
                            </div>
                            <div class="progress mb-4" style="height: 6px; background: rgba(255,255,255,0.1); border-radius: 10px;">
                                <div class="progress-bar bg-success" style="width: 85%; border-radius: 10px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="font-weight-bold text-dark mb-0">System Control</h6>
                        </div>
                        <div class="card-body px-3 pt-0 pb-3">
                            <div class="list-group list-group-flush">
                                <button class="list-group-item list-group-item-action border-0 rounded-lg mb-2 py-3 d-flex align-items-center bg-light">
                                    <div class="bg-white rounded p-2 mr-3 shadow-sm text-primary"><i class="fas fa-link"></i></div>
                                    <div class="text-left"><span class="d-block font-weight-bold small text-dark">API Node Status</span><small class="text-muted">4 Providers Active</small></div>
                                    <span class="ml-auto badge badge-success">ONLINE</span>
                                </button>
                                
                                <button class="list-group-item list-group-item-action border-0 rounded-lg py-3 d-flex align-items-center bg-light">
                                    <div class="bg-white rounded p-2 mr-3 shadow-sm text-info"><i class="fas fa-cog"></i></div>
                                    <div class="text-left"><span class="d-block font-weight-bold small text-dark">Margin Settings</span><small class="text-muted">Current: 40% Global</small></div>
                                    <i class="fas fa-chevron-right ml-auto text-muted small"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection