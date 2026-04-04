<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\message; // Suggestion: Rename to Message (PascalCase)
use App\Models\Blocklist;
use Livewire\WithPagination;

class MessageComponent extends Component
{
    use WithPagination;

    // Standardizes pagination for AdminLTE/Bootstrap
    protected $paginationTheme = 'bootstrap';

    public $checkbox = [];
    public $selectAll = false;
    public $search = '';

    /**
     * Resets pagination to page 1 whenever the search term changes.
     * Prevents "No results" if you search while on page 3.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteMultipleMessages()
    {
        if (empty($this->checkbox)) {
            session()->flash('feedback', 'No message selected');
            return;
        }

        message::whereIn('id', $this->checkbox)->delete();
        
        // Reset state after deletion
        $this->reset(['checkbox', 'selectAll']);
        session()->flash('feedback', 'Selected messages have been deleted');
    }

    public function delete($id)
    {
        message::findOrFail($id)->delete();
        session()->flash('feedback', 'Message deleted successfully');
    }

    public function blockEmail($email)
    {
        // Removed dd($email) to allow the process to complete
        // Using firstOrCreate to prevent duplicate block entries
        Blocklist::firstOrCreate(
            ['email' => $email],
            ['service' => 'contact']
        );

        session()->flash('feedback', "Email {$email} has been added to blocklist");
    }

    public function selectAllMessages()
    {
        if ($this->selectAll) {
            // Select IDs only from the filtered result set
            $this->checkbox = message::query()
                ->when($this->search, function($q) {
                    $q->where('email', 'LIKE', '%' . $this->search . '%')
                      ->orWhere('name', 'LIKE', '%' . $this->search . '%');
                })
                ->pluck('id')
                ->map(fn($id) => (string)$id) // Cast to string for checkbox consistency
                ->toArray();
        } else {
            $this->checkbox = [];
        }
    }

    public function render()
    {
        // Build a base query to reuse for both counting and results
        $baseQuery = message::query()
            ->when($this->search, function($q) {
                $q->where(function($sub) {
                    $sub->where('email', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('name', 'LIKE', '%' . $this->search . '%');
                });
            })
            ->orderBy('id', 'DESC');

        return view('livewire.message-component', [
            'messages' => $baseQuery->paginate(25),
            'messageCounter' => $baseQuery->count()
        ]);
    }
}