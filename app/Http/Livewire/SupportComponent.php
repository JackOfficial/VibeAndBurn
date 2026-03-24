<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Supports;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class SupportComponent extends Component
{
    public $tickedId;
    public $msg;
    
    public function sendTicket(){
        $sentTicket = Supports::create([
            'ticket_Id' => $this->tickedId,
            'message' => $this->msg
            ]);
            
        if($sentTicket){
          session()->flash('sendTicketSuccess', 'Message was sent!');
        }
        else{
          session()->flash('sendTicketFail', 'Message was not sent. Try again!');
        }
   }
   
    public function render()
    {
        $ticket = Supports::join('tickets', 'supports.ticket_id', 'tickets.id')
        ->where('tickets.id', $this->tickedId)
        ->select('tickets.*', 'supports.message')
        ->first();
        
        $support_chats = Supports::join('tickets', 'supports.ticket_id', 'tickets.id')
        ->join('users', 'tickets.user_id', 'users.id')
        ->where('tickets.id', $this->tickedId)
        ->select('tickets.*', 'supports.message', 'supports.reply', 'users.google_id', 'users.avatar')
        ->get();
        
        if(Auth::user()){
              $tickets = Ticket::where('user_id', Auth::id())->orderBy('tickets.id', 'DESC')
        ->get();
        }
        
        return view('livewire.support-component', compact('ticket', 'tickets', 'support_chats'));
    }
}
