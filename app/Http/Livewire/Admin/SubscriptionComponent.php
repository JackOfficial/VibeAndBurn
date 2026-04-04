<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\subscription as Subscription;

class SubscriptionComponent extends Component
{
    use WithPagination;

    // Use Bootstrap styling for pagination links
    protected $paginationTheme = 'bootstrap';

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
        return view('livewire.admin.subscription-component', [
            'subscribers' => Subscription::latest()->paginate(15),
            'subscribersCounter' => Subscription::count(),
        ]);
    }
}