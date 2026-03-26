<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if (Session::has('feedback'))
                <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="icon fas fa-info-circle mr-2"></i>
                    {{ Session::get('feedback') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-ticket-alt mr-2 text-primary"></i> Support Tickets
                                @if(count($this->checkbox) > 0)
                                    <span class="badge badge-pill badge-primary ml-2 animate__animated animate__fadeIn">
                                        {{ count($this->checkbox) }} selected
                                    </span>
                                @endif
                            </h3>

                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 250px;">
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="search" 
                                           class="form-control border-right-0" 
                                           placeholder="Search by ID, name or subject...">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-white border-left-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="mailbox-controls d-flex align-items-center p-3 border-bottom bg-light">
                            <div class="btn-group mr-2">
                                <button type="button" wire:click="selectAllMessages" class="btn btn-white btn-sm shadow-sm" title="Select All">
                                    <i class="{{ count($this->checkbox) > 0 ? 'fas fa-check-square text-primary' : 'far fa-square' }}"></i>
                                </button>
                                <button type="button" 
                                        wire:click="deleteMultipleMessages" 
                                        wire:confirm="Are you sure you want to delete these tickets?"
                                        class="btn btn-white btn-sm shadow-sm {{ empty($this->checkbox) ? 'disabled' : '' }}" 
                                        title="Delete Selected">
                                    <i class="far fa-trash-alt text-danger"></i>
                                </button>
                            </div>

                            <button type="button" wire:click="$refresh" class="btn btn-white btn-sm shadow-sm mr-2" title="Refresh">
                                <i class="fas fa-sync-alt" wire:loading.class="fa-spin" wire:target="render"></i>
                            </button>

                            <div wire:loading wire:target="deleteMultipleMessages, delete" class="text-muted small">
                                <div class="spinner-border spinner-border-sm text-primary mr-2" role="status"></div>
                                Processing...
                            </div>

                            <div class="ml-auto d-flex align-items-center">
                                <span class="text-muted small mr-3">Showing {{ $tickets->count() }} results</span>
                                {{ $tickets->links('vendor.livewire.bootstrap') }}
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 40px"></th>
                                        <th>Ticket</th>
                                        <th>Contact</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tickets as $ticket)
                                    <tr wire:key="ticket-{{ $ticket->id }}">
                                        <td class="pl-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" 
                                                       value="{{ $ticket->id }}" 
                                                       id="check-{{ $ticket->id }}"
                                                       class="custom-control-input" 
                                                       wire:model="checkbox">
                                                <label class="custom-control-label" for="check-{{ $ticket->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold text-primary">
                                            <a href="/admin/ticket/{{$ticket->id}}">#{{ $ticket->id }}</a>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="font-weight-bold text-dark">{{ ucfirst($ticket->name) }}</span>
                                                <small class="text-muted">{{ $ticket->email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 250px;">
                                                {{ Str::limit($ticket->subject, 40) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($ticket->status == 0)
                                                <span class="badge badge-soft-warning px-2 py-1">Pending</span>
                                            @elseif($ticket->status == 1)
                                                <span class="badge badge-soft-primary px-2 py-1">Replied</span>
                                            @else
                                                <span class="badge badge-soft-success px-2 py-1">Closed</span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">
                                            {{ $ticket->created_at->diffForHumans() }}
                                        </td>
                                        <td class="text-right pr-3">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light btn-xs dropdown-toggle" data-toggle="dropdown">
                                                    Manage
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="/admin/ticket/{{$ticket->id}}">
                                                        <i class="fas fa-eye mr-2"></i> View Details
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <button class="dropdown-item text-danger" wire:click="delete({{ $ticket->id }})" wire:confirm="Delete this ticket?">
                                                        <i class="fas fa-trash mr-2"></i> Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="60" class="opacity-50 mb-3" alt="Empty">
                                            <p class="text-muted">No tickets found matching your criteria.</p>
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

<style>
    /* Professional Soft Badges */
    .badge-soft-warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .badge-soft-primary { background-color: #cce5ff; color: #004085; border: 1px solid #b8daff; }
    .badge-soft-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    
    /* Hover effects */
    .table-hover tbody tr:hover { background-color: rgba(0,123,255,0.03); cursor: pointer; }
    .btn-white { background: #fff; border: 1px solid #dee2e6; color: #495057; }
    .btn-white:hover { background: #f8f9fa; color: #212529; }
</style>