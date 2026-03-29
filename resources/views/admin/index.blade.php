@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-dark">VibeAndBurn Analytics</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-info shadow-sm">
                        <div class="inner">
                            <h3>{{ number_format($orderStats->total) }}</h3>
                            <p>Total Orders 
                                <span class="badge badge-danger ml-2">{{ $orderStats->pending }} Pending</span>
                            </p>
                        </div>
                        <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                        <a href="{{route('admin.clientOrders.index')}}" class="small-box-footer">Manage Orders <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-success shadow-sm">
                        <div class="inner">
                            <h3>{{ number_format($usersCounter) }}</h3>
                            <p>Total Users <small class="badge badge-light ml-1">+{{ $newUsersThisWeek }} this week</small></p>
                        </div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <a href="{{route('admin.users.index')}}" class="small-box-footer">View Users <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-dark shadow-sm text-white">
                        <div class="inner">
                            <h3>${{ number_format($orderStats->total_revenue, 2) }}</h3>
                            <p>Total Revenue (Completed)</p>
                        </div>
                        <div class="icon"><i class="fas fa-chart-line"></i></div>
                        <a href="{{route('admin.wallet.index')}}" class="small-box-footer">Financial Logs <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-danger shadow-sm">
                        <div class="inner">
                            <h3>{{ number_format($subscribersCounter) }}</h3>
                            <p>Subscribers</p>
                        </div>
                        <div class="icon"><i class="fas fa-envelope"></i></div>
                        <a href="{{ route('admin.subscription.index') }}" class="small-box-footer">Mailing List <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <section class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header border-0">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-th mr-1"></i> Revenue Trend (Last 6 Months)
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header border-transparent">
                            <h3 class="card-title font-weight-bold">Recent Orders</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Item</th>
                                            <th>Status</th>
                                            <th>Charge</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $order)
                                        <tr>
                                            <td><a href="{{ route('admin.clientOrders.show', $order->id) }}">#{{ $order->id }}</a></td>
                                            <td><small class="text-muted">{{ Str::limit($order->service_name, 40) }}</small></td>
                                            <td>
                                                @php
                                                    $badge = match($order->status) {
                                                        'pending', '0' => 'warning',
                                                        'completed' => 'success',
                                                        'processing' => 'primary',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge badge-{{ $badge }} text-capitalize">{{ $order->status }}</span>
                                            </td>
                                            <td>${{ number_format($order->charge, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-3 text-muted">No recent orders found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            <a href="{{route('admin.clientOrders.index')}}" class="btn btn-sm btn-secondary float-right">View All Orders</a>
                        </div>
                    </div>
                </section>

                <section class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h3 class="card-title font-weight-bold">System Health</h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <b>Total Wallet Balance</b> <a class="float-right">${{ number_format($walletsTotal, 2) }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Total Funds Deposited</b> <a class="float-right">${{ number_format($fundsTotal, 2) }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Fund Transactions</b> <a class="float-right">{{ number_format($fundsCounter) }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold"><i class="fas fa-tasks mr-1"></i> Quick Actions</h3>
                        </div>
                        <div class="card-body">
                            <ul class="todo-list" data-widget="todo-list">
                                <li>
                                    <span class="text">Update SMM APIs</span>
                                    <small class="badge badge-danger"><i class="far fa-clock"></i> Urgent</small>
                                </li>
                                <li>
                                    <span class="text">Check Pending Refunds</span>
                                    <small class="badge badge-info"><i class="far fa-clock"></i> 4 hours</small>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer clearfix">
                            <button type="button" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Add Task</button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
            datasets: [{
                label: 'Monthly Revenue ($)',
                data: {!! json_encode($monthlyRevenue->pluck('amount')) !!},
                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush

@endsection