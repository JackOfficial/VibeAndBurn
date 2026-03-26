<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination; // Required for pagination in Livewire
use App\Models\Supports;
use App\Models\Ticket;

class TicketComponent extends Component
{
    use WithPagination;

    public $ticketID;
    public $msg;

    protected $paginationTheme = 'bootstrap'; // Or 'tailwind' depending on your CSS

    public function sendReply()
    {
        // 1. Validation
        $this->validate([
            'msg' => 'required|min:1',
            'ticketID' => 'required|exists:tickets,id'
        ]);

        // 2. Create the Support Reply
        $reply = Supports::create([
            'ticket_id' => $this->ticketID, // Standardized naming
            'reply' => $this->msg
        ]);

        if($reply) {
            // 3. Update Ticket status to 'Answered'
            Ticket::where('id', $this->ticketID)->update(['status' => 'answered']);
            
            $this->msg = ''; // Clear input field
            session()->flash('sendReplySuccess', 'Message was sent!');
        } else {
            session()->flash('sendReplyFail', 'Message was not sent. Try again!');
        }
    }

    public function render()
    {
        // Fetch specific conversation for the selected ticket
        $ticketMessages = Supports::join('tickets', 'supports.ticket_id', '=', 'tickets.id')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->where('tickets.id', $this->ticketID)
            ->select([
                'tickets.id as ticket_id',
                'tickets.subject',
                'tickets.status',
                'supports.id as support_id',
                'supports.message',
                'supports.reply',
                'supports.created_at as message_date',
                'users.google_id',
                'users.avatar'
            ])
            ->latest('supports.created_at')
            ->paginate(10, ['*'], 'messagesPage');

        // Fetch the list of all tickets for the sidebar/list view
        $allTickets = Ticket::join('users', 'tickets.user_id', '=', 'users.id')
            ->select(
                'tickets.*', 
                'tickets.id as ticket_id', 
                'users.name', 
                'users.email', 
                'users.google_id', 
                'users.avatar'
            )
            ->orderBy('tickets.id', 'DESC')
            ->paginate(15, ['*'], 'ticketsPage');

        return view('livewire.admin.ticket-component', [
            'ticket' => $ticketMessages,
            'chats' => $allTickets
        ]);
    }
}