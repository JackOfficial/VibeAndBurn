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
                            <h3>{{$ordersCounter}}</h3>
                            <p>Total Orders 
                                <span class="badge badge-danger ml-2">{{$pendingOrders}} Pending</span>
                            </p>
                        </div>
                        <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                        <a href="{{route('admin.clientOrders.index')}}" class="small-box-footer">Manage Orders <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-success shadow-sm">
                        <div class="inner">
                            <h3>{{$usersCounter}}</h3>
                            <p>Active Users</p>
                        </div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <a href="{{route('admin.users.index')}}" class="small-box-footer">View Users <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-warning shadow-sm text-white">
                        <div class="inner">
                            <h3>{{$walletsCounter}}</h3>
                            <p>Active Wallets</p>
                        </div>
                        <div class="icon"><i class="fas fa-wallet"></i></div>
                        <a href="{{route('admin.wallet.index')}}" class="small-box-footer" style="color: rgba(255,255,255,0.8) !important;">Wallet Logs <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-danger shadow-sm">
                        <div class="inner">
                            <h3>{{$subscribersCounter}}</h3>
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
                                        {{-- You can loop through your $recentOrders here --}}
                                        @forelse($recentOrders ?? [] as $order)
                                        <tr>
                                            <td><a href="#">#{{ $order->id }}</a></td>
                                            <td>{{ $order->service_name }}</td>
                                            <td><span class="badge badge-info">{{ $order->status_label }}</span></td>
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
                        <div class="card-header">
                            <h3 class="card-title"><i class="ion ion-clipboard mr-1"></i> Admin Tasks</h3>
                        </div>
                        <div class="card-body">
                            <ul class="todo-list" data-widget="todo-list">
                                <li>
                                    <span class="text">Update SMM Provider APIs</span>
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

                    <div class="card bg-gradient-success shadow-sm">
                        <div class="card-header border-0">
                            <h3 class="card-title"><i class="far fa-calendar-alt mr-1"></i> Calendar</h3>
                        </div>
                        <div class="card-body pt-0">
                            <div id="calendar" style="width: 100%"></div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>

@endsection