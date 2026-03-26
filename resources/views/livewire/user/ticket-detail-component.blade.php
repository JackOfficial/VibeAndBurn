<div class="nk-msg">
    <div class="nk-msg-body show-msg">
        <div class="nk-msg-head">
            <div class="nk-msg-head-meta">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div>
                        <h4 class="title mb-1">{{ $ticket->subject }}</h4>
                        <ul class="nk-msg-tags">
                            <li><span class="label-tag"><em class="icon ni ni-hash"></em> <span>{{ $ticket->id }}</span></span></li>
                            <li><span class="label-tag"><em class="icon ni ni-tag"></em> <span>{{ $ticket->category->name ?? 'General' }}</span></span></li>
                            @if($ticket->order_id)
                                <li><span class="label-tag bg-primary-dim text-primary"><em class="icon ni ni-cart"></em> <span>Order: #{{ $ticket->order_id }}</span></span></li>
                            @endif
                        </ul>
                    </div>
                    <div class="nk-msg-actions">
                        <span class="badge badge-dim {{ $ticket->status == 'pending' ? 'badge-warning' : ($ticket->status == 'answered' ? 'badge-success' : 'badge-secondary') }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div><div class="nk-msg-reply nk-reply" style="max-height: 550px; overflow-y: auto; background-color: #fcfdfe;">
            @forelse($messages as $msg)
                <div class="nk-reply-item">
                    <div class="nk-reply-header">
                        <div class="user-card">
                            <div class="user-avatar {{ $msg->is_admin ? 'bg-primary' : 'bg-info' }}">
                                <span>{{ strtoupper(substr($msg->user->name ?? ($msg->is_admin ? 'S' : 'U'), 0, 2)) }}</span>
                            </div>
                            <div class="user-name">
                                <span class="lead-text">{{ $msg->is_admin ? 'Support Team' : 'You' }}</span>
                                <span class="sub-text">{{ $msg->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="nk-reply-body">
                        <div class="nk-reply-entry entry {{ $msg->is_admin ? 'admin-reply' : '' }}" 
                             style="{{ $msg->is_admin ? 'background: #f5f6fa; border-radius: 12px; padding: 15px;' : 'background: #eef2ff; border-radius: 12px; padding: 15px;' }}">
                            <p>{!! nl2br(e($msg->message)) !!}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-5 text-center">
                    <em class="icon ni ni-chat-circle-fill fs-1 text-soft"></em>
                    <p class="text-soft">No messages in this conversation yet.</p>
                </div>
            @endforelse
        </div><div class="nk-reply-form">
            @if($ticket->status !== 'closed')
                <div class="nk-reply-form-header">
                    <ul class="nav nav-tabs-s2 nav-tabs nav-tabs-sm">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:void(0);">Reply Message</a>
                        </li>
                    </ul>
                </div>
                <div class="nk-reply-form-editor">
                    <div class="nk-reply-form-field">
                        <textarea wire:model.defer="newMessage" class="form-control form-control-simple no-resize" placeholder="Reply to the support team..."></textarea>
                    </div>
                    <div class="nk-reply-form-tools">
                        <ul class="nk-reply-form-actions g-1">
                            <li class="mr-2">
                                <button wire:click="sendMessage" wire:loading.attr="disabled" class="btn btn-primary" type="submit">
                                    <span wire:loading.remove>
                                        <em class="icon ni ni-send-alt"></em> <span>Send Reply</span>
                                    </span>
                                    <span wire:loading>
                                        <em class="icon ni ni-loader spin"></em> <span>Sending...</span>
                                    </span>
                                </button>
                            </li>
                        </ul>
                        @error('newMessage') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
            @else
                <div class="alert alert-fill alert-light border">
                    <em class="icon ni ni-info-fill"></em>
                    <span>This ticket has been <strong>closed</strong>. You can no longer reply to this conversation.</span>
                </div>
            @endif
        </div></div></div>