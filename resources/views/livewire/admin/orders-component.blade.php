<section class="content">
      <div class="container-fluid">
      <div class="row">
          <div class="col-12">

            @if (Session::has('approveOrderSuccess'))
            <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-check"></i></strong> {{ Session::get('approveOrderSuccess') }} </div>
          @elseif(Session::has('approveOrderFail'))
          <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('approveOrderFail') }} </div> 
            @endif
            
            @if (Session::has('breathDetails'))
            <div class="alert alert-dark alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-info"></i></strong> {{ Session::get('breathDetails') }} </div>
          @endif

            <div class="card">
              <div class="card-header">
                  <div class="d-flex flex-lg-row flex-md-row flex-sm-column justify-content-lg-between justify-content-md-between justify-content-sm-start">
                    <div class="row">
                        <div class="col-md-6">
                           <select wire:model="filterStatus" class="form-control form-control-sm">
                     <option value="">All</option>
                     <option value="0">Pending</option>
                     <option value="1">Completed</option>
                     <option value="2">Reversed</option>
                     <option value="3">Processing</option>
                     <option value="4">In Progress</option>
                     <option value="5">Partial</option>
                    </select>
                    <div class="float-left" wire:loading wire:loading.target="filterStatus">Filtering...</div>
                        </div>
                        <div class="col-md-6">
                            {{ $ordersCounter }} Orders 
                        </div>
                    </div>
                <div>
                    <div class="input-group">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class="fa fa-search"></i></span>
    </div>
    <input type="text" wire:model="keyword" class="form-control form-control-sm" placeholder="Search ...">
  </div>
                    </div>
                  </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                  <thead>
                  <tr>
                      <th>#</th>
                    <th>ID</th>
                     <th>Order ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Socialmedia</th>
                    <th>Category</th>
                    <th>Service</th>
                    <th>Rate Per 1000</th>
                    <th>link</th>
                    <th>Comments</th>
                    <th>quantity</th>
                    <th>charge</th>
                    <th>Start Count</th>
                    <th>Remains</th>
                    <th>Status</th>
                    <th>Created_at</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                 
                   @forelse ($orders as $order)
                   <tr> 
                   <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->id }}
                    <div class="d-flex">
                      <select wire:model="status"  class="form-control form-control-sm">
                     <option value="">Make this</option>
                     <option value="0">Pending</option>
                     <option value="1">Completed</option>
                     <option value="2">Reversed</option>
                     <option value="3">Processing</option>
                     <option value="4">In Progress</option>
                     <option value="5">Partial</option>
                     </select>  
                     <button class="btn btn-outline-primary btn-sm" wire:click="changeStatus({{$order->id}})">Ok</button>
                    </div>
                     </td>
                     <td>{{ $order->orderId }} 
                      <div>
                            @if($order->source_id == 2)
                        <span class="badge" style="background-color: #7946E9;"><a href="https://bulkfollows.org" target="_blank" style="color:white">{{ $order->api_source }}</a></span>
                        @elseif($order->source_id == 3)
                        <span class="badge" style="background-color: #3AE3A4;"><a href="https://amazingsmm.com" target="_blank" style="color:white">{{ $order->api_source }}</a></span>
                        @elseif($order->source_id == 4)
                        <span class="badge badge-primary"><a href="https://bulkmedya.org/" target="_blank" style="color:white">{{ $order->api_source }}</a></span>
                        @else
                        <span class="badge badge-danger">{{ $order->api_source }}</span>
                        @endif
                        </div>
                     </td>
                    <td>
                        <a role="button" wire:click="userWalletDetails({{ $order->user_id }})">
                        @if($order->status == 0)
                     <span class="text-danger"> {{ $order->name }} </span>
                    @elseif($order->status == 1)
                    <span class="text-success"> {{ $order->name }} </span>
                    @elseif($order->status == 2)
                    <span class="text-warning"> {{ $order->name }} </span>
                     @elseif($order->status == 3)
                    <span class="text-info"> {{ $order->name }} </span>
                      @elseif($order->status == 4)
                    <span class="text-primary"> {{ $order->name }} </span>
                    @else
                    <span class="text-secondary"> {{ $order->name }} </span>
                  @endif
                  </a>
                  <div wire:loading wire:loading.target="userWalletDetails({{ $order->user_id }})">Computing...</div>
                  </td>
                    <td>{{ $order->email }}</td>
                    <td>{{ $order->socialmedia }}</td>
                    <td>{{ $order->category }}</td>
                    <td>{{ $order->service }} <b>({{ $order->serviceId }})</b></td>
                    <td>{{ $order->rate_per_1000 }}</td>
                    <td><a href="{{ $order->link }}" target="__blank">{{ $order->link }}</a></td>
                    <td>{{ Str::limit($order->comment, 12) }}</td>
                    <td>{{ number_format($order->quantity) }}</td>
                    <td>{{ $order->charge }}</td>
                     <td>{{ $order->start_count }}</td>
                    <td>{{ $order->remains }}</td>
                    <td>
                        @if($order->status == 0)
               <span class="bg-danger text-white p-1 rounded">Pending</span>
                      @elseif($order->status == 1)
              <span class="bg-success text-white p-1 rounded"><i class="fa fa-check"></i>Completed</span>
               @elseif($order->status == 2)
              <span class="bg-warning text-white p-1 rounded">Reversed</span>
               @elseif($order->status == 3)
              <span class="bg-info text-white p-1 rounded">Processing</span>
                   @elseif($order->status == 4)
             <span class="bg-primary text-white p-1 rounded"> In Proggress</span>
             @else
         <span class="bg-secondary text-white p-1 rounded">Partial</span>
                @endif</td>
                    <td>{{ $order->created_at }}</td>
                    <td>
                              <a href="{{ route('approve', $order->id) }}" class="btn text-primary borderless"><i class="fa fa-check"></i></a> &nbsp;
                              <a onclick="return confirm('Are you sure you want to reverse?')" href="{{ route('order.show', $order->id) }}" class="btn text-danger borderless"><i class="fa fa-times"></i></a>
                        <a href="{{ route('admin.clientOrders.edit', $order->id) }}" class="btn text-success borderless"><i class="fa fa-edit"></i></a> &nbsp;
                       </td>
                  </tr>  
                  @empty
                  <tr>
                      <td colspan="18" class="text-center"> NO RECORD FOUND! <div class="spinner-grow spinner-grow-sm"></div></td>
                  </tr>
                   @endforelse 
                  
              
                  </tbody>
                  <tfoot>
                  <tr>
                        <th>#</th>
                    <th>ID</th>
                     <th>Order ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Socialmedia</th>
                    <th>Category</th>
                    <th>Service</th>
                    <th>Rate Per 1000</th>
                    <th>link</th>
                    <th>Comments</th>
                    <th>quantity</th>
                    <th>charge</th>
                    <th>Start Count</th>
                    <th>Remains</th>
                    <th>Status</th>
                    <th>Created_at</th>
                    <th>Actions</th>
                  </tr>
                  </tfoot>
                </table>
            </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        
        {!! $orders->links() !!}
        
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>