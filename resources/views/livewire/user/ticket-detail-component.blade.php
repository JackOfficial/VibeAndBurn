<div class="nk-msg">
    <div class="nk-msg-body show-msg">
        {{-- Header Section --}}
        <div class="nk-msg-head pb-3 border-bottom">
            <div class="nk-msg-head-meta">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div>
                        <div class="d-flex align-items-center mb-1">
                            <a href="javascript:history.back()" class="btn btn-icon btn-trigger mr-n1 d-md-none"><em class="icon ni ni-arrow-left"></em></a>
                            <h4 class="title mb-0 ml-2 ml-md-0">{{ $ticket->subject }}</h4>
                        </div>
                        <ul class="nk-msg-tags">
                            <li><span class="badge badge-outline-light"><em class="icon ni ni-hash"></em> <span>{{ $ticket->id }}</span></span></li>
                            <li><span class="badge badge-dim badge-primary"><em class="icon ni ni-tag"></em> <span>{{ $ticket->category->name ?? 'General' }}</span></span></li>
                            @if($ticket->order_id)
                                <li><span class="badge badge-dim badge-info"><em class="icon ni ni-cart"></em> <span>Order: #{{ $ticket->order_id }}</span></span></li>
                            @endif
                        </ul>
                    </div>
                    <div class="nk-msg-actions text-right">
                        <div class="d-none d-sm-block">
                             <span class="badge badge-dot {{ $ticket->status == 'pending' ? 'badge-warning' : ($ticket->status == 'answered' ? 'badge-success' : 'badge-secondary') }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                        <span class="sub-text mt-1">{{ $ticket->created_at->format('d M, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chat/Reply History --}}
        <div class="nk-msg-reply nk-reply" style="max-height: 550px; overflow-y: auto; background-color: #f5f6fa7a; padding: 20px;">
            @forelse($messages as $msg)
                <div class="nk-reply-item {{ $msg->is_admin ? 'admin-reply' : 'user-reply' }}">
                    <div class="nk-reply-header">
                        <div class="user-card">
                            <div class="user-avatar {{ $msg->is_admin ? 'bg-primary' : 'bg-gray' }} size-sm">
                                <span>{{ strtoupper(substr($msg->user->name ?? ($msg->is_admin ? 'ST' : 'ME'), 0, 2)) }}</span>
                            </div>
                            <div class="user-name">
                                <span class="lead-text">{{ $msg->is_admin ? 'Support Team' : 'You' }}</span>
                                <span class="sub-text">{{ $msg->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="nk-reply-body">
                        <div class="nk-reply-entry entry" 
                             style="{{ $msg->is_admin 
                                ? 'background: #ffffff; border: 1px solid #dbdfea; border-radius: 0 15px 15px 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);' 
                                : 'background: #eef2ff; border-radius: 15px 0 15px 15px; border: 1px solid #c4d1ff;' }} 
                                padding: 12px 16px; margin-left: {{ $msg->is_admin ? '0' : 'auto' }}; max-width: 85%;">
                            <p class="mb-0 text-dark" style="white-space: pre-wrap;">{{ $msg->message }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-5 text-center">
                    <div class="nk-reply-form-field text-soft">
                        <em class="icon ni ni-chat-circle-fill fs-1"></em>
                        <p>No conversation found for this ticket.</p>
                    </div>
                </div>
            @endforelse
            {{-- Anchor for auto-scroll --}}
            <div id="chat-bottom"></div>
        </div>

        {{-- Reply Form Section --}}
        <div class="nk-reply-form border-top bg-white">
            @if($ticket->status !== 'closed')
                <div class="nk-reply-form-header">
                    <ul class="nav nav-tabs-s2 nav-tabs nav-tabs-sm">
                        <li class="nav-item">
                            <span class="nav-link active py-1"><em class="icon ni ni-reply-fill mr-1"></em> Quick Reply</span>
                        </li>
                    </ul>
                </div>
                <div class="nk-reply-form-editor">
                    <div class="nk-reply-form-field px-3 pt-2">
                        <textarea wire:model.defer="newMessage" 
                                  class="form-control form-control-simple no-resize px-0" 
                                  rows="2" 
                                  placeholder="Write your message here..." 
                                  style="font-size: 15px;"></textarea>
                    </div>
                    <div class="nk-reply-form-tools border-top px-3 py-2">
                        <ul class="nk-reply-form-actions g-1">
                            <li>
                                <button wire:click="sendMessage" wire:loading.attr="disabled" class="btn btn-primary btn-round">
                                    <span wire:loading.remove>
                                        <em class="icon ni ni-send-alt"></em> <span>Send Message</span>
                                    </span>
                                    <span wire:loading>
                                        <em class="icon ni ni-loader spin"></em> <span>Sending...</span>
                                    </span>
                                </button>
                            </li>
                        </ul>
                        @error('newMessage') <span class="text-danger small ml-2">{{ $message }}</span> @enderror
                    </div>
                </div>
            @else
                <div class="p-4 bg-light text-center rounded-bottom">
                    <div class="alert alert-fill alert-light border-0 shadow-none mb-0">
                        <em class="icon ni ni-lock-alt-fill text-soft fs-4"></em>
                        <h6 class="mt-2 text-soft">Ticket Conversation Locked</h6>
                        <p class="sub-text">This ticket has been marked as closed. If you still need help, please open a new ticket.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>