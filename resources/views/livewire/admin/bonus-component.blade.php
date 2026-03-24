<div>
     @if (Session::has('bonusSuccess'))
            <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-check"></i></strong> {{ Session::get('bonusSuccess') }} </div>
          @elseif(Session::has('bonusFail'))
          <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('bonusFail') }} </div> 
            @endif

            <div class="card">
              <div class="card-header">
                  <div class="d-flex justify-content-between">
                    <h3 class="card-title">{{ $bonusesCounter }} Entries &nbsp; <span class="bg-danger text-white p-1 px-2 rounded ml-2"> ${{ number_format($bonusesTotal) }} Bonus</span></h3>
                    <select wire:model="filter">
                        <option value="default">Default</option>
                        <option value="pending">Pending</option>
                        <option value="cleamed">Claimed</option>
                        <option value="offered">Offered</option>
                    </select>
                  </div>
                
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <div class="table-responsive">
                <table class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Referral</th>
                    <th>Email</th>
                    <th>Bonus</th>
                    <th>Status</th>
                    <th>Created_at</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                 
                   @foreach ($sharedlinks as $sharedlink)
                   <tr> 
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sharedlink->name }}</td>
                    <td>{{ $sharedlink->email }}</td>
                    <td>{{ $sharedlink->bonus}}</td>
                    <td>
                        @if($sharedlink->status == 0)
                        <span class="text-defaut">Pending</span>
                        @elseif($sharedlink->status == 1)
                        <span class="text-warning">Cleamed</span>
                        @elseif($sharedlink->status == 2)
                        <span class="text-success">Offered</span>
                        @else
                         <span class="text-primary">Pending</span>
                        @endif
                        </td>
                    <td>{{ $sharedlink->created_at }}</td>
                    <td>
                    <button wire:loading.attr="disabed" onclick="confirm('Do you want to offer this bonus?')" wire:click.prevent="offer({{ $sharedlink->id }}, {{ $sharedlink->user_id }}, {{ $sharedlink->bonus }})" class="btn btn-xs btn-primary {{ $sharedlink->bonus == 0 ? 'd-none' : '' }}">
                        Offer 
                        <div wire:loading wire:loading.target="offer({{ $sharedlink->id }}, {{ $sharedlink->user_id }}, {{ $sharedlink->bonus }})" class="spinner-border spinner-border-xs"></div></button>
                     <a role="button" data-toggle="modal" data-target="#bonus-details" wire:click.prevent="seeDetails({{ $sharedlink->user_id }})" class="btn btn-success btn-xs">Details</a>   
                    </td>
                   </tr>   
                   @endforeach 
                  
              
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Referral</th>
                    <th>Email</th>
                    <th>Bonus</th>
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
            
            <div wire:ignore.self class="modal fade" id="bonus-details">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                              <div class="modal-content">
                              
                                <!-- Modal Header -->
                                <div class="modal-header">
                                  <h4 class="modal-title">Bonus Details</h4>
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div wire:loading class="spinner-border spinner-border-lg my-auto mx-auto"></div>
                                <div wire:loading class="text-center mt-2 mb-5"> Loading...</div>
                                <div wire:loading.remove class="modal-body {{ count($bonuses) > 0 ? 'd-block': 'd-none' }}">
                                <div wire:loading.remove class="mb-1 font-weight-bold">{{ $username }} have shared {{ count($bonuses) }} {{ count($bonuses) > 1 ? 'people' : 'person' }}</div>
                                 <div class="table-responsive">
                <table wire:loading.remove class="table table-bordered table-striped table-sm">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Bonus</th>
                    <th>Created_at</th>
                  </tr>
                  </thead>
                  <tbody>
                    @forelse($bonuses as $bonus)  
                    <tr> 
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $bonus->name ?? '' }}</td>
                    <td>{{ $bonus->email ?? '' }}</td>
                    <td>{{ $bonus->amount ?? '' }}</td>
                    <td>{{ $bonus->created_at->diffForHumans() ?? '' }}</td>
                   </tr>
                   @empty
                   <tr> 
                    <td colspan="5" class="text-center">No bonus found!</td>
                   </tr>
                   @endforelse
                  
              
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Bonus</th>
                    <th>Created_at</th>
                  </tr>
                  </tfoot>
                </table>
                </div>  
                                </div>
                                 <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>
                             
                                </div>
                                
                              </div>
                            </div>
</div>
