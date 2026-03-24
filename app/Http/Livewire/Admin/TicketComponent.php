<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Supports;
use App\Models\Ticket;

class TicketComponent extends Component
{
    public $ticketID;
    public $msg;
    
    public function sendReply(){
        $reply = Supports::create([
            'ticket_Id' => $this->ticketID,
            'reply' => $this->msg
            ]);
            
        if($reply){
          session()->flash('sendReplySuccess', 'Message was sent!');
        }
        else{
          session()->flash('sendReplyFail', 'Message was not sent. Try again!');
        }
   }
   
    public function render()
    {
        $ticket = Supports::join('tickets', 'supports.ticket_id', 'tickets.id')
        ->join('users', 'tickets.user_id', 'users.id')
        ->where('tickets.id', $this->ticketID)
        ->select('tickets.*', 'supports.message', 'supports.reply', 'users.google_id', 'users.avatar')
        ->get();
        
        $chats =  Ticket::join('users', 'tickets.user_id', 'users.id')
        ->select('tickets.*', 'users.name', 'users.email', 'users.google_id', 'users.avatar')
        ->orderBy('tickets.id', 'DESC')
        ->get();
        
        return view('livewire.admin.ticket-component', compact('ticket', 'chats'));
    }
}
