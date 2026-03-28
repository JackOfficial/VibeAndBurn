<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 mt-4"> {{-- Increased top margin --}}

        {{-- Alerts with extra bottom margin --}}
        @if (Session::has('approveOrderSuccess'))
          <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle mr-2"></i> {{ Session::get('approveOrderSuccess') }}
          </div>
        @elseif(Session::has('approveOrderFail'))
          <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong><i class="fas fa-exclamation-triangle mr-2"></i> FAILED:</strong> {{ Session::get('approveOrderFail') }}
          </div>
        @endif

        <div class="card shadow-sm border-0"> {{-- Removed border for a modern look --}}
          <div class="card-header bg-white py-3"> {{-- Added vertical padding --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center" style="gap: 15px;">
              
              {{-- Left Side: Filters --}}
              <div class="d-flex align-items-center">
                <div class="mr-3">
                    <select wire:model="filterStatus" class="form-control form-control-sm border-secondary" style="width: 160px; height: 38px;">
                      <option value="">All Statuses</option>
                      <option value="0">Pending</option>
                      <option value="1">Completed</option>
                      <option value="2">Reversed</option>
                      <option value="3">Processing</option>
                      <option value="4">In Progress</option>
                      <option value="5">Partial</option>
                    </select>
                </div>
                <span class="badge badge-light border px-3 py-2 text-muted" style="font-size: 0.9rem;">
                    <strong>{{ number_format($ordersCounter) }}</strong> Total Orders
                </span>
                
                <div class="ml-3 text-primary" wire:loading wire:target="filterStatus">
                   <i class="fas fa-spinner fa-spin"></i>
                </div>
              </div>

              {{-- Right Side: Search --}}
              <div class="input-group" style="width: 300px;">
                <input type="text" wire:model.debounce.500ms="keyword" class="form-control border-right-0" placeholder="Search orders..." style="height: 38px;">
                <div class="input-group-append">
                  <span class="input-group-text bg-white border-left-0 text-muted"><i class="fa fa-search"></i></span>
                </div>
              </div>

            </div>
          </div>

          <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 750px;">
              {{-- Removed table-sm to give rows more height --}}
              <table class="table table-head-fixed table-hover mb-0">
                <thead class="bg-light">
                  <tr class="text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                    <th class="py-3">#</th>
                    <th class="py-3">Update Status</th>
                    <th class="py-3">Source & ID</th>
                    <th class="py-3">Customer</th>
                    <th class="py-3">Service Details</th>
                    <th class="py-3 text-center">Qty</th>
                    <th class="py-3">Charge</th>
                    <th class="py-3">Status</th>
                    <th class="py-3 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($orders as $order)
                    @php
                      $statusClasses = [
                        0 => 'badge-danger', 1 => 'badge-success', 2 => 'badge-warning',
                        3 => 'badge-info', 4 => 'badge-primary', 5 => 'badge-secondary'
                      ];
                      $currentBadge = $statusClasses[$order->status] ?? 'badge-dark';
                    @endphp
                    <tr>
                      <td class="align-middle text-muted px-3">{{ $loop->iteration }}</td>
                      
                      <td class="align-middle">
                        <div class="input-group input-group-sm" style="width: 170px;">
                          <select wire:model="status" class="form-control">
                            <option value="">Update...</option>
                            <option value="0">Pending</option>
                            <option value="1">Completed</option>
                            <option value="2">Reversed</option>
                            <option value="3">Processing</option>
                            <option value="4">In Progress</option>
                            <option value="5">Partial</option>
                          </select>
                          <div class="input-group-append">
                            <button class="btn btn-dark" wire:click="changeStatus({{$order->id}})">OK</button>
                          </div>
                        </div>
                      </td>

                      <td class="align-middle">
                        <span class="text-dark font-weight-bold d-block">ID: {{ $order->orderId }}</span>
                        @switch($order->source_id)
                          @case(2) <span class="badge badge-pill" style="background: #7946E9; color: white; font-size: 10px;">BulkFollows</span> @break
                          @case(3) <span class="badge badge-pill" style="background: #3AE3A4; color: white; font-size: 10px;">AmazingSMM</span> @break
                          @default <span class="badge badge-pill badge-secondary" style="font-size: 10px;">Manual</span>
                        @endswitch
                      </td>

                      <td class="align-middle">
                        <div class="d-flex flex-column">
                            <a href="javascript:void(0)" wire:click="userWalletDetails({{ $order->user_id }})" class="text-primary font-weight-bold mb-0">
                              {{ $order->name }}
                            </a>
                            <small class="text-muted">{{ $order->email }}</small>
                        </div>
                      </td>

                      <td class="align-middle">
                        <div style="max-width: 220px;">
                            <span class="d-block text-truncate font-weight-normal" title="{{ $order->service }}">
                              {{ $order->service }}
                            </span>
                            <a href="{{ $order->link }}" target="_blank" class="small text-info"><i class="fas fa-external-link-alt mr-1"></i>Visit Link</a>
                        </div>
                      </td>

                      <td class="align-middle text-center">
                        <span class="badge badge-light border font-weight-bold">{{ number_format((int)($order->quantity ?? 0)) }}</span>
                      </td>
                      
                      <td class="align-middle">
                        <span class="text-success font-weight-bold">${{ number_format((float)($order->charge ?? 0), 3) }}</span>
                      </td>

                      <td class="align-middle text-center">
    @php
        // Map the numeric status to a human-readable string
        $statusNames = [
            0 => 'Pending',
            1 => 'Completed',
            2 => 'Reversed',
            3 => 'Processing',
            4 => 'In Progress',
            5 => 'Partial'
        ];
        $statusText = $statusNames[$order->status] ?? 'Unknown';
    @endphp

    <span class="badge {{ $currentBadge }} px-2 py-1 shadow-sm" 
          style="font-size: 10px; min-width: 85px; letter-spacing: 0.5px;">
        {{ strtoupper($statusText) }}
    </span>
    
    <small class="d-block text-muted mt-1" style="font-size: 10px;">
        <i class="far fa-clock mr-1"></i>{{ $order->created_at->format('d M, H:i') }}
    </small>
</td>

                      <td class="align-middle text-right px-3">
                        <div class="btn-group shadow-sm">
                          <a href="{{ route('approve', $order->id) }}" class="btn btn-sm btn-white text-success border" title="Approve"><i class="fa fa-check"></i></a>
                          <a href="{{ route('admin.clientOrders.edit', $order->id) }}" class="btn btn-sm btn-white text-info border" title="Edit"><i class="fa fa-edit"></i></a>
                          <button onclick="confirm('Reverse this order?') || event.stopImmediatePropagation()" wire:click="reverseOrder({{ $order->id }})" class="btn btn-sm btn-white text-danger border" title="Reverse">
                            <i class="fa fa-undo-alt"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="9" class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p class="h5">No orders found.</p>
                        </div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="card-footer bg-white py-3 border-top">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Showing page {{ $orders->currentPage() }} of {{ $orders->lastPage() }}</small>
                <div>
                  {{ $orders->links() }}
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>