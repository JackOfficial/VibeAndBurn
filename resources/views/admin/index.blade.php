@extends('admin.layouts.app')

@section('css')
<style>
    .small-box { border-radius: 15px; overflow: hidden; transition: transform 0.3s; position: relative; }
    .small-box:hover { transform: translateY(-5px); }
    .card { border-radius: 12px; border: none; margin-bottom: 1.5rem; }
    .badge { padding: 5px 10px; border-radius: 8px; font-weight: 500; }
    .revenue-gradient { background: linear-gradient(45deg, #28a745, #218838); }
    .table thead th { border-top: none; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; color: #6c757d; }
    .pulse-small { animation: pulse-animation 2s infinite; }
    @keyframes pulse-animation { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
    .truncate-text { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 13px; font-weight: 500; }
    .msg-avatar { width: 35px; height: 35px; border-radius: 50%; background: #007bff; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #fff; text-transform: uppercase; font-size: 14px; }
    .transition-hover:hover { background-color: #f8f9fa; cursor: pointer; }
</style>
@endsection

@section('content')
<div class="content-wrapper" x-data="{ refreshing: false, chartPeriod: '6months' }">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-dark">
                        <i class="fas fa-rocket mr-2 text-primary"></i>VibeAndBurn Executive View
                    </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <span class="badge badge-light shadow-sm py-2 px-3 border">
                        <i class="fas fa-sync-alt mr-1 text-primary" :class="refreshing ? 'fa-spin' : ''"></i> 
                        Live Stats: <span x-text="new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white shadow-sm border">
                        <div class="inner p-4">
                            <p class="text-muted mb-1 text-uppercase font-weight-bold" style="font-size: 11px;">Volume</p>
                            <h3 class="text-dark">{{ number_format($orderStats->total) }}</h3>
                            <span class="text-danger font-weight-bold" style="font-size: 12px;">
                                <i class="fas fa-clock mr-1"></i> {{ $orderStats->pending }} Pending
                            </span>
                        </div>
                        <div class="icon text-primary" style="opacity: 0.1;"><i class="fas fa-shopping-cart"></i></div>
                        <a href="{{ route('admin.clientOrders.index') }}" class="small-box-footer bg-light text-dark border-top">Manage Orders <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white shadow-sm border">
                        <div class="inner p-4">
                            <p class="text-muted mb-1 text-uppercase font-weight-bold" style="font-size: 11px;">Userbase</p>
                            <h3 class="text-dark">{{ number_format($usersCounter) }}</h3>
                            <span class="text-success font-weight-bold" style="font-size: 12px;">
                                <i class="fas fa-arrow-up mr-1"></i> +{{ $newUsersThisWeek }} New
                            </span>
                        </div>
                        <div class="icon text-success" style="opacity: 0.1;"><i class="fas fa-user-plus"></i></div>
                        <a href="{{ route('admin.users.index') }}" class="small-box-footer bg-light text-dark border-top">View Directory <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box shadow-sm text-white" style="background: linear-gradient(45deg, #28a745, #218838);">
                        <div class="inner p-4">
                            <p class="text-white-50 mb-1 text-uppercase font-weight-bold" style="font-size: 11px;">Revenue</p>
                            <h3 class="text-white">${{ number_format($orderStats->total_revenue ?? 0, 2) }}</h3>
                            <span class="text-white-50" style="font-size: 12px;">Lifetime Earnings</span>
                        </div>
                        <div class="icon" style="color: rgba(255,255,255,0.2)"><i class="fas fa-hand-holding-usd"></i></div>
                        <a href="{{ route('admin.wallet.index') }}" class="small-box-footer" style="background: rgba(0,0,0,0.1) !important;">
                            Audit Logs <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning shadow-sm text-dark">
                        <div class="inner p-4">
                            <p class="text-dark-50 mb-1 text-uppercase font-weight-bold" style="font-size: 11px;">Tickets</p>
                            <h3>{{ number_format($openTicketsCount ?? 0) }}</h3>
                            <span class="text-dark font-weight-bold" style="font-size: 12px;">Awaiting Reply</span>
                        </div>
                        <div class="icon" style="opacity: 0.2;"><i class="fas fa-ticket-alt"></i></div>
                        <a href="{{ route('admin.tickets') }}" class="small-box-footer" style="background: rgba(0,0,0,0.05)">Help Desk <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3 border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title font-weight-bold text-dark">Revenue Performance</h3>
                                <div class="btn-group btn-group-sm">
                                    <button @click="chartPeriod = '6months'" :class="chartPeriod === '6months' ? 'btn-primary' : 'btn-outline-secondary'" class="btn">6 Months</button>
                                    <button @click="chartPeriod = 'yearly'" :class="chartPeriod === 'yearly' ? 'btn-primary' : 'btn-outline-secondary'" class="btn">Yearly</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="height: 300px;">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="card-title font-weight-bold text-dark mb-0">Live Order Stream</h3>
                                <span class="badge badge-pill badge-light border text-muted mt-1">Recent Activities</span>
                            </div>
                            <button @click="refreshing = true; setTimeout(() => { window.location.reload() }, 1000)" class="btn btn-sm btn-primary shadow-sm">
                                <i class="fas fa-sync-alt mr-1" :class="refreshing ? 'fa-spin' : ''"></i> 
                                <span x-text="refreshing ? 'Fetching...' : 'Refresh'"></span>
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="pl-4 py-3">Order</th>
                                            <th>User</th>
                                            <th>Service</th>
                                            <th>Status</th>
                                            <th class="text-right pr-4">Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $order)
                                        <tr>
                                            <td class="pl-4 py-3">
                                                <a href="{{ route('admin.clientOrders.show', $order->id) }}" class="text-primary font-weight-bold">#{{ $order->id }}</a>
                                                <div class="text-muted small">{{ $order->created_at->diffForHumans() }}</div>
                                            </td>
                                            <td>
                                                <div class="text-dark font-weight-bold">{{ $order->user->name ?? 'Guest' }}</div>
                                                <div class="text-muted small truncate-text">{{ $order->link }}</div>
                                            </td>
                                            <td><div class="text-dark small font-weight-bold">{{ Str::limit($order->service_name, 25) }}</div></td>
                                            <td>
                                                @php
                                                    $statusStyle = match(strtolower($order->status)) {
                                                        'pending', '0' => 'warning',
                                                        'completed' => 'success',
                                                        'processing' => 'info',
                                                        'partial' => 'primary',
                                                        'canceled', 'cancelled' => 'danger',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusStyle }} text-uppercase" style="font-size: 10px;">{{ $order->status }}</span>
                                            </td>
                                            <td class="text-right pr-4 font-weight-bold">${{ number_format($order->charge, 3) }}</td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="5" class="text-center py-4">No recent orders</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card bg-gradient-dark shadow-sm text-white border-0">
                        <div class="card-body">
                            <h5 class="mb-3 font-weight-bold"><i class="fas fa-shield-alt mr-2 text-success"></i>Financial Health</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white-50">User Wallets</span>
                                <span class="font-weight-bold">${{ number_format($walletsTotal, 2) }}</span>
                            </div>
                            <div class="progress mb-3" style="height: 6px; border-radius: 10px; background: rgba(255,255,255,0.1)">
                                <div class="progress-bar bg-success" style="width: 70%"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-white-50">Gross Deposits</span>
                                <span class="font-weight-bold">${{ number_format($fundsTotal, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h3 class="card-title font-weight-bold"><i class="fas fa-ticket-alt mr-2 text-danger"></i>Active Support</h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @forelse($recentTickets as $ticket)
                                <li class="list-group-item border-0 transition-hover">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="truncate-text">
                                            <a href="{{ route('admin.ticket.show', $ticket->id) }}" class="text-dark font-weight-bold">{{ $ticket->subject }}</a>
                                            <div class="text-muted small">From: {{ $ticket->user->name ?? 'Client' }}</div>
                                        </div>
                                        <span class="badge badge-{{ $ticket->status == 'open' ? 'danger' : 'light' }}">{{ strtoupper($ticket->status) }}</span>
                                    </div>
                                </li>
                                @empty
                                <li class="list-group-item text-center py-4 text-muted">No pending tickets</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h3 class="card-title font-weight-bold"><i class="fas fa-envelope mr-2 text-primary"></i>Latest Replies</h3>
                        </div>
                        <div class="card-body p-0">
                            @forelse($recentMessages as $msg)
                            <div class="p-3 border-bottom transition-hover">
                                <div class="d-flex align-items-center">
                                    <div class="msg-avatar mr-3">
                                        {{ substr($msg->user->name ?? 'A', 0, 1) }}
                                    </div>
                                    <div style="flex: 1;">
                                        <div class="d-flex justify-content-between">
                                            <span class="font-weight-bold small">{{ $msg->user->name ?? 'Admin' }}</span>
                                            <span class="text-muted small" style="font-size: 10px;">{{ $msg->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-muted mb-0 small">{{ Str::limit($msg->message ?? $msg->reply, 45) }}</p>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="p-4 text-center text-muted">No recent messages</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-2">
                            <a href="{{ route('admin.subscription.index') }}" class="btn btn-light btn-block text-left py-2 px-3 border-0">
                                <i class="fas fa-bullhorn text-primary mr-2"></i> Marketing List
                                <span class="badge badge-dark float-right">{{ $subscribersCounter }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection