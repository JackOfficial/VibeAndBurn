<div class="nk-block nk-block-lg">
    <div class="nk-block-head">
        <div class="nk-block-head-content">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="nk-block-title">My Orders</h4>
                    <div class="nk-block-des">
                        <p class="text-soft">Total <strong>{{ $ordersCounter }}</strong> orders recorded</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="form-group mb-0 mr-2">
                        <div class="form-control-wrap">
                            <div class="form-icon form-icon-right">
                                <em class="icon ni ni-search"></em>
                            </div>
                            <input wire:model.debounce.400ms="search" type="text" class="form-control form-control-sm border-light shadow-sm" placeholder="Search ID or Link..." style="width: 200px;">
                        </div>
                    </div>

                    <div class="form-group mb-0 mr-3 d-flex align-items-center">
                        <select wire:model="filter" class="form-control form-control-sm form-select border-light shadow-sm" style="width: 130px;">
                            <option value="All">All Status</option>
                            <option value="Completed">Completed</option>
                            <option value="Processing">Processing</option>
                            <option value="In progress">In progress</option>
                            <option value="Partial">Partial</option>
                            <option value="Canceled">Canceled</option>
                        </select>
                    </div>

                    <a href="{{ route('newOrder.create') }}" class="btn btn-primary btn-sm shadow-sm">
                        <em class="icon ni ni-plus-sm"></em> <span>New Order</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-bordered card-preview shadow-sm">
        <div class="card-inner p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light small text-muted text-uppercase font-weight-bold">
                        <tr>
                            <th class="pl-4 cursor-pointer" wire:click="sortBy('id')">
                                ID @if($sortField === 'id') <em class="icon ni ni-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill text-primary"></em> @endif
                            </th>
                            <th class="cursor-pointer" wire:click="sortBy('created_at')">
                                Date @if($sortField === 'created_at') <em class="icon ni ni-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill text-primary"></em> @endif
                            </th>
                            <th>Link</th>
                            <th class="cursor-pointer" wire:click="sortBy('charge')">
                                Charge @if($sortField === 'charge') <em class="icon ni ni-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill text-primary"></em> @endif
                            </th>
                            <th>Qty</th>
                            <th>Service & Category</th>
                            <th>Start</th>
                            <th>Remains</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        @forelse ($orders as $order)
                        <tr class="align-middle">
                            <td class="pl-4"><span class="font-weight-bold text-dark">#{{ $order->id }}</span></td>
                            <td class="text-nowrap">
                                {{ $order->created_at->format('d M, Y') }}<br>
                                <small class="text-soft">{{ $order->created_at->format('H:i') }}</small>
                            </td>
                            <td style="max-width: 150px;">
                                <a href="{{ $order->link }}" target="_blank" class="text-primary text-truncate d-block" title="{{ $order->link }}">
                                    {{ str_replace(['https://', 'http://'], '', $order->link) }}
                                </a>
                            </td>
                            <td class="font-weight-bold text-dark">${{ number_format((float)($order->charge ?? 0), 3) }}</td>
                            <td>{{ number_format((int)($order->quantity ?? 0)) }}</td>
                            
                            <td style="max-width: 200px;">
                                <span class="d-block text-dark font-weight-bold text-truncate" title="{{ $order->service->service ?? 'N/A' }}">
                                    {{ $order->service->service ?? 'Service Deleted' }}
                                </span>
                                @if($order->service && $order->service->category)
                                    <span class="badge badge-dim badge-light text-soft" style="font-size: 10px;">
                                        {{ $order->service->category->socialmedia->socialmedia ?? '' }} - {{ $order->service->category->category }}
                                    </span>
                                @endif
                            </td>

                            <td class="text-soft">{{ number_format((int)($order->start_count ?? 0)) }}</td>
                            <td class="text-danger">{{ number_format((int)($order->remains ?? 0)) }}</td>
                            
                            <td>
                                @php
                                    $statusValue = (int) $order->status;
                                    $statusConfig = match($statusValue) {
                                        0 => ['class' => 'badge-warning', 'label' => 'Pending'],
                                        1 => ['class' => 'badge-success', 'label' => 'Completed'],
                                        2 => ['class' => 'badge-danger', 'label' => 'Reversed'],
                                        3 => ['class' => 'badge-info', 'label' => 'Processing'],
                                        4 => ['class' => 'badge-primary', 'label' => 'In Progress'],
                                        5 => ['class' => 'badge-secondary', 'label' => 'Partial'],
                                        default => ['class' => 'badge-dark', 'label' => 'Unknown'],
                                    };
                                @endphp
                                <span class="badge badge-dot {{ $statusConfig['class'] }}">
                                    {{ $statusConfig['label'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <em class="icon ni ni-info-fill fs-36px mb-2"></em>
                                    <p>No records found matching your criteria.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($orders->hasPages())
            <div class="card-inner border-top">
                <div class="d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>

    <style>
    .cursor-pointer { cursor: pointer; transition: background 0.2s; }
    .cursor-pointer:hover { background-color: #f4f6fa !important; }
    .table thead th { vertical-align: middle; }
</style>

</div>

