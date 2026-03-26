<div class="nk-msg">
    <div class="nk-msg-body show-msg" style="background: #ffffff;">
        {{-- Header: Stick to top with slight shadow for depth --}}
        <div class="nk-msg-head pb-3 border-bottom sticky-top bg-white" style="z-index: 10;">
            <div class="nk-msg-head-meta">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div>
                        <div class="d-flex align-items-center mb-1">
                            <a href="javascript:history.back()" class="btn btn-icon btn-trigger mr-2 d-md-none">
                                <em class="icon ni ni-arrow-left"></em>
                            </a>
                            <h4 class="title mb-0" style="font-weight: 700; color: #1c2b36;">{{ $ticket->subject }}</h4>
                        </div>
                        <ul class="nk-msg-tags g-2">
                            <li><span class="badge badge-dim badge-outline-light text-soft">ID: #{{ $ticket->id }}</span></li>
                            <li><span class="badge badge-dim badge-primary">{{ $ticket->category->name ?? 'General' }}</span></li>
                            @if($ticket->order_id)
                                <li><span class="badge badge-dim badge-info">Order: #{{ $ticket->order_id }}</span></li>
                            @endif
                        </ul>
                    </div>
                    <div class="nk-msg-actions text-right">
                        <div class="badge badge-md badge-dot {{ $ticket->status == 'pending' ? 'badge-warning' : ($ticket->status == 'answered' ? 'badge-success' : 'badge-secondary') }}">
                            {{ ucfirst($ticket->status) }}
                        </div>
                        <div class="sub-text mt-1 small text-soft">{{ $ticket->created_at->format('M d, Y • h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Expanded Chat Area: Increased height and reduced horizontal padding --}}
        <div class="nk-msg-reply nk-reply" 
             style="height: 700px; overflow-y: auto; background-color: #f5f7f9; padding: 40px 10% 20px 10%;">
            
            @forelse($messages as $msg)
                <div class="d-flex flex-column mb-4 {{ $msg->is_admin ? 'align-items-start' : 'align-items-end' }}">
                    
                    {{-- Wider Bubbles (90% instead of 80%) --}}
                    <div class="p-3 shadow-sm" 
                         style="max-width: 90%; font-size: 15px; line-height: 1.6;
                                {{ $msg->is_admin 
                                    ? 'background: #ffffff; color: #364a63; border: 1px solid #e5e9f2; border-radius: 0 20px 20px 20px;' 
                                    : 'background: #6576ff; color: #ffffff; border-radius: 20px 20px 0 20px;' }}">
                        
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $msg->message }}</p>
                    </div>

                    <div class="mt-1 px-2">
                        <small class="text-muted" style="font-size: 12px; font-weight: 500;">
                            {{ $msg->is_admin ? 'Support Agent' : 'Customer' }} • {{ $msg->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            @empty
                <div class="p-5 text-center text-soft">
                    <em class="icon ni ni-chat-circle-fill fs-1" style="opacity: 0.3;"></em>
                    <p class="mt-3">No messages yet. Send a reply to start the conversation.</p>
                </div>
            @endforelse
            <div id="chat-bottom"></div>
        </div>

        {{-- Reply Form: More vertical room for typing --}}
        <div class="nk-reply-form border-top bg-white p-4">
            @if($ticket->status !== 'closed')
                <div class="form-group">
                    <textarea wire:model.defer="newMessage" 
                              class="form-control form-control-simple no-resize border shadow-none p-3" 
                              rows="4" 
                              placeholder="Type your reply to the client..." 
                              style="background: #f8f9fa; border-radius: 15px; font-size: 16px; border: 1px solid #e5e9f2 !important;"></textarea>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-soft small d-flex align-items-center">
                        <em class="icon ni ni-info-fill fs-5 mr-1 text-info"></em> 
                        Press "Send Reply" to notify the user.
                    </div>
                    <button wire:click="sendMessage" wire:loading.attr="disabled" class="btn btn-lg btn-primary btn-round px-5">
                        <span wire:loading.remove>
                            <em class="icon ni ni-send-alt"></em> <span>Send Reply</span>
                        </span>
                        <span wire:loading>
                            <em class="icon ni ni-loader spin"></em> <span>Sending...</span>
                        </span>
                    </button>
                </div>
                @error('newMessage') <span class="text-danger small mt-2 d-block font-weight-bold">{{ $message }}</span> @enderror
            @else
                <div class="alert alert-fill alert-light text-center py-4 mb-0" style="border-radius: 15px;">
                    <em class="icon ni ni-lock-alt-fill text-soft fs-3"></em>
                    <h6 class="mt-2 text-dark">This conversation is resolved and locked.</h6>
                </div>
            @endif
        </div>
    </div>
</div>