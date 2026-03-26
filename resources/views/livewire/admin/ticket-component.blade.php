<div class="mx-3">
    @if(session()->has('sendReplySuccess'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('sendReplySuccess') }}
        </div>
    @endif

    @if(session()->has('sendReplyFail'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('sendReplyFail') }}
        </div>
    @endif

    <div class="card direct-chat direct-chat-primary">
        <div class="card-header">
            <h3 class="card-title">Ticket ID #{{ $ticketID }}</h3>
            <div class="card-tools">
                <span title="Messages" class="badge badge-primary">{{ $ticket->total() }}</span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                    <i class="fas fa-comments"></i>
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="direct-chat-messages">
                @foreach($ticket as $chat)
                    {{-- User Message (Left) --}}
                    @if($chat->message)
                        <div class="direct-chat-msg">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-left">{{ $chat->name ?? 'User' }}</span>
                                <span class="direct-chat-timestamp float-right">{{ \Carbon\Carbon::parse($chat->message_date)->format('d M h:i A') }}</span>
                            </div>
                            <img class="direct-chat-img" src="{{ $chat->avatar ?? asset('back/dist/img/user1-128x128.jpg') }}" alt="User Image">
                            <div class="direct-chat-text">
                                {{ $chat->message }}
                            </div>
                        </div>
                    @endif

                    {{-- Admin Reply (Right) --}}
                    @if($chat->reply)
                        <div class="direct-chat-msg right">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-right">Admin Support</span>
                                <span class="direct-chat-timestamp float-left">{{ \Carbon\Carbon::parse($chat->message_date)->format('d M h:i A') }}</span>
                            </div>
                            <img class="direct-chat-img" src="{{ asset('back/dist/img/admin-avatar.png') }}" alt="Admin Image">
                            <div class="direct-chat-text">
                                {{ $chat->reply }}
                            </div>
                        </div>
                    @endif
                @endforeach
                
                <div class="mt-3">
                    {{-- {{ $ticket->links() }} --}}
                </div>
            </div>

            <div class="direct-chat-contacts">
                <ul class="contacts-list">
                    @forelse($chats as $ticketItem)
                        <li>
                            {{-- Using wire:click or a route to change the ticketID --}}
                            <a href="#" wire:click.prevent="$set('ticketID', {{ $ticketItem->ticket_id }})">
                                <img class="contacts-list-img" src="{{ $ticketItem->avatar ?? asset('back/dist/img/user1-128x128.jpg') }}">
                                <div class="contacts-list-info">
                                    <span class="contacts-list-name">
                                        {{ $ticketItem->name }}
                                        <small class="contacts-list-date float-right">{{ $ticketItem->created_at->format('d/m/Y') }}</small>
                                    </span>
                                    <span class="contacts-list-msg">Ticket: {{ $ticketItem->subject }}</span>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="p-2 text-white">No tickets found!</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="card-footer">
            {{-- prevent default on enter key --}}
            <form wire:submit.prevent="sendReply">
                <div class="input-group">
                    <input type="text" wire:model.defer="msg" placeholder="Type Message ..." class="form-control @error('msg') is-invalid @enderror">
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="sendReply">Send</span>
                            <span wire:loading wire:target="sendReply">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </span>
                </div>
                @error('msg') <span class="text-danger small">{{ $message }}</span> @enderror
            </form>
        </div>
    </div>
</div>