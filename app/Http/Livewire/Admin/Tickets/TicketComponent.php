<?php

namespace App\Http\Livewire\Admin\Tickets;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Blocklist;
use App\Models\Support; // This is the correct model for your replies

class TicketComponent extends Component
{
    public $ticket;
    public $newMessage;

    public function mount($id)
    {
        // Now using 'messages' as defined in your updated Ticket model
        $this->ticket = Ticket::with(['user', 'messages'])->findOrFail($id);
    }

    public function sendMessage()
    {
        $this->validate([
            'newMessage' => 'required|min:2'
        ]);

        // Creating the reply in the 'supports' table via the Support model
        Support::create([
            'ticket_id' => $this->ticket->id,
            'user_id'   => auth()->id(),
            'message'   => $this->newMessage,
            'is_admin'  => true,
        ]);

        // Update ticket to status 1 (Replied)
        $this->ticket->update([
            'status' => 1,
            'last_reply_at' => now()
        ]);

        $this->reset('newMessage');
        $this->ticket->refresh();

        session()->flash('feedback', 'Reply sent successfully.');
    }

    public function closeTicket()
    {
        $this->ticket->update(['status' => 2]);
        $this->ticket->refresh();
        
        session()->flash('feedback', 'Ticket #'.$this->ticket->id.' has been closed.');
    }

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
            // Passing the fresh conversation to the view
            'messages' => $this->ticket->messages()->orderBy('created_at', 'asc')->get()
        ]);
    }
}