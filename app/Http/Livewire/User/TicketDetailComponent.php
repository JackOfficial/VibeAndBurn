<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Support;
use Illuminate\Support\Facades\Auth;

class TicketDetailComponent extends Component
{
    public $ticket;
    public $newMessage;

    public function mount($id)
    {
        // Ensure the user can only view THEIR own tickets
        $this->ticket = Ticket::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
    }

    public function sendMessage()
{
    $this->validate([
        'newMessage' => 'required|min:2'
    ]);

    // 1. Add the message to the thread
    Support::create([
        'ticket_id' => $this->ticket->id,
        'user_id' => Auth::id(),
        'message' => $this->newMessage,
        'is_admin' => false,
    ]);

    // 2. Update ticket status AND last_reply_at
    // We added last_reply_at in our professional migration earlier!
    $this->ticket->update([
        'status' => 'pending',
        'last_reply_at' => now(), // This helps you sort oldest tickets in Admin
        'updated_at' => now()
    ]);

    $this->reset('newMessage');
    
    // 3. Optional: Emit a browser event for a "Success" toast
    $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Reply Sent!']);
}

    public function render()
    {
        return view('livewire.user.ticket-detail-component', [
            'messages' => Support::where('ticket_id', $this->ticket->id)
                ->with('user')
                ->oldest()
                ->get()
        ]);
    }
}