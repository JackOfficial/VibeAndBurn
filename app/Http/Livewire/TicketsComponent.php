<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;
use App\Models\Blocklist; // Ensure this model exists

class TicketsComponent extends Component
{
    use WithPagination;

    // Use Bootstrap styling for pagination
    protected $paginationTheme = 'bootstrap';

    public $checkbox = [];
    public $selectAll = false;
    public $search = '';
    public $status = null; // null = all, 0 = pending, 1 = replied, 2 = closed

    // Reset pagination when search or status changes
    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }

    public function selectAllMessages()
    {
        $this->selectAll = !$this->selectAll;
        if ($this->selectAll) {
            // Only select IDs from the current filtered set to avoid accidental mass deletion
            $this->checkbox = Ticket::query()
                ->when($this->status !== null, fn($q) => $q->where('status', $this->status))
                ->search($this->search)
                ->pluck('id')
                ->map(fn($id) => (string)$id)
                ->toArray();
        } else {
            $this->checkbox = [];
        }
    }

    public function deleteMultipleMessages()
    {
        if (empty($this->checkbox)) {
            session()->flash('feedback', 'No tickets selected');
            return;
        }

        Ticket::whereIn('id', $this->checkbox)->delete();
        $this->reset(['checkbox', 'selectAll']);
        session()->flash('feedback', 'Selected tickets deleted successfully');
    }

    public function delete($id)
    {
        Ticket::findOrFail($id)->delete();
        session()->flash('feedback', 'Ticket #' . $id . ' deleted');
    }

    public function blockEmail($email)
    {
        Blocklist::updateOrCreate(
            ['email' => $email],
            ['service' => 'contact']
        );
        session()->flash('feedback', 'User email ' . $email . ' has been blocked');
    }

    public function render()
    {
        $query = Ticket::query()
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->select('tickets.*', 'users.name', 'users.email')
            // Handle Search
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('tickets.id', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('users.name', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('tickets.subject', 'LIKE', '%' . $this->search . '%');
                });
            })
            // Handle Status Filter
            ->when($this->status !== null, function ($q) {
                $q->where('tickets.status', $this->status);
            })
            ->orderBy('tickets.id', 'DESC');

        return view('livewire.tickets-component', [
            'tickets' => $query->paginate(15),
            'ticketsCounter' => Ticket::count()
        ]);
    }
}