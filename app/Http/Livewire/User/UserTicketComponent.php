<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;
use App\Models\Supports;
use App\Models\TicketCategories;
use Illuminate\Support\Facades\Auth;

class UserTicketComponent extends Component
{
    use WithPagination;

    // Form fields
    public $subject, $category_id, $order_id, $message;
    
    // UI state
    public $isCreating = false;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'subject' => 'required|min:5|max:255',
        'category_id' => 'required|exists:ticket_categories,id',
        'order_id' => 'nullable|numeric',
        'message' => 'required|min:10',
    ];

    public function toggleCreate()
    {
        $this->isCreating = !$this->isCreating;
        $this->reset(['subject', 'category_id', 'order_id', 'message']);
    }

    public function createTicket()
    {
        $this->validate();

        // 1. Create the Ticket Header
        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'category_id' => $this->category_id,
            'subject' => $this->subject,
            'order_id' => $this->order_id,
            'status' => 'pending',
            'priority' => 'medium',
        ]);

        // 2. Create the first message in the Supports thread
        Supports::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $this->message,
            'is_admin' => false,
        ]);

        session()->flash('success', 'Ticket opened successfully!');
        $this->toggleCreate();
    }

    public function render()
    {
        return view('livewire.user.user-ticket-component', [
            'tickets' => Ticket::where('user_id', Auth::id())
                ->with('category')
                ->latest()
                ->paginate(10),
            'categories' => TicketCategories::where('is_active', true)->get()
        ]);
    }
}