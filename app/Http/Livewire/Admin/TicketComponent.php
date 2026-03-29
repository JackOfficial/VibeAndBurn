<?php

namespace App\Http\Livewire\Admin;

use App\Models\Support;
use App\Models\Ticket;
use Livewire\Component;
use Livewire\WithPagination;

class TicketComponent extends Component
{
    use WithPagination;

    public $ticketID;
    public $msg;

    protected $paginationTheme = 'bootstrap';

    public function sendReply()
    {
        $this->validate([
            'msg' => 'required|min:1',
            'ticketID' => 'required|exists:tickets,id'
        ]);

        // Use the relationship to create the reply
        $ticket = Ticket::findOrFail($this->ticketID);
        
        $reply = $ticket->supports()->create([
            'reply' => $this->msg
        ]);

        if ($reply) {
            $ticket->update(['status' => 'answered']);
            
            $this->reset('msg');
            session()->flash('sendReplySuccess', 'Message was sent!');
        } else {
            session()->flash('sendReplyFail', 'Failed to send message.');
        }
    }

    public function render()
    {
        // 1. Fetch conversation for the selected ticket using relationships
        // Assuming Ticket hasMany Support
        $ticketMessages = Support::with(['ticket.user'])
            ->when($this->ticketID, function($query) {
                $query->where('ticket_id', $this->ticketID);
            }, function($query) {
                $query->where('id', 0); 
            })
            ->latest()
            ->paginate(10, ['*'], 'chatPage');

        // 2. Fetch all tickets for the sidebar using Eager Loading
        $allTickets = Ticket::with('user')
            ->latest('id')
            ->paginate(15, ['*'], 'ticketsPage');

        return view('livewire.admin.ticket-component', [
            'ticket' => $ticketMessages,
            'chats' => $allTickets
        ]);
    }
}