<div class="nk-msg">
    <div class="nk-msg-body show-msg">
        {{-- Header Section: Clean & Informative --}}
        <div class="nk-msg-head pb-3 border-bottom bg-white">
            <div class="nk-msg-head-meta">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div>
                        <div class="d-flex align-items-center mb-1">
                            <a href="javascript:history.back()" class="btn btn-icon btn-trigger mr-2 d-md-none">
                                <em class="icon ni ni-arrow-left"></em>
                            </a>
                            <h4 class="title mb-0">{{ $ticket->subject }}</h4>
                        </div>
                        <ul class="nk-msg-tags g-2">
                            <li><span class="badge badge-outline-light">#{{ $ticket->id }}</span></li>
                            <li><span class="badge badge-dim badge-primary">{{ $ticket->category->name ?? 'General' }}</span></li>
                            @if($ticket->order_id)
                                <li><span class="badge badge-dim badge-info">Order: #{{ $ticket->order_id }}</span></li>
                            @endif
                        </ul>
                    </div>
                    <div class="nk-msg-actions text-right">
                        <div class="badge badge-dot {{ $ticket->status == 'pending' ? 'badge-warning' : ($ticket->status == 'answered' ? 'badge-success' : 'badge-secondary') }}">
                            {{ ucfirst($ticket->status) }}
                        </div>
                        <div class="sub-text mt-1 small text-soft">{{ $ticket->created_at->format('d M, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chat History: WhatsApp/Telegram Style Bubbles --}}
        <div class="nk-msg-reply nk-reply" style="height: 550px; overflow-y: auto; background-color: #f9f9fb; padding: 30px 20px;">
            @forelse($messages as $msg)
                <div class="d-flex flex-column mb-4 {{ $msg->is_admin ? 'align-items-start' : 'align-items-end' }}">
                    {{-- Message Bubble --}}
                    <div class="p-3 shadow-sm bubble-msg" 
                         style="max-width: 80%; font-size: 14px; line-height: 1.6;
                                {{ $msg->is_admin 
                                    ? 'background: #ffffff; color: #364a63; border: 1px solid #e5e9f2; border-radius: 0 18px 18px 18px;' 
                                    : 'background: #6576ff; color: #ffffff; border-radius: 18px 18px 0 18px;' }}">
                        
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $msg->message }}</p>
                    </div>

                    {{-- Metadata under the bubble --}}
                    <div class="mt-1 px-1">
                        <small class="text-muted" style="font-size: 11px;">
                            <em class="icon ni {{ $msg->is_admin ? 'ni-shield-check-fill text-primary' : 'ni-user-fill' }}"></em>
                            {{ $msg->is_admin ? 'Support Team' : 'You' }} • {{ $msg->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            @empty
                <div class="p-5 text-center text-soft">
                    <em class="icon ni ni-chat-circle-fill fs-1"></em>
                    <p>Start the conversation below.</p>
                </div>
            @endforelse
            <div id="chat-bottom"></div>
        </div>

        {{-- Reply Form: Refined Input Area --}}
        <div class="nk-reply-form border-top bg-white p-3">
            @if($ticket->status !== 'closed')
                <div class="form-group mb-2">
                    <textarea wire:model.defer="newMessage" 
                              class="form-control form-control-simple no-resize border shadow-none p-3" 
                              rows="3" 
                              placeholder="Reply to the user..." 
                              style="background: #f5f6fa; border-radius: 12px; font-size: 15px;"></textarea>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-soft small">
                        <em class="icon ni ni-help-fill"></em> Keep replies professional.
                    </div>
                    <button wire:click="sendMessage" wire:loading.attr="disabled" class="btn btn-primary btn-round px-4">
                        <span wire:loading.remove>
                            <em class="icon ni ni-send-alt"></em> <span>Send Reply</span>
                        </span>
                        <span wire:loading>
                            <em class="icon ni ni-loader spin"></em> <span>Sending...</span>
                        </span>
                    </button>
                </div>
                @error('newMessage') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
            @else
                <div class="alert alert-light border-0 text-center mb-0" style="background: #f8f9fc; border-radius: 10px;">
                    <em class="icon ni ni-lock-alt-fill text-soft fs-4"></em>
                    <h6 class="mt-2 text-soft">This ticket is marked as Resolved.</h6>
                </div>
            @endif
        </div>
    </div>
</div>