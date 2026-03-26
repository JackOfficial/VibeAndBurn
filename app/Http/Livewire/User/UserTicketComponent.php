<?php

namespace App\Http\User; // Your specific required namespace

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;
use App\Models\Supports; 
use App\Models\TicketCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserTicketComponent extends Component
{
    use WithPagination;

    // Form fields
    public $subject, $category_id, $order_id, $message, $payment_ref, $other_subject;
    
    // UI state
    public $isCreating = false;

    protected $paginationTheme = 'bootstrap';

    public function rules()
    {
        $selectedCategory = TicketCategory::find($this->category_id);
        $categoryName = $selectedCategory ? strtolower($selectedCategory->name) : '';

        return [
            'category_id' => 'required|exists:ticket_categories,id',
            'subject' => 'required',
            'other_subject' => ($this->subject === 'Other') ? 'required|min:5|max:255' : 'nullable',
            'order_id' => Str::contains($categoryName, ['order', 'refill', 'cancel']) 
                            ? 'required|numeric' 
                            : 'nullable',
            'payment_ref' => Str::contains($categoryName, ['payment', 'deposit']) 
                            ? 'required|string' 
                            : 'nullable',
            'message' => 'required|min:10',
        ];
    }

    public function mount()
    {
        $defaultCategory = TicketCategory::where('name', 'LIKE', '%Order%')
                            ->where('is_active', true)
                            ->first();

        if ($defaultCategory) {
            $this->category_id = $defaultCategory->id;
        }
    }

    public function toggleCreate()
    {
        $this->isCreating = !$this->isCreating;
        
        if (!$this->isCreating) {
            $this->reset(['subject', 'category_id', 'order_id', 'message', 'payment_ref', 'other_subject']);
            $this->resetErrorBag();
        } else {
            $this->mount(); 
        }
    }

    public function updatedCategoryId()
    {
        $this->reset(['subject', 'other_subject']);
    }

    public function createTicket()
    {
        $this->validate();

        $finalSubject = ($this->subject === 'Other') ? $this->other_subject : $this->subject;

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'category_id' => $this->category_id,
            'subject' => $finalSubject,
            'order_id' => $this->order_id,
            'status' => 'pending',
            'priority' => 'medium',
        ]);

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
            'categories' => TicketCategory::where('is_active', true)->get()
        ]);
    }
}