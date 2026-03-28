<div class="nk-block nk-block-lg">
    <div class="nk-block-head">
        <div class="nk-block-head-content">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="nk-block-title">My Orders</h4>
                    <div class="nk-block-des">
                        <p class="text-soft">You have total <strong>{{ $ordersCounter }}</strong> {{ Str::plural('Order', $ordersCounter) }}</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="form-group mb-0 mr-3 d-flex align-items-center">
                        <label class="mr-2 mb-0 small text-muted text-uppercase">Filter</label>
                        <select wire:model="filter" class="form-control form-control-sm form-select border-light shadow-sm" style="width: 140px;">
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
                            <th class="pl-4">ID</th>
                            <th>Date</th>
                            <th>Link</th>
                            <th>Charge</th>
                            <th>Qty</th>
                            <th>Service & Category</th>
                            <th>Rate</th>
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
                            
                            {{-- Eloquent Relationship Access --}}
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

                            <td>${{ number_format((float)($order->service->rate_per_1000 ?? 0), 2) }}</td>
                            <td class="text-soft">{{ number_format((int)($order->start_count ?? 0)) }}</td>
                            <td class="text-danger">{{ number_format((int)($order->remains ?? 0)) }}</td>
                            
                            <td>
                                @php
                                    $statusConfig = match($order->status) {
                                        0 => ['class' => 'badge-warning', 'label' => 'Pending'],
                                        1 => ['class' => 'badge-success', 'label' => 'Completed'],
                                        2 => ['class' => 'badge-danger', 'label' => 'Reversed'],
                                        3 => ['class' => 'badge-info', 'label' => 'Processing'],
                                        4 => ['class' => 'badge-primary', 'label' => 'In Progress'],
                                        default => ['class' => 'badge-secondary', 'label' => 'Partial'],
                                    };
                                @endphp
                                <span class="badge badge-dot {{ $statusConfig['class'] }}">
                                    {{ $statusConfig['label'] }}
                                </span>
                                
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="text-muted">
                                    <em class="icon ni ni-info-fill fs-36px mb-2"></em>
                                    <p>No records found!</p>
                                    <a href="{{ route('newOrder.create') }}" class="btn btn-outline-primary btn-sm mt-2">Make New Order</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>