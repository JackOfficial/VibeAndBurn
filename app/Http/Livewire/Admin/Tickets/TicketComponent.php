<?php

namespace App\Http\Livewire\Admin\Tickets;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Message; // Or TicketReply depending on your table name
use App\Models\Blocklist;

class TicketComponent extends Component
{
    public $ticket;
    public $newMessage;

    /**
     * Initialize the component with the Ticket ID from the URL
     */
    public function mount($id)
    {
        // Fetch ticket with user and messages to avoid N+1 query issues
        $this->ticket = Ticket::with(['user', 'messages'])->findOrFail($id);
    }

    /**
     * Send a support reply to the user
     */
    public function sendMessage()
    {
        $this->validate([
            'newMessage' => 'required|min:2'
        ]);

        // 1. Create the message
        Message::create([
            'ticket_id' => $this->ticket->id,
            'user_id'   => auth()->id(),
            'message'   => $this->newMessage,
            'is_admin'  => true,
        ]);

        // 2. Update ticket status to 1 (Replied)
        $this->ticket->update(['status' => 1]);

        // 3. Clear the input and refresh ticket data
        $this->reset('newMessage');
        $this->ticket->refresh();

        session()->flash('feedback', 'Reply sent successfully.');
    }

    /**
     * Close the ticket (Mark as Resolved)
     */
    public function closeTicket()
    {
        $this->ticket->update(['status' => 2]); // 2 = Closed
        $this->ticket->refresh();
        
        session()->flash('feedback', 'Ticket #'.$this->ticket->id.' has been closed.');
    }

    /**
     * Block a user email (Helper from your SMM Panel logic)
     */
    public function blockEmail($email)
    {
        Blocklist::updateOrCreate(
            ['email' => $email],
            ['service' => 'tickets']
        );

        session()->flash('feedback', 'User email '.$email.' is now blocked.');
    }

    public function render()
    {
        return view('livewire.admin.tickets.ticket-component', [
            'messages' => $this->ticket->messages()->orderBy('created_at', 'asc')->get()
        ]);
    }
}