<div wire:poll.5s> {{-- Critical: The Single Root Wrapper --}}
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                {{-- Left Side: Ticket Conversation --}}
                <div class="col-lg-8">
                    <div class="card card-outline card-primary shadow-sm border-0">
                        <div class="card-header bg-white d-flex align-items-center justify-content-between">
                            <h3 class="card-title font-weight-bold">
                                <a href="{{ route('admin.tickets') }}" class="btn btn-sm btn-icon btn-light mr-2">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                Ticket #{{ $ticket->id }}: <span class="text-muted">{{ $ticket->subject }}</span>
                            </h3>
                            <div class="card-tools">
                                @if($ticket->status != 2)
                                @role('Super Admin|Admin')
                                    <button wire:click="closeTicket" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-check-circle mr-1"></i> Mark as Resolved
                                    </button>
                                    @endrole
                                @endif
                            </div>
                        </div>

                        {{-- Chat History --}}
                        @can('manage tickets')
                        <div class="card-body p-0">
                            <div class="nk-reply-history px-4 py-3" style="max-height: 600px; overflow-y: auto; background: #f8f9fc;">
                                @foreach($messages as $msg)
                                    <div class="mb-4 d-flex {{ $msg->is_admin ? 'justify-content-end' : 'justify-content-start' }}">
                                        <div class="d-flex flex-column {{ $msg->is_admin ? 'align-items-end' : 'align-items-start' }}" style="max-width: 80%;">
                                            <div class="d-flex align-items-center mb-1">
                                                @if(!$msg->is_admin)
                                                    <span class="badge badge-light mr-2">{{ $ticket->user->name }}</span>
                                                @endif
                                                <small class="text-muted">{{ $msg->created_at->diffForHumans() }}</small>
                                                @if($msg->is_admin)
                                                    <span class="badge badge-primary ml-2">Support (You)</span>
                                                @endif
                                            </div>
                                            
                                            <div class="p-3 shadow-sm {{ $msg->is_admin ? 'bg-primary text-white rounded-left-lg' : 'bg-white border rounded-right-lg' }}" 
                                                 style="border-radius: 15px; font-size: 14px; line-height: 1.6;">
                                                {!! nl2br(e($msg->message)) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @can('manage tickets')

                        {{-- Reply Form --}}
                        <div class="card-footer bg-white border-top-0">
                            @if($ticket->status != 2)
                                <div class="form-group">
                                    <label class="small font-weight-bold text-uppercase text-muted">Your Response</label>
                                    <textarea wire:model.defer="newMessage" class="form-control border-light shadow-none" rows="4" placeholder="Type your reply here..."></textarea>
                                    @error('newMessage') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        <i class="fas fa-info-circle mr-1"></i> User will be notified via email.
                                    </div>
                                    <button wire:click="sendMessage" class="btn btn-primary px-4">
                                        <span><i class="fas fa-paper-plane mr-2"></i> Send Reply</span>
                                    </button>
                                </div>
                            @else
                                <div class="alert alert-light border text-center mb-0">
                                    <i class="fas fa-lock mr-2 text-muted"></i> This ticket is closed. Re-open it to send a message.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Side: User & Ticket Meta --}}
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white font-weight-bold">Customer Details</div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <div class="avatar-lg bg-soft-primary text-primary mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px; font-size: 24px;">
                                    {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                                </div>
                            </div>
                            <h5 class="mb-0">{{ $ticket->user->name }}</h5>
                            <p class="text-muted small mb-3">{{ $ticket->user->email }}</p>
                            
                            <div class="d-grid gap-2">
                                <a href="/admin/users/{{ $ticket->user_id }}" class="btn btn-xs btn-outline-primary btn-block">View User Profile</a>
                                <button wire:click="blockEmail('{{ $ticket->user->email }}')" class="btn btn-xs btn-outline-danger btn-block mt-2">Block This User</button>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mt-3">
                        <div class="card-header bg-white font-weight-bold">Ticket Information</div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center small">
                                    <span class="text-muted">Status</span>
                                    @if($ticket->status == 0)
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($ticket->status == 1)
                                        <span class="badge badge-primary">Replied</span>
                                    @else
                                        <span class="badge badge-success">Closed</span>
                                    @endif
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center small">
                                    <span class="text-muted">Last Activity</span>
                                    <span>{{ $ticket->updated_at->diffForHumans() }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .rounded-left-lg { border-top-right-radius: 0 !important; }
        .rounded-right-lg { border-top-left-radius: 0 !important; }
        .bg-soft-primary { background-color: #e3f2fd; }
        .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; padding: 0; }
    </style>
</div>