<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\order;
use Illuminate\Support\Facades\Auth;

class OrdersComponent extends Component
{
    public $filter = "All";

    public function render()
    {
        // 1. Map filter names to status IDs
        $statusMap = [
            "Completed"   => 1,
            "Canceled"    => 2,
            "Processing"  => 3,
            "In progress" => 4,
            "Partial"     => 5,
        ];

        // 2. Start the base query scoped to the current user
        // We use 'with' for Eager Loading instead of joins
        $query = order::where('user_id', Auth::id())
            ->with(['service.category.socialmedia'])
            ->latest('id');

        // 3. Apply status filter if it's not "All"
        if (isset($statusMap[$this->filter])) {
            $query->where('status', $statusMap[$this->filter]);
        }

        // 4. Final Execution
        $orders = $query->get();
        $ordersCounter = $orders->count();

        return view('livewire.orders-component', compact('orders', 'ordersCounter'));
    }
}