<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-4">

                {{-- Session Alerts --}}
                @if (Session::has('approveOrderSuccess'))
                    <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm border-0" style="border-left: 5px solid #28a745 !important;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fas fa-check-circle mr-2"></i> {{ Session::get('approveOrderSuccess') }}
                    </div>
                @elseif(Session::has('approveOrderFail'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm border-0" style="border-left: 5px solid #dc3545 !important;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fas fa-exclamation-triangle mr-2"></i> {{ Session::get('approveOrderFail') }}
                    </div>
                @endif

                <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            
                            {{-- Left Side: Stats & Filters --}}
                            <div class="d-flex align-items-center flex-wrap" style="gap: 15px;">
                                <h3 class="card-title font-weight-bold text-dark mr-3" style="font-size: 1.25rem;">Orders Management</h3>
                                <select wire:model="filterStatus" class="form-control form-control-sm border-light bg-light shadow-none" style="width: 150px; border-radius: 8px;">
                                    <option value="">All Statuses</option>
                                    @foreach([0=>'Pending', 1=>'Completed', 2=>'Reversed', 3=>'Processing', 4=>'In Progress', 5=>'Partial'] as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <span class="badge badge-primary-soft text-primary px-3 py-2" style="background: #e7f1ff; border-radius: 8px;">
                                    <strong>{{ number_format($ordersCounter) }}</strong> Orders Found
                                </span>
                            </div>

                            {{-- Right Side: Search --}}
                            <div class="input-group" style="width: 320px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-0"><i class="fa fa-search text-muted"></i></span>
                                </div>
                                <input type="text" wire:model.debounce.500ms="keyword" class="form-control bg-light border-0 shadow-none" placeholder="Search ID, User, or Link..." style="border-radius: 0 8px 8px 0;">
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        {{-- Sticky Header Wrapper --}}
                        <div class="table-responsive" style="max-height: 700px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="thead-light" style="position: sticky; top: 0; z-index: 10;">
                                    <tr class="text-muted text-uppercase small font-weight-bold">
                                        <th class="border-0 px-4 bg-light"># / Update</th>
                                        <th class="border-0 bg-light">Source/ID</th>
                                        <th class="border-0 bg-light">Customer</th>
                                        <th class="border-0 bg-light">Service & Link</th>
                                        <th class="border-0 text-center bg-light">Price / Qty</th>
                                        <th class="border-0 bg-light">Total Charge</th>
                                        <th class="border-0 text-center bg-light">Status</th>
                                        <th class="border-0 text-right px-4 bg-light">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $order)
                                        @php
                                            $statusColors = [
                                                0 => ['bg' => '#ffe5e5', 'text' => '#dc3545'], 
                                                1 => ['bg' => '#e6fffa', 'text' => '#28a745'], 
                                                2 => ['bg' => '#fff4e5', 'text' => '#fd7e14'], 
                                                3 => ['bg' => '#e5f6ff', 'text' => '#17a2b8'], 
                                                4 => ['bg' => '#f0f0ff', 'text' => '#6610f2'], 
                                                5 => ['bg' => '#f8f9fa', 'text' => '#6c757d'], 
                                            ];
                                            $style = $statusColors[$order->status] ?? ['bg' => '#eee', 'text' => '#333'];
                                            $statusName = [0=>'Pending', 1=>'Completed', 2=>'Reversed', 3=>'Processing', 4=>'In Progress', 5=>'Partial'][$order->status] ?? 'Unknown';
                                        @endphp
                                        <tr style="transition: all 0.2s ease;">
                                            <td class="px-4">
                                                <div class="d-flex align-items-center" style="gap: 8px;">
                                                    <span class="text-muted small">#{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</span>
                                                    <div class="input-group input-group-sm" style="width: 120px;">
                                                        <select wire:model="status" class="form-control custom-select-sm border-light">
                                                            <option value="">Update</option>
                                                            <option value="1">Complete</option>
                                                            <option value="2">Reverse</option>
                                                            <option value="3">Process</option>
                                                        </select>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-secondary" wire:click="changeStatus({{$order->id}})"><i class="fas fa-check fa-xs"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                           <td>
    <div class="d-flex flex-column">
        {{-- Internal DB ID --}}
        <span class="text-dark font-weight-bold" style="font-size: 0.9rem;">
            ID: #{{ $order->id }}
        </span>

        {{-- API Order ID (Only if not "MINE" and exists) --}}
        @if($order->service && $order->service->source && $order->service->source->api_source !== "MINE" && $order->orderId)
            <span class="text-muted small" style="font-size: 0.75rem;">
                <i class="fas fa-plug mr-1"></i>API: {{ $order->orderId }}
            </span>
        @endif

        {{-- Source Badge --}}
        <div class="mt-1">
            @if($order->service && $order->service->source)
                <span class="badge badge-pill text-uppercase" style="background: rgba(121, 70, 233, 0.1); color: #7946E9; font-size: 8px; letter-spacing: 0.5px;">
                    {{ $order->service->source->api_source }}
                </span>
            @else
                <span class="badge badge-pill badge-light text-muted" style="font-size: 8px;">MANUAL</span>
            @endif
        </div>
    </div>
</td>

       <td>
    <div class="d-flex align-items-center">
        {{-- Avatar Section --}}
        <div class="avatar-sm mr-2 bg-light text-primary d-flex align-items-center justify-content-center rounded-circle" 
             style="width: 32px; height: 32px; font-size: 12px; font-weight: bold; overflow: hidden;">
            
            @if($order->user && $order->user->avatar)
                {{-- Show Socialite/Google Avatar --}}
                <img src="{{ $order->user->avatar }}" 
                     alt="{{ $order->user->name }}" 
                     style="width: 100%; height: 100%; object-fit: cover;">
            @else
                {{-- Fallback to First Letter --}}
                {{ strtoupper(substr($order->user->name ?? 'U', 0, 1)) }}
            @endif
            
        </div>

        {{-- User Info Section --}}
        <div>
            <a href="javascript:void(0)" wire:click="userWalletDetails({{ $order->user_id }})" 
               class="text-dark font-weight-bold d-block mb-0" style="font-size: 0.9rem;">
                {{ Str::limit($order->user->name ?? 'Deleted', 15) }}
            </a>
            <small class="text-muted">{{ Str::limit($order->user->email ?? '', 20) }}</small>
        </div>
    </div>
</td>

                                            <td x-data="{ copied: false }">
                                                <span class="d-block text-truncate text-dark mb-1" style="max-width: 200px; font-size: 0.85rem;" title="{{ $order->service->service ?? '' }}">
                                                    {{ $order->service->service ?? 'Service Deleted' }}
                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ $order->link }}" target="_blank" class="badge badge-light text-info mr-2">
                                                        <i class="fas fa-link mr-1"></i> Visit
                                                    </a>
                                                    <button 
                                                        @click="navigator.clipboard.writeText('{{ $order->link }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                                        class="btn btn-link btn-sm p-0 text-muted" style="text-decoration: none;">
                                                        <i :class="copied ? 'fas fa-check text-success' : 'far fa-copy'"></i>
                                                        <span x-show="copied" x-cloak class="small ml-1 text-success">Copied!</span>
                                                    </button>
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <small class="text-muted d-block">Rate: ${{ number_format((float)($order->service->rate_per_1000 ?? 0), 2) }}</small>
                                                <span class="badge badge-light border text-dark">{{ number_format((int)$order->quantity) }}</span>
                                            </td>

                                            <td>
                                                <span class="text-dark font-weight-bold" style="font-size: 1rem;">${{ number_format((float)$order->charge, 3) }}</span>
                                            </td>

                                            <td class="text-center">
                                                <div style="background: {{ $style['bg'] }}; color: {{ $style['text'] }}; border-radius: 20px; padding: 4px 12px; display: inline-block; font-size: 0.75rem; font-weight: 700;">
                                                    <i class="fas fa-circle mr-1" style="font-size: 6px; vertical-align: middle;"></i>
                                                    {{ strtoupper($statusName) }}
                                                </div>
                                                <small class="d-block text-muted mt-1" style="font-size: 0.7rem;">{{ $order->created_at->diffForHumans() }}</small>
                                            </td>

                                            <td class="text-right px-4">
                                                <div class="btn-group">
                                                    <a href="{{ route('approve', $order->id) }}" class="btn btn-sm btn-outline-success border-0"><i class="fa fa-check"></i></a>
                                                    <a href="{{ route('admin.clientOrders.edit', $order->id) }}" class="btn btn-sm btn-outline-info border-0"><i class="fa fa-edit"></i></a>
                                                    <button onclick="confirm('Reverse money back to user?') || event.stopImmediatePropagation()" wire:click="reverseOrder({{ $order->id }})" class="btn btn-sm btn-outline-danger border-0">
                                                        <i class="fa fa-undo-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <p class="text-muted">No orders found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Card Footer for Pagination --}}
                    <div class="card-footer bg-white py-3 border-top">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing <b>{{ $orders->firstItem() }}</b> to <b>{{ $orders->lastItem() }}</b> of <b>{{ $orders->total() }}</b> results
                            </div>
                            <div class="mt-2 mt-md-0">
                                {{ $orders->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>