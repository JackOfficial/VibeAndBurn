<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Supports;
use Illuminate\Support\Facades\Auth;

class TicketComponent extends Component
{
    public $subject, $order_id, $transaction_id, $payment, $amount, $request, $myrequest, $message;
        
    public function send(){
        
        $this->validate([
            'message' => 'required'
            ]);
            
            if(Auth::check()){
                $user_id = Auth::id();
                $ticket = Ticket::create([
                    'user_id' => $user_id,
                    'subject' => $this->subject,
                    'order_id' => $this->order_id,
                    'transaction_id' => $this->transaction_id,
                    'payment' => $this->payment,
                    'amount' => $this->amount,
                    'request' => $this->request,
                    'myrequest' => $this->myrequest,
                    ]);
                    
                if($ticket){
                    $support = Supports::create([
            'ticket_Id' => $ticket->id,
            'message' => $this->message
            ]);
        if($support){
        $this->reset('subject', 'order_id', 'transaction_id', 'payment', 'request', 'message');
          session()->flash('ticketSuccess', 'Your ticket has been sent.');
        }  
        else{
            session()->flash('ticketFail', 'Your ticket could not be sent. Try again!');
        }
                }       
         
            }
            else{
                return redirect('/login');
            }
    }
    public function render()
    {
        if(Auth::user()){
              $tickets = Ticket::where('user_id', Auth::id())->orderBy('tickets.id', 'DESC')
              ->get();
        }
        
        //   $messages = Supports::join('tickets', 'supports.ticket_id', 'tickets.id')
        // ->where('tickets.user_id', Auth::id())
        // ->select('tickets.*', 'supports.message')
        // ->get();
      
        return view('livewire.ticket-component', compact('tickets'));
    }
}
