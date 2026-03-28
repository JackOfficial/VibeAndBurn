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
                                    <option value="0">Pending</option>
                                    <option value="1">Completed</option>
                                    <option value="2">Reversed</option>
                                    <option value="3">Processing</option>
                                    <option value="4">In Progress</option>
                                    <option value="5">Partial</option>
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
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="thead-light">
                                    <tr class="text-muted text-uppercase small font-weight-bold">
                                        <th class="border-0 px-4"># / Update</th>
                                        <th class="border-0">Source/ID</th>
                                        <th class="border-0">Customer</th>
                                        <th class="border-0">Service & Link</th>
                                        <th class="border-0 text-center">Price / Qty</th>
                                        <th class="border-0">Total Charge</th>
                                        <th class="border-0 text-center">Status</th>
                                        <th class="border-0 text-right px-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $order)
                                        @php
                                            $statusColors = [
                                                0 => ['bg' => '#ffe5e5', 'text' => '#dc3545'], // Pending
                                                1 => ['bg' => '#e6fffa', 'text' => '#28a745'], // Completed
                                                2 => ['bg' => '#fff4e5', 'text' => '#fd7e14'], // Reversed
                                                3 => ['bg' => '#e5f6ff', 'text' => '#17a2b8'], // Processing
                                                4 => ['bg' => '#f0f0ff', 'text' => '#6610f2'], // Progress
                                                5 => ['bg' => '#f8f9fa', 'text' => '#6c757d'], // Partial
                                            ];
                                            $style = $statusColors[$order->status] ?? ['bg' => '#eee', 'text' => '#333'];
                                            $statusName = [0=>'Pending', 1=>'Completed', 2=>'Reversed', 3=>'Processing', 4=>'In Progress', 5=>'Partial'][$order->status] ?? 'Unknown';
                                        @endphp
                                        <tr style="transition: all 0.2s ease;">
                                            {{-- Update Section --}}
                                            <td class="px-4">
                                                <div class="d-flex align-items-center" style="gap: 8px;">
                                                    <span class="text-muted small">#{{ $loop->iteration }}</span>
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

                                            {{-- ID & Source --}}
                                            <td>
                                                <span class="font-weight-bold d-block text-dark">#{{ $order->orderId ?? $order->id }}</span>
                                                @if($order->service && $order->service->source)
                                                    <span class="badge badge-pill text-uppercase" style="background: rgba(121, 70, 233, 0.1); color: #7946E9; font-size: 8px;">
                                                        {{ $order->service->source->api_source }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-pill badge-light text-muted" style="font-size: 8px;">MANUAL</span>
                                                @endif
                                            </td>

                                            {{-- Customer --}}
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm mr-2 bg-light text-primary d-flex align-items-center justify-content-center rounded-circle" style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                                                        {{ strtoupper(substr($order->user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0)" wire:click="userWalletDetails({{ $order->user_id }})" class="text-dark font-weight-bold d-block mb-0" style="font-size: 0.9rem;">
                                                            {{ Str::limit($order->user->name ?? 'Deleted', 15) }}
                                                        </a>
                                                        <small class="text-muted">{{ Str::limit($order->user->email ?? '', 20) }}</small>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Service & Copy Link --}}
                                            <td x-data="{ copied: false }">
                                                <span class="d-block text-truncate text-dark mb-1" style="max-width: 200px; font-size: 0.85rem;" title="{{ $order->service->service ?? '' }}">
                                                    {{ $order->service->service ?? 'Service Deleted' }}
                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ $order->link }}" target="_blank" class="badge badge-light text-info mr-2">
                                                        <i class="fas fa-link mr-1"></i> Visit
                                                    </a>
                                                    {{-- Alpine.js Copy Logic --}}
                                                    <button 
                                                        @click="navigator.clipboard.writeText('{{ $order->link }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                                        class="btn btn-link btn-sm p-0 text-muted"
                                                        style="text-decoration: none;">
                                                        <i :class="copied ? 'fas fa-check text-success' : 'far fa-copy'"></i>
                                                        <span x-show="copied" x-cloak class="small ml-1 text-success">Copied!</span>
                                                    </button>
                                                </div>
                                            </td>

                                            {{-- Price & Quantity --}}
                                            <td class="text-center">
                                                <small class="text-muted d-block">Rate: ${{ number_format($order->service->rate_per_1000 ?? 0, 2) }}</small>
                                                <span class="badge badge-light border text-dark">{{ number_format($order->quantity) }}</span>
                                            </td>

                                            {{-- Charge --}}
                                            <td>
                                                <span class="text-dark font-weight-bold" style="font-size: 1rem;">${{ number_format($order->charge, 3) }}</span>
                                            </td>

                                            {{-- Status Pill --}}
                                            <td class="text-center">
                                                <div style="background: {{ $style['bg'] }}; color: {{ $style['text'] }}; border-radius: 20px; padding: 4px 12px; display: inline-block; font-size: 0.75rem; font-weight: 700;">
                                                    <i class="fas fa-circle mr-1" style="font-size: 6px; vertical-align: middle;"></i>
                                                    {{ strtoupper($statusName) }}
                                                </div>
                                                <small class="d-block text-muted mt-1" style="font-size: 0.7rem;">{{ $order->created_at->diffForHumans() }}</small>
                                            </td>

                                            {{-- Actions --}}
                                            <td class="text-right px-4">
                                                <div class="btn-group">
                                                    <a href="{{ route('approve', $order->id) }}" class="btn btn-sm btn-outline-success border-0" title="Approve"><i class="fa fa-check"></i></a>
                                                    <a href="{{ route('admin.clientOrders.edit', $order->id) }}" class="btn btn-sm btn-outline-info border-0" title="Edit"><i class="fa fa-edit"></i></a>
                                                    <button onclick="confirm('Reverse money back to user?') || event.stopImmediatePropagation()" wire:click="reverseOrder({{ $order->id }})" class="btn btn-sm btn-outline-danger border-0">
                                                        <i class="fa fa-undo-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" style="width: 80px; opacity: 0.2;" class="mb-3">
                                                <p class="text-muted">No orders found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>