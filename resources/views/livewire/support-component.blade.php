<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-2">
        <div class="alert">
          <h3>Welcome to the support section!</h3>
          <p>Please be sure to check the Frequently asked questions section 
          before opening a support ticket. You will find many answers 
          you are looking for in this section.</p>
        </div>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-12 col-12">
        <div class="card">
    <div class="card-header">
    Ticket ID: #{{ $tickedId }}
    </div>

    <div class="card-body">
        @if (session()->has('sendTicketSuccess'))
    <div class="alert alert-success alert-dismissible fade show">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>Success!</strong> {{ session('sendTicketSuccess') }}
</div>
   @endif
   
    @if (session()->has('sendTicketFail'))
    <div class="alert alert-danger alert-dismissible fade show">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>Success!</strong> {{ session('sendTicketFail') }}
</div>
   @endif
   
      <div class="p-2 mb-1 bg-primary text-light border rounded">Hi {{ Auth::user()->name }}, how can we help you today?</div>
      
      <div class="overflow-auto">
           @foreach($support_chats as $chat)
       @if($chat->message != null)
       <div class="p-2 mb-1 bg-secondary text-light border rounded text-left">
           {{ $chat->message }}
       </div>
       @endif
        @if($chat->reply != null)
        <div class="p-2 mb-1 bg-light border rounded text-right">
            {{ $chat->reply }}
            </div>
       @endif
       @endforeach
      </div>
      
  
      <form wire:submit.prevent="sendTicket">
         <div class="d-none"><input type="hidden" class="form-control" wire:model="tickedId" required /></div>
         <div class="input-group mb-3">
    <input type="text" wire:model="msg" class="form-control" />
    <div class="input-group-append">
      <button type="submit" class="input-group-text btn btn-primary">Send <div wire:loading wire:target="sendTicket" class="spinner-border spinner-border-sm"></div></button>
    </div>
  </div>  
      </form>
   </div>     
    
</div>
    </div>
     <div class="col-lg-4 col-md-4 col-sm-12 col-12">
         <div class="card">
            <div class="card-header">
                <i class="fa fa-help"></i> Support requests
            </div>
            <div class="card-body">
                @forelse($tickets as $ticket)
                <a href="/ticket/{{ $ticket->id }}" class="d-flex justify-content-between p-2 mb-1 bg-dark text-light rounded">
                    <div>
                        <div>#{{ $ticket->id }} - {{ $ticket->subject }}</div>
                        @if($ticket->status == 0)
                        <div class="text-warning">Pending</div>
                        @elseif($ticket->status == 1)
                        <div class="text-success">Answered</div>
                        @else
                        <div class="text-danger">Closed</div>
                        @endif
                    </div>
                    <div>{{ $ticket->created_at }}</div>
                </a>
                @empty
                <div>There's no support to display</div>
                @endforelse
            </div>
        </div>
    </div>
</div>