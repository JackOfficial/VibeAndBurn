<div>
    @if (Session::has('deleteWalletSuccess'))
            <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-check"></i></strong> {{ Session::get('deleteWalletSuccess') }} </div>
          @elseif(Session::has('deleteWalletFail'))
          <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('deleteWalletFail') }} </div> 
            @endif
            
            @if (isset($feedback))
             <div class="alert alert-success alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
            {{ $feedback }} </div>
            @endif

            <a data-toggle="modal" data-target="#addWholesaler" class="btn btn-danger btn-sm rounded shadow mb-1"><i class="fas fa-plus"></i> Add a Wholesaler</a>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ $wholesalersCounter }} {{ ($wholesalersCounter > 1) ? 'Sources' : 'Source' }} </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Created_at</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                 
                   @forelse ($wholesalers as $wholesaler)
                   <tr> 
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $wholesaler->wholesaler }}</td>
                     <td>{{ $wholesaler->status ? 'Active' : 'Disabled' }}</td>
                    <td>{{ $wholesaler->created_at }}</td>
                    <td>
                        <a class="btn btn-success btn-sm" wire:click.prevent="edit({{ $wholesaler->id }})" data-toggle="modal" data-target="#editWallet"><i class="fa fa-edit"></i> Edit</a>
                            <button class="btn btn-danger btn-sm" wire:click="removeSource({{ $wholesaler->id }})"><i class="fas fa-trash"></i> Delete</button>
                        </td>
                  </tr>   
                  @empty
                   <tr> 
                    <td colspan="5" class="py-2 text-center">No wholesaler available at the moment</td>
                  </tr> 
                   @endforelse
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Created_at</th>
                    <th>Actions</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

<!-- The Modal -->
<div wire:ignore.self class="modal fade" id="addWholesaler">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-edit"></i> Add a Wholesaler</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
              <div class="form-group">
                <label for="wholesaler">Wholesaler</label>
                <input type="text" wire:model="wholesaler" class="form-control" id="wholesaler" required />
              </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
           <button type="button" class="btn btn-primary btn-sm" wire:click="add"><i class="fa fa-plus"></i> Add Source &nbsp; <div wire:loading wire:target="add" class="spinner-border spinner-border-sm"></div></button>
       </div>
    </div>
  </div>
</div>
</div>
