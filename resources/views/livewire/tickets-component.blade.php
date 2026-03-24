<section class="content">
      <div class="row"> 
        <!-- /.col -->
        <div class="col-md-12">
             @if (Session::has('feedback')) 
    <div class="alert alert-danger alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
  {{ Session::get('feedback') }} 
</div> 
    @endif
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Tickets <span class="{{ empty($this->checkbox) ? 'd-none' : '' }}">{{ count($this->checkbox) }}</span></h3>

              <div class="card-tools">
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control" wire:model="search" placeholder="Search Mail">
                  <div class="input-group-append">
                    <div class="btn btn-primary">
                      <i class="fas fa-search"></i>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" wire:click="selectAllMessages" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                  <button type="button" wire:click="deleteMultipleMessages" class="btn btn-default btn-sm">
                    <i class="far fa-trash-alt"></i>
                  </button>
                  <button type="button" class="btn btn-default btn-sm">
                    <i class="fas fa-reply"></i>
                  </button>
                  <button type="button" class="btn btn-default btn-sm">
                    <i class="fas fa-share"></i>
                  </button>
                </div>
                
                <!-- /.btn-group -->
                <button type="button" wire:click="render" class="btn btn-default btn-sm">
                  <i class="fas fa-sync-alt"></i>
                </button>
                
                 <span class="mr-2" wire:loading wire:target="deleteMultipleMessages">Deleting message ...</span>
                 
                <div class="float-right">
                  1-50/200
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm">
                      <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="button" class="btn btn-default btn-sm">
                      <i class="fas fa-chevron-right"></i>
                    </button>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.float-right -->
              </div>
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                  <tbody>
                 
                   @forelse($tickets as $ticket)
                  <tr>
                    <td>
                      <div class="form-check">
                        <input type="checkbox" value="{{ $ticket->id }}" class="form-check-input" wire:model="checkbox">
                        <label class="form-check-label"></label>
                      </div>
                    </td>
                    <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
                    <td class="mailbox-name"><a href="/admin/ticket/{{$ticket->id}}">#{{ $ticket->id }} - {{ucfirst($ticket->name)}}</a></td>
                    <td class="mailbox-email">{{ $ticket->email }}</td>
                    <td class="mailbox-subject">{{Str::limit($ticket->subject, 35, '...')}}
                    </td>
                    <td class="">
                        @if($ticket->status == 0)
                        <span class="badge badge-warning">Pending</span>
                        @elseif($ticket->status == 1)
                        <span class="badge badge-primary">Repried</span>
                        @else
                        <span class="badge badge-success">Closed</span>
                        @endif
                        </td>
                    
                    <td class="mailbox-date">{{ $ticket->created_at->diffForHumans() }}</td>
                    <td>
                        <div class="dropdown dropleft">
  <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
    Option
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="#" wire:click="delete({{ $ticket->id }})">Delete</a>
    <a class="dropdown-item" href="#" wire:click="blockEmail({{ $ticket->email }})">Block</a>
  </div>
</div>
                    </td>
                  </tr>
                  @empty
                   <tr>
                       <td colspan="7" class="text-center py-2"> No message found</td>
                   </tr>
                  @endforelse
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer p-0">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" wire:click="selectAllMessages" class="btn btn-default btn-sm checkbox-toggle">
                  <i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                  <button type="button" wire:click="deleteMultipleMessages" class="btn btn-default btn-sm">
                    <i class="far fa-trash-alt"></i>
                  </button>
                  <button type="button" class="btn btn-default btn-sm">
                    <i class="fas fa-reply"></i>
                  </button>
                  <button type="button" class="btn btn-default btn-sm">
                    <i class="fas fa-share"></i>
                  </button>
                </div>
                <!-- /.btn-group -->
                <button type="button" wire:click="render" class="btn btn-default btn-sm">
                  <i class="fas fa-sync-alt"></i>
                </button>
                <div class="float-right">
                  1-50/200
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm">
                      <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="button" class="btn btn-default btn-sm">
                      <i class="fas fa-chevron-right"></i>
                    </button>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.float-right -->
              </div>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
