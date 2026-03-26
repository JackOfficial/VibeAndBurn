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

                {{-- Quick Stats / Filters Bar --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="info-box shadow-sm border-0 clickable-card {{ $status === null ? 'bg-primary text-white' : 'bg-white' }}" wire:click="$set('status', null)" style="cursor: pointer;">
                            <span class="info-box-icon"><i class="fas fa-list"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">All Tickets</span>
                                <span class="info-box-number">Total</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box shadow-sm border-0 clickable-card {{ $status === '0' ? 'bg-warning text-dark' : 'bg-white' }}" wire:click="$set('status', '0')" style="cursor: pointer;">
                            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pending</span>
                                <span class="info-box-number">Action Required</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box shadow-sm border-0 clickable-card {{ $status === '1' ? 'bg-info text-white' : 'bg-white' }}" wire:click="$set('status', '1')" style="cursor: pointer;">
                            <span class="info-box-icon"><i class="fas fa-reply"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Replied</span>
                                <span class="info-box-number">Awaiting User</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box shadow-sm border-0 clickable-card {{ $status === '2' ? 'bg-success text-white' : 'bg-white' }}" wire:click="$set('status', '2')" style="cursor: pointer;">
                            <span class="info-box-icon"><i class="fas fa-check-double"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Closed</span>
                                <span class="info-box-number">Resolved</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-outline card-primary shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-ticket-alt mr-2 text-primary"></i> 
                                Ticket Management
                                @if(count($this->checkbox) > 0)
                                    <span class="badge badge-pill badge-primary ml-2 animate__animated animate__pulse animate__infinite">
                                        {{ count($this->checkbox) }} selected
                                    </span>
                                @endif
                            </h3>

                            <div class="card-tools d-flex">
                                <div class="input-group input-group-sm" style="width: 300px;">
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="search" 
                                           class="form-control border-right-0 shadow-none" 
                                           placeholder="Search ID, email, or subject...">
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
                        <div class="mailbox-controls d-flex align-items-center p-3 border-bottom bg-light-soft">
                            <div class="btn-group mr-2">
                                <button type="button" wire:click="selectAllMessages" class="btn btn-white btn-sm shadow-sm border" title="Select All">
                                    <i class="{{ count($this->checkbox) > 0 ? 'fas fa-check-square text-primary' : 'far fa-square' }}"></i>
                                </button>
                                <button type="button" 
                                        wire:click="deleteMultipleMessages" 
                                        wire:confirm="Permanentely delete these tickets?"
                                        class="btn btn-white btn-sm shadow-sm border {{ empty($this->checkbox) ? 'disabled text-muted' : '' }}" 
                                        title="Delete Selected">
                                    <i class="far fa-trash-alt text-danger"></i>
                                </button>
                            </div>

                            <button type="button" wire:click="$refresh" class="btn btn-white btn-sm shadow-sm border mr-2" title="Refresh">
                                <i class="fas fa-sync-alt" wire:loading.class="fa-spin" wire:target="render"></i>
                            </button>

                            <div wire:loading wire:target="search, status, deleteMultipleMessages" class="text-primary small font-weight-bold">
                                <span class="spinner-grow spinner-grow-sm mr-1"></span> Updating...
                            </div>

                            <div class="ml-auto d-flex align-items-center">
                                <span class="text-muted small mr-3 d-none d-md-inline">Showing {{ $tickets->firstItem() }} - {{ $tickets->lastItem() }} of {{ $tickets->total() }}</span>
                                {{ $tickets->links('livewire::bootstrap') }}
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small text-uppercase font-weight-bold">
                                    <tr>
                                        <th style="width: 50px" class="pl-4"></th>
                                        <th style="width: 100px">Ticket ID</th>
                                        <th>User Details</th>
                                        <th>Subject & Preview</th>
                                        <th>Status</th>
                                        <th>Activity</th>
                                        <th class="text-right pr-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tickets as $ticket)
                                    <tr wire:key="ticket-{{ $ticket->id }}" class="{{ $ticket->status == 0 ? 'bg-unseen' : '' }}">
                                        <td class="pl-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" 
                                                       value="{{ $ticket->id }}" 
                                                       id="check-{{ $ticket->id }}"
                                                       class="custom-control-input" 
                                                       wire:model="checkbox">
                                                <label class="custom-control-label" for="check-{{ $ticket->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold">
                                            <a href="/admin/ticket/{{$ticket->id}}" class="text-primary">#{{ $ticket->id }}</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm mr-2 d-none d-md-flex align-items-center justify-content-center bg-light rounded-circle text-primary font-weight-bold" style="width: 32px; height: 32px;">
                                                    {{ strtoupper(substr($ticket->name, 0, 1)) }}
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="font-weight-bold text-dark mb-0">{{ ucfirst($ticket->name) }}</span>
                                                    <small class="text-muted" style="font-size: 11px;">{{ $ticket->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-dark font-weight-600">{{ Str::limit($ticket->subject, 35) }}</span>
                                                <small class="text-muted text-truncate" style="max-width: 200px;">
                                                    {{ $ticket->last_message ?? 'Click to view conversation...' }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($ticket->status == 0)
                                                <span class="badge badge-soft-warning px-3 py-1 rounded-pill"><i class="fas fa-exclamation-circle mr-1"></i> Pending</span>
                                            @elseif($ticket->status == 1)
                                                <span class="badge badge-soft-primary px-3 py-1 rounded-pill"><i class="fas fa-reply mr-1"></i> Replied</span>
                                            @else
                                                <span class="badge badge-soft-success px-3 py-1 rounded-pill"><i class="fas fa-check mr-1"></i> Closed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted small">
                                                <i class="far fa-clock mr-1"></i>{{ $ticket->created_at->diffForHumans() }}
                                            </span>
                                        </td>
                                        <td class="text-right pr-4">
                                            <div class="btn-group shadow-sm">
                                                <a href="/admin/ticket/{{$ticket->id}}" class="btn btn-white btn-sm px-3" title="View Ticket">
                                                    <i class="fas fa-eye text-primary"></i>
                                                </a>
                                                <button class="btn btn-white btn-sm px-3 text-danger" 
                                                        wire:click="delete({{ $ticket->id }})" 
                                                        wire:confirm="Delete this ticket forever?"
                                                        title="Delete Ticket">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 bg-white">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-light mb-3"></i>
                                                <h5 class="text-muted">No tickets found</h5>
                                                <p class="text-soft small">Try adjusting your filters or search terms.</p>
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
        </div>
    </div>
</section>

<style>
    /* Admin UI Overrides */
    .bg-light-soft { background-color: #f9fbfd; }
    .bg-unseen { background-color: rgba(0, 123, 255, 0.04); }
    .font-weight-600 { font-weight: 600; }
    
    /* Professional Soft Badges (Pill style) */
    .badge-soft-warning { background-color: #fff8e1; color: #f57c00; border: 1px solid #ffe082; }
    .badge-soft-primary { background-color: #e3f2fd; color: #1976d2; border: 1px solid #90caf9; }
    .badge-soft-success { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
    
    /* Info Box Hover */
    .clickable-card { transition: all 0.2s; border: 1px solid transparent !important; }
    .clickable-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important; }
    
    /* Table Enhancements */
    .table thead th { border-top: none; }
    .table tbody tr td { vertical-align: middle; padding: 12px 8px; border-bottom: 1px solid #edf2f9; }
    .btn-white { background: #fff; border: 1px solid #dee2e6; color: #495057; }
    .btn-white:hover { background: #f8f9fa; border-color: #c1c9d0; }
</style>