@extends('admin.layouts.app')

@section('css')
<style>
    .small-box { border-radius: 15px; overflow: hidden; transition: transform 0.3s; }
    .small-box:hover { transform: translateY(-5px); }
    .card { border-radius: 12px; border: none; }
    .badge { padding: 5px 10px; border-radius: 8px; font-weight: 500; }
    .revenue-gradient { background: linear-gradient(45deg, #28a745, #218838); }
    .table thead th { border-top: none; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; color: #6c757d; }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-dark"><i class="fas fa-rocket mr-2 text-primary"></i>VibeAndBurn Executive View</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <span class="badge badge-light shadow-sm py-2 px-3">
                        <i class="fas fa-sync-alt fa-spin mr-1 text-primary"></i> Live Stats: {{ now()->format('H:i A') }}
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
                        <div class="icon text-primary"><i class="fas fa-shopping-cart"></i></div>
                        <a href="{{route('admin.clientOrders.index')}}" class="small-box-footer bg-light text-dark border-top">Manage Orders <i class="fas fa-arrow-right ml-1"></i></a>
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
                        <div class="icon text-success"><i class="fas fa-user-plus"></i></div>
                        <a href="{{route('admin.users.index')}}" class="small-box-footer bg-light text-dark border-top">View Directory <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
    <div class="small-box bg-success shadow-sm" style="background: linear-gradient(45deg, #28a745, #218838) !important;">
        <div class="inner p-4">
            <p class="text-white-50 mb-1 text-uppercase font-weight-bold" style="font-size: 11px;">Revenue</p>
            <h3 class="text-white">${{ number_format($orderStats->total_revenue ?? 0, 2) }}</h3>
            <span class="text-white-50" style="font-size: 12px;">Lifetime Earnings</span>
        </div>
        <div class="icon" style="color: rgba(255,255,255,0.3)"><i class="fas fa-hand-holding-usd"></i></div>
        <a href="{{route('admin.wallet.index')}}" class="small-box-footer" style="background: rgba(0,0,0,0.1) !important;">
            Audit Logs <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
</div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-dark shadow-sm text-white">
                        <div class="inner p-4">
                            <p class="text-muted mb-1 text-uppercase font-weight-bold" style="font-size: 11px;">Marketing</p>
                            <h3>{{ number_format($subscribersCounter) }}</h3>
                            <span class="text-muted" style="font-size: 12px;">Newsletter List</span>
                        </div>
                        <div class="icon text-white-50"><i class="fas fa-bullhorn"></i></div>
                        <a href="{{ route('admin.subscription.index') }}" class="small-box-footer" style="background: rgba(255,255,255,0.05)">Campaigns <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3 border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title font-weight-bold text-dark">Revenue Performance</h3>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-secondary active">6 Months</button>
                                    <button class="btn btn-outline-secondary">Yearly</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="height: 300px;">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h3 class="card-title font-weight-bold text-dark">Live Order Stream</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="pl-4">ORDER</th>
                                            <th>SERVICE</th>
                                            <th>STATUS</th>
                                            <th class="text-right pr-4">CHARGE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $order)
                                        <tr>
                                            <td class="pl-4">
                                                <a href="{{ route('admin.clientOrders.show', $order->id) }}" class="text-primary font-weight-bold">#{{ $order->id }}</a>
                                                <div class="text-muted" style="font-size: 10px;">{{ $order->created_at->diffForHumans() }}</div>
                                            </td>
                                            <td>
                                                <span class="text-dark">{{ Str::limit($order->service_name, 35) }}</span>
                                                <div class="text-muted small">{{ $order->user->name ?? 'Guest' }}</div>
                                            </td>
                                            <td>
                                                @php
                                                    $color = match($order->status) {
                                                        'pending', '0' => 'warning',
                                                        'completed' => 'success',
                                                        'processing' => 'primary',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge badge-{{ $color }} text-uppercase" style="font-size: 10px;">{{ $order->status }}</span>
                                            </td>
                                            <td class="text-right pr-4">
                                                <span class="font-weight-bold text-dark">${{ number_format($order->charge, 2) }}</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="4" class="text-center py-5 text-muted">No activities found.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card bg-gradient-dark shadow-sm mb-4 text-white">
                        <div class="card-body">
                            <h5 class="mb-3 font-weight-bold"><i class="fas fa-shield-alt mr-2 text-success"></i>Finance Overview</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white-50">Locked Balance</span>
                                <span class="font-weight-bold">${{ number_format($walletsTotal, 2) }}</span>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 75%"></div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white-50">Gross Deposits</span>
                                <span class="font-weight-bold">${{ number_format($fundsTotal, 2) }}</span>
                            </div>
                            <hr style="border-top: 1px solid rgba(255,255,255,0.1)">
                            <div class="text-center mt-3">
                                <small class="text-white-50">Total Payment Transactions: <b>{{ $fundsCounter }}</b></small>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0">
                            <h3 class="card-title font-weight-bold"><i class="fas fa-bolt mr-2 text-warning"></i>Admin Shortcuts</h3>
                        </div>
                        <div class="card-body p-2">
                            <a href="#" class="btn btn-light btn-block text-left mb-2 py-2 px-3 border-0">
                                <i class="fas fa-plug text-primary mr-2"></i> Provider API Status
                                <span class="badge badge-success float-right">Online</span>
                            </a>
                            <a href="#" class="btn btn-light btn-block text-left mb-2 py-2 px-3 border-0">
                                <i class="fas fa-sync text-info mr-2"></i> Update Service Rates
                            </a>
                            <a href="#" class="btn btn-light btn-block text-left py-2 px-3 border-0">
                                <i class="fas fa-user-shield text-danger mr-2"></i> Security Audit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Create Gradient
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(40, 167, 69, 0.4)');
    gradient.addColorStop(1, 'rgba(40, 167, 69, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
            datasets: [{
                label: 'Earnings',
                data: {!! json_encode($monthlyRevenue->pluck('amount')) !!},
                backgroundColor: gradient,
                borderColor: '#28a745',
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#28a745',
                pointHoverRadius: 6,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { display: false }, ticks: { callback: value => '$' + value } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush