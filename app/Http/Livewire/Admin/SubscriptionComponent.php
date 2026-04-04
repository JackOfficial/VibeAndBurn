<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\subscription as Subscription;

class SubscriptionComponent extends Component
{
    use WithPagination;

    // Standard Bootstrap theme for Laravel 8 pagination
    protected $paginationTheme = 'bootstrap';
    
    public $search = '';

    /**
     * Livewire 2 hook: Resets pagination when $search changes.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteSubscriber($id)
    {
        $subscriber = Subscription::find($id);
        if ($subscriber) {
            $subscriber->delete();
            session()->flash('message', 'Subscriber removed successfully.');
        }
    }

    public function render()
    {
        // Filter logic for Laravel 8
        $subscribers = Subscription::where('email', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(15);

        return view('livewire.admin.subscription-component', [
            'subscribers' => $subscribers,
            'subscribersCounter' => Subscription::count(),
        ]);
    }
}