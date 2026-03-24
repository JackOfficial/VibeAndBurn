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
    <i class="fa fa-plus"></i> Add Ticket
    </div>
    <div class="card-body">
         @if(Session::has("ticketSuccess"))
        <div class="alert alert-success">{{ Session::get("ticketSuccess") }}</div>
        @endif
        
         @if(Session::has("ticketFail"))
        <div class="alert alert-danger">{{ Session::get("ticketFail") }}</div>
        @endif
        <form wire:submit.prevent="send">
        <div class="form-group">
            <label for="subject">Subject</label>
                <select class="form-control @error('subject') is-invalid @enderror" id="subject" wire:model="subject" required>
                     <option value="">Select subject</option>
                    <option value="Order">Order</option>
                    <option value="Payment">Payment</option>
                    <option value="Request">Request</option>
                    <option value="Other">Other</option>
                  </select>
                  @error('subject')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $subject }}</strong>
            </span>
            @enderror
            </div>
            
             <div class="form-group {{ $subject != 'Order' ? 'd-none' : '' }}">
                  <label for="order_id">Order ID</label>
                <input type="number" id="order_id" value="{{ old('order_id') }}" class="form-control @error('order_id') is-invalid @enderror" placeholder="Enter order ID" wire:model="order_id" />
              @error('order_id')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $order_id }}</strong>
            </span>
            @enderror
              </div>
              
               <div class="form-group {{ $subject != 'Payment' ? 'd-none' : '' }}">
            <label for="payment">Payment</label>
                <select class="form-control @error('payment') is-invalid @enderror" id="payment" wire:model="payment">
                     <option value="">Select payment</option>
                    <option value="Order">AfriPay</option>
                    <option value="Payment">Flutterwave</option>
                    <option value="Other">Other</option>
                  </select>
                  @error('payment')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $payment }}</strong>
            </span>
            @enderror
            </div>

            <div class="form-group {{ $subject != 'Payment' ? 'd-none' : '' }}">
              <label for="transaction_id">Payment ID</label>
             <input type="text" value="{{ old('transaction_id') }}" id="transaction_id" class="form-control @error('transaction_id') is-invalid @enderror" placeholder="Enter transaction ID" wire:model="transaction_id" />
              @error('transaction_id')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $transaction_id }}</strong>
            </span>
            @enderror
            </div>
            
            <div class="form-group {{ $subject != 'Payment' ? 'd-none' : '' }}">
                  <label for="amount">Amount</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">$</span>
                </div>
                <input type="number" value="{{ old('amount') }}" id="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="Enter amount that you have paid" wire:model="amount" />
              </div>  
                 @error('amount')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $amount }}</strong>
            </span>
            @enderror
            </div>
            
            <div class="form-group {{ $subject != 'Order' ? 'd-none' : '' }}">
            <label for="myrequest">Request</label>
                <select class="form-control @error('myrequest') is-invalid @enderror" id="myrequest" wire:model="myrequest">
                     <option value="Refill">Refill</option>
                    <option value="Cancel">Cancel</option>
                    <option value="Speed Up">Speed Up</option>
                      <option value="Restart">Restart</option>
                    <option value="Other">Other</option>
                  </select>
                  @error('myrequest')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $myrequest }}</strong>
            </span>
            @enderror
            </div>
            
            <div class="form-group {{ ($subject == null || $subject == '') ? 'd-none' : '' }}">
              <label for="message">Message {{ $subject == 'Order' ? ' (optional)' : '' }}</label>
             <textarea rows="4" id="message" class="form-control @error('message') is-invalid @enderror" 
             placeholder="{{ $subject == 'Request' ? 'Which service would you like to request?' : '' }}" 
             wire:model="message" {{ ($subject == 'Order' || $subject == 'Request') ? 'required' : '' }}>{{ old('message') }}</textarea>
              @error('message')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
            @enderror
            </div>
           <hr>
   <button wire:loading.attr="disabled" type="submit" class="btn btn-block btn-primary">Submit Ticket</button>
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
               
        
           
    
   
    
    
    
    
    

   
