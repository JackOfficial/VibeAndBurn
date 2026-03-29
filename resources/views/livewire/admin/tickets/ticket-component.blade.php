<div wire:poll.10s> {{-- Increased to 10s to reduce server load, adjust as needed --}}
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                {{-- Left Side: Ticket Conversation --}}
                <div class="col-lg-8">
                    <div class="card card-outline card-primary shadow-sm border-0">
                        <div class="card-header bg-white d-flex align-items-center justify-content-between">
                            <h3 class="card-title font-weight-bold mb-0">
                                <a href="{{ route('admin.tickets') }}" class="btn btn-sm btn-icon btn-light mr-2">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                Ticket #{{ $ticket->id }}: <span class="text-muted small font-weight-normal">{{ $ticket->subject }}</span>
                            </h3>
                            <div class="card-tools">
                                @if($ticket->status != 2)
                                    @role('Super Admin|Admin')
                                        <button wire:click="closeTicket" wire:loading.attr="disabled" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-check-circle mr-1"></i> Mark as Resolved
                                        </button>
                                    @endrole
                                @endif
                            </div>
                        </div>

                        {{-- Chat History --}}
                        <div class="card-body p-0">
                            <div class="nk-reply-history px-4 py-3" style="max-height: 600px; overflow-y: auto; background: #f8f9fc;">
                                @foreach($messages as $msg)
                                    <div class="mb-4 d-flex {{ $msg->is_admin ? 'justify-content-end' : 'justify-content-start' }}" wire:key="msg-{{ $msg->id }}">
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

                        {{-- Reply Form --}}
                        <div class="card-footer bg-white border-top">
                            @if($ticket->status != 2)
                                <div class="form-group">
                                    <label class="small font-weight-bold text-uppercase text-muted">Your Response</label>
                                    <textarea wire:model="newMessage" class="form-control border-light shadow-none" rows="4" placeholder="Type your reply here..."></textarea>
                                    @error('newMessage') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        <i class="fas fa-info-circle mr-1"></i> User will be notified via email.
                                    </div>
                                    <button wire:click="sendMessage" wire:loading.attr="disabled" class="btn btn-primary px-4">
                                        <span wire:loading.remove wire:target="sendMessage"><i class="fas fa-paper-plane mr-2"></i> Send Reply</span>
                                        <span wire:loading wire:target="sendMessage"><i class="fas fa-spinner fa-spin mr-2"></i> Sending...</span>
                                    </button>
                                </div>
                            @else
                                <div class="alert alert-light border text-center mb-0">
                                    <i class="fas fa-lock mr-2 text-muted"></i> This ticket is closed.
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
                            <div class="mb-3 d-flex justify-content-center">
                                <div class="avatar-lg bg-soft-primary text-primary d-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px; font-size: 24px; font-weight: bold;">
                                    {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                                </div>
                            </div>
                            <h5 class="mb-0 font-weight-bold">{{ $ticket->user->name }}</h5>
                            <p class="text-muted small mb-3">{{ $ticket->user->email }}</p>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.users.show', $ticket->user_id) }}" class="btn btn-sm btn-outline-primary btn-block">View User Profile</a>
                                <button onclick="confirm('Block user?') || event.stopImmediatePropagation()" wire:click="blockEmail('{{ $ticket->user->email }}')" class="btn btn-sm btn-outline-danger btn-block mt-2">Block This User</button>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mt-3">
                        <div class="card-header bg-white font-weight-bold">Ticket Information</div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center small border-0">
                                    <span class="text-muted">Status</span>
                                    @php
                                        $badgeClass = match($ticket->status) {
                                            0 => 'badge-warning',
                                            1 => 'badge-primary',
                                            default => 'badge-success'
                                        };
                                        $statusText = match($ticket->status) {
                                            0 => 'Pending',
                                            1 => 'Replied',
                                            default => 'Closed'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center small border-0">
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
        .rounded-left-lg { border-bottom-right-radius: 15px !important; border-top-right-radius: 5px !important; }
        .rounded-right-lg { border-bottom-left-radius: 15px !important; border-top-left-radius: 5px !important; }
        .bg-soft-primary { background-color: #e3f2fd; }
        .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; padding: 0; }
        .nk-reply-history::-webkit-scrollbar { width: 5px; }
        .nk-reply-history::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 10px; }
    </style>
</div>