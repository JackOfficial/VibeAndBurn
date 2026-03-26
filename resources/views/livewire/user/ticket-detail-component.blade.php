<div class="nk-msg nk-msg-custom-container" wire:poll.5s>
    <div class="nk-msg-body d-flex flex-column h-100">
        
        {{-- Header Section: Fixed height, never shrinks --}}
        <div class="nk-msg-head border-bottom bg-white px-4 py-3" style="flex: 0 0 auto;">
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
                            <li><span class="badge badge-dim badge-outline-light text-soft">#{{ $ticket->id }}</span></li>
                            <li><span class="badge badge-dim badge-primary">{{ $ticket->category->name ?? 'Support' }}</span></li>
                            @if($ticket->order_id)
                                <li><span class="badge badge-dim badge-info">Order: #{{ $ticket->order_id }}</span></li>
                            @endif
                        </ul>
                    </div>
                    <div class="nk-msg-actions text-right">
                        <div class="badge badge-md badge-dot {{ $ticket->status == 'pending' ? 'badge-warning' : ($ticket->status == 'answered' ? 'badge-success' : 'badge-secondary') }}">
                            {{ ucfirst($ticket->status) }}
                        </div>
                        <div class="sub-text mt-1 small text-soft">{{ $ticket->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chat History: This is the area that grows to fill the "Big" space --}}
        <div class="nk-msg-reply-scroll nk-reply" id="chat-container">
            @forelse($messages as $msg)
                <div class="d-flex flex-column mb-4 {{ $msg->is_admin ? 'align-items-start' : 'align-items-end' }}">
                    
                    <div class="bubble-msg {{ $msg->is_admin ? 'admin-bubble' : 'user-bubble' }}">
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $msg->message }}</p>
                    </div>

                    <div class="mt-1 px-2">
                        <small class="text-soft" style="font-size: 11px;">
                            <em class="icon ni {{ $msg->is_admin ? 'ni-shield-check-fill text-primary' : 'ni-user-fill' }}"></em>
                            {{ $msg->is_admin ? 'Support Agent' : 'You' }} • {{ $msg->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            @empty
                <div class="h-100 d-flex align-items-center justify-content-center flex-column text-soft">
                    <em class="icon ni ni-chat-circle-fill fs-1" style="opacity: 0.2;"></em>
                    <p class="mt-3">No messages in this ticket yet.</p>
                </div>
            @endforelse
            <div id="chat-bottom"></div>
        </div>

        {{-- Reply Form: Pinned to the bottom --}}
        <div class="reply-form-fixed">
            @if($ticket->status !== 'closed')
                <div class="form-group mb-3">
                    <textarea wire:model.defer="newMessage" 
                              class="form-control form-control-simple no-resize border shadow-none p-3" 
                              rows="3" 
                              placeholder="Write your message here..." 
                              style="background: #f5f6fa; border-radius: 12px; font-size: 15px; border: 1px solid #e5e9f2 !important;"></textarea>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-soft small">
                        <em class="icon ni ni-info-fill"></em> Press Enter to send.
                    </div>
                    <button wire:click="sendMessage" 
        wire:loading.attr="disabled" 
        wire:target="sendMessage" 
        class="btn btn-primary btn-lg px-5 btn-round">
    
    {{-- Hide this only when sendMessage is processing --}}
    <span wire:loading.remove wire:target="sendMessage">
        <em class="icon ni ni-send-alt"></em> 
        <span>Send Reply</span>
    </span>

    {{-- Show this only when sendMessage is processing --}}
    <span wire:loading wire:target="sendMessage">
        <em class="icon ni ni-loader spin"></em> 
        <span>Sending...</span>
    </span>
</button>
                </div>
                @error('newMessage') <span class="text-danger small mt-2 d-block font-weight-bold">{{ $message }}</span> @enderror
            @else
                <div class="alert alert-fill alert-light text-center py-3 mb-0" style="border-radius: 12px;">
                    <em class="icon ni ni-lock-alt-fill text-soft fs-4"></em>
                    <h6 class="mt-1 text-soft">Conversation Locked</h6>
                </div>
            @endif
        </div>
    </div>
</div>