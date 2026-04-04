<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\subscription as Subscription;

class SubscriptionComponent extends Component
{
    use WithPagination;

    // Use Bootstrap styling for pagination links
    protected $paginationTheme = 'bootstrap';
    public $search = '';

    // Reset pagination when searching
public function updatingSearch()
{
    $this->resetPage();
}

    /**
     * Delete a subscriber and stay on the same page.
     */
    public function deleteSubscriber($id)
    {
        Subscription::findOrFail($id)->delete();
        
        session()->flash('message', 'Subscriber removed successfully.');
    }

    public function render()
    {
        $subscribers = Subscription::where('email', 'like', '%' . $this->search . '%')
        ->latest()
        ->paginate(15);

    return view('livewire.admin.subscription-component', [
        'subscribers' => $subscribers,
        'subscribersCounter' => Subscription::count(),
    ]);
    }
}