<?php

namespace App\Http\Livewire\Admin\Funds;

use Livewire\Component;
use App\Models\fund as Fund;
use Livewire\WithPagination;

class IndexComponent extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';

    /**
     * Reset pagination when the search term changes
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';

        // 1. Fetch funds with "Search Everything" logic
        $funds = Fund::with(['user.wallet'])
            ->where(function($q) use ($searchTerm) {
                $q->where('method', 'like', $searchTerm)
                  ->orWhere('Payedwith', 'like', $searchTerm)
                  ->orWhere('amount', 'like', $searchTerm)
                  ->orWhere('id', 'like', $searchTerm)
                  // Search inside the User relationship
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', $searchTerm)
                                ->orWhere('email', 'like', $searchTerm);
                  });
            })
            ->latest()
            ->paginate(25);

        // 2. Aggregates for the dashboard stats
        $fundsCounter = Fund::count();
        $fundsTotal = Fund::sum('amount');

        return view('livewire.admin.funds.index-component', [
            'funds' => $funds,
            'fundsCounter' => $fundsCounter,
            'fundsTotal' => $fundsTotal
        ]);
    }
}