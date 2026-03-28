<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\order;
use Illuminate\Support\Facades\Auth;

class OrdersComponent extends Component
{
    use WithPagination;

    // Properties for UI state
    public $filter = "All";
    public $search = "";
    public $sortField = 'id';
    public $sortDirection = 'desc';

    // Ensure Livewire uses Bootstrap styling for pagination links
    protected $paginationTheme = 'bootstrap';

    // Reset pagination to page 1 whenever search or filter changes
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilter() { $this->resetPage(); }

    /**
     * Handle header clicks for sorting
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
        $statusMap = [
            "Completed"   => 1,
            "Canceled"    => 2,
            "Processing"  => 3,
            "In progress" => 4,
            "Partial"     => 5,
        ];

        // 1. Build Query with Eager Loading
        $query = order::where('user_id', Auth::id())
            ->with(['service.category.socialmedia'])
            
            // 2. Conditional Status Filter
            ->when(isset($statusMap[$this->filter]), function ($q) use ($statusMap) {
                return $q->where('status', $statusMap[$this->filter]);
            })
            
            // 3. Search Filter (Search by ID or Link)
            ->when($this->search, function ($q) {
                return $q->where(function($sub) {
                    $sub->where('id', 'like', '%' . $this->search . '%')
                        ->orWhere('link', 'like', '%' . $this->search . '%');
                });
            })
            
            // 4. Dynamic Sorting
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.orders-component', [
            'orders' => $query->paginate(10),
            'ordersCounter' => order::where('user_id', Auth::id())->count()
        ]);
    }
}