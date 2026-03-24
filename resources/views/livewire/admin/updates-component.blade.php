<section class="content">
      <div class="container-fluid">
      <div class="row">
          <div class="col-12">

            @if (Session::has('updateSuccess'))
            <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-check"></i></strong> {{ Session::get('updateSuccess') }} </div>
          @elseif(Session::has('updateFail'))
          <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('updateFail') }} </div> 
            @endif

            <div class="card">
              <div class="card-header d-flex justify-content-between">
              <div> {{ count($myupdates) }} {{ (count($myupdates)<1) ? 'Update' : 'Updates' }}</div>
               <div><a href="{{ route('update.create') }}"><i class="fa fa-plus"></i> Add Update</a></div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Update</th>
                    <th>Status</th>
                    <th>Created_at</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                 
                   @forelse ($myupdates as $myupdate)
                   <tr> 
                   <td>{{ $loop->iteration }}</td>
                    <td>{{ $myupdate->vibeUpdate }} </td>
                    <td>
                        @if($myupdate->status == 0)
               <span class="badge badge-danger">Inactive</span>
                      @else
              <span class="badge badge-success">Active</span>
                @endif
                </td>
                    <td>{{ $myupdate->created_at }}</td>
                    <td>
                        <form method="POST" action="{{ route('update.destroy', $myupdate->id) }}">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button>
                        </form>
                        <a href="{{ route('update.edit', $myupdate->id) }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> Edit</a>
                    </td>
                  </tr>  
                  @empty
                  <tr>
                      <td colspan="17" class="text-center"> NO UPDATE FOUND! </td>
                  </tr>
                   @endforelse 
                  
              
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Update</th>
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
        
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>