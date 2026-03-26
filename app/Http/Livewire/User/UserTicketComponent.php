<?php

namespace App\Livewire\User; // Updated Namespace for Livewire 3

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;
use App\Models\Supports; // Ensure this model name is plural in your migrations
use App\Models\TicketCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserTicketComponent extends Component
{
    use WithPagination;

    // Form fields
    public $subject, $category_id, $order_id, $message, $payment_ref;
    
    // UI state
    public $isCreating = false;

    protected $paginationTheme = 'bootstrap';

    // Livewire 3 uses the 'rules' method for more dynamic control
    public function rules()
    {
        $selectedCategory = TicketCategory::find($this->category_id);
        $categoryName = $selectedCategory ? strtolower($selectedCategory->name) : '';

        return [
            'subject' => 'required|min:5|max:255',
            'category_id' => 'required|exists:ticket_categories,id',
            // Order ID is only required if the category is about Orders
            'order_id' => Str::contains($categoryName, ['order', 'refill', 'cancel']) 
                            ? 'required|numeric' 
                            : 'nullable',
            'message' => 'required|min:10',
            'payment_ref' => Str::contains($categoryName, ['payment', 'deposit']) 
                            ? 'required|string' 
                            : 'nullable',
        ];
    }

    public function toggleCreate()
    {
        $this->isCreating = !$this->isCreating;
        $this->reset(['subject', 'category_id', 'order_id', 'message', 'payment_ref']);
        $this->resetErrorBag(); // Clears validation errors when toggling
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

        // 2. Create the first message in the thread
        Supports::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $this->message,
            'is_admin' => false,
        ]);

        session()->flash('success', 'Ticket opened successfully!');
        $this->isCreating = false; // Close the form
        $this->reset(['subject', 'category_id', 'order_id', 'message', 'payment_ref']);
    }

    public function render()
    {
        return view('livewire.user.user-ticket-component', [
            'tickets' => Ticket::where('user_id', Auth::id())
                ->with('category')
                ->latest()
                ->paginate(10),
            'categories' => TicketCategory::where('is_active', true)->get()
        ]); // Ensure this points to your DashLite user layout
    }
}