<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 mt-3">

        {{-- Alerts --}}
        @if (Session::has('approveOrderSuccess'))
          <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> {{ Session::get('approveOrderSuccess') }}
          </div>
        @elseif(Session::has('approveOrderFail'))
          <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>FAILED:</strong> {{ Session::get('approveOrderFail') }}
          </div>
        @endif

        <div class="card shadow-sm">
          <div class="card-header bg-white">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
              
              {{-- Left Side: Filters --}}
              <div class="d-flex align-items-center mb-2 mb-md-0">
                <select wire:model="filterStatus" class="form-control form-control-sm mr-2" style="width: 150px;">
                  <option value="">All Statuses</option>
                  <option value="0">Pending</option>
                  <option value="1">Completed</option>
                  <option value="2">Reversed</option>
                  <option value="3">Processing</option>
                  <option value="4">In Progress</option>
                  <option value="5">Partial</option>
                </select>
                <span class="badge badge-pill badge-light border">{{ number_format($ordersCounter) }} Orders</span>
                <div class="ml-2 text-primary small" wire:loading wire:target="filterStatus">
                   <i class="fas fa-spinner fa-spin"></i> Filtering...
                </div>
              </div>

              {{-- Right Side: Search --}}
              <div class="input-group input-group-sm" style="width: 250px;">
                <input type="text" wire:model.debounce.500ms="keyword" class="form-control" placeholder="Search Order ID, Link, User...">
                <div class="input-group-append">
                  <span class="input-group-text bg-primary text-white"><i class="fa fa-search"></i></span>
                </div>
              </div>

            </div>
          </div>

          <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 700px;">
              <table class="table table-head-fixed table-hover table-sm text-nowrap mb-0">
                <thead>
                  <tr class="text-uppercase small">
                    <th>#</th>
                    <th>Update Status</th>
                    <th>API Source</th>
                    <th>User Detail</th>
                    <th>Service</th>
                    <th>Quantity</th>
                    <th>Charge</th>
                    <th>Stats</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-right">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($orders as $order)
                    @php
                      $statusClasses = [
                        0 => 'badge-danger',    // Pending
                        1 => 'badge-success',   // Completed
                        2 => 'badge-warning',   // Reversed
                        3 => 'badge-info',      // Processing
                        4 => 'badge-primary',   // In Progress
                        5 => 'badge-secondary'  // Partial
                      ];
                      $currentBadge = $statusClasses[$order->status] ?? 'badge-dark';
                    @endphp
                    <tr>
                      <td class="align-middle text-muted">{{ $loop->iteration }}</td>
                      
                      <td class="align-middle" style="width: 180px;">
                        <div class="input-group input-group-sm">
                          <select wire:model="status" class="form-control custom-select">
                            <option value="">Update...</option>
                            <option value="0">Pending</option>
                            <option value="1">Completed</option>
                            <option value="2">Reversed</option>
                            <option value="3">Processing</option>
                            <option value="4">In Progress</option>
                            <option value="5">Partial</option>
                          </select>
                          <div class="input-group-append">
                            <button class="btn btn-primary" wire:click="changeStatus({{$order->id}})">OK</button>
                          </div>
                        </div>
                      </td>

                      <td class="align-middle">
                        <small class="d-block text-muted">ID: {{ $order->orderId }}</small>
                        @switch($order->source_id)
                          @case(2) <span class="badge" style="background: #7946E9; color: white;">BulkFollows</span> @break
                          @case(3) <span class="badge" style="background: #3AE3A4; color: white;">AmazingSMM</span> @break
                          @case(4) <span class="badge badge-primary">BulkMedya</span> @break
                          @default <span class="badge badge-secondary">Manual</span>
                        @endswitch
                      </td>

                      <td class="align-middle">
                        <a href="javascript:void(0)" wire:click="userWalletDetails({{ $order->user_id }})" class="font-weight-bold d-block">
                          {{ $order->name }}
                        </a>
                        <small class="text-muted">{{ $order->email }}</small>
                      </td>

                      <td class="align-middle">
                        <span class="d-block truncate" style="max-width: 200px;" title="{{ $order->service }}">
                          {{ $order->service }}
                        </span>
                        <a href="{{ $order->link }}" target="_blank" class="small text-info"><i class="fas fa-link"></i> View Link</a>
                      </td>

                      <td class="align-middle"><strong>{{ number_format($order->quantity) }}</strong></td>
                      
                      <td class="align-middle text-success font-weight-bold">${{ number_format($order->charge, 4) }}</td>

                      <td class="align-middle small">
                        Start: {{ $order->start_count }}<br>
                        Left: <span class="text-danger">{{ $order->remains }}</span>
                      </td>

                      <td class="align-middle">
                        <span class="badge {{ $currentBadge }} p-1 px-2">
                          {{ $order->status_name ?? 'Status '.$order->status }}
                        </span>
                      </td>

                      <td class="align-middle small text-muted">
                        {{ $order->created_at->format('M d, Y') }}<br>
                        {{ $order->created_at->format('H:i') }}
                      </td>

                      <td class="align-middle text-right">
                        <div class="btn-group">
                          <a href="{{ route('approve', $order->id) }}" class="btn btn-sm text-success" title="Approve"><i class="fa fa-check"></i></a>
                          <a href="{{ route('admin.clientOrders.edit', $order->id) }}" class="btn btn-sm text-info" title="Edit"><i class="fa fa-edit"></i></a>
                          <button onclick="confirm('Reverse this order?') || event.stopImmediatePropagation()" wire:click="reverseOrder({{ $order->id }})" class="btn btn-sm text-danger" title="Reverse">
                            <i class="fa fa-undo-alt"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="11" class="text-center py-5 text-muted">
                        <i class="fas fa-search fa-3x mb-3"></i>
                        <p>No orders found matching your criteria.</p>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="card-footer bg-white">
            <div class="float-right">
              {{ $orders->links() }} {{-- Changed from $funds to $orders --}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>