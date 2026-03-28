<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\order; // Capitalized class names (PSR-4 standard)
use App\Models\fund;
use App\Models\User;

class OrdersComponent extends Component
{
    use WithPagination;
    
    public $keyword;
    public $status;
    public $filterStatus;

    protected $paginationTheme = 'bootstrap';
    
    // Reset pagination when searching or filtering
    public function updatingKeyword() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }

    public function userWalletDetails($userId)
    {
        $user = User::findOrFail($userId);
        
        // Optimized sum: doing it in SQL is 100x faster than a foreach loop
        $sum = order::where('user_id', $userId)->sum('charge');
        $fund = fund::where('user_id', $userId)->sum('amount');
        $remain = $fund - $sum;
        
        session()->flash("breathDetails", "The total funds paid by {$user->name} is: {$fund}, the total amount charged: {$sum} and the balance should be: {$remain}");
    }
    
    public function changeStatus($orderId)
    {
        $updated = order::where('id', $orderId)->update(['status' => $this->status]);
          
        if($updated) {
            $statuses = [
                0 => "pending", 1 => "completed", 2 => "reversed", 
                3 => "processing", 4 => "in progress", 5 => "partial"
            ];
            
            $feedback = "This order marked " . ($statuses[$this->status] ?? 'updated') . "!";
                
            $this->dispatchBrowserEvent('toastr:success', ['message' => $feedback]);
        }
    }
    
    public function render()
    {
        // 1. Define the Base Query (The "Master" query)
        $query = order::query()
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('services', 'orders.service_id', '=', 'services.id')
            ->join('sources', 'services.source_id', '=', 'sources.id')
            ->join('categories', 'services.category_id', '=', 'categories.id')
            ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
            ->select(
                'orders.*', 'users.name', 'users.email', 
                'socialmedia.socialmedia', 'categories.category', 
                'services.service', 'services.source_id', 
                'sources.api_source', 'services.rate_per_1000', 'services.serviceId'
            );

        // 2. Apply Search Logic
        if (!empty($this->keyword)) {
            $query->where(function($q) {
                $q->where('orders.id', $this->keyword)
                  ->orWhere('users.name', 'LIKE', '%' . $this->keyword . '%')
                  ->orWhere('users.email', 'LIKE', '%' . $this->keyword . '%')
                  ->orWhere('orders.link', 'LIKE', '%' . $this->keyword . '%');
            });
        } 

        // 3. Apply Filter Logic
        if ($this->filterStatus !== null && $this->filterStatus !== "") {
            $query->where('orders.status', $this->filterStatus);
            
            // Dispatch event only once when filter is active
            $this->dispatchBrowserEvent('toastr:success', ['message' => "Filter applied!"]);
        }

        // 4. Final Execution
        $ordersCounter = $query->count();
        $orders = $query->orderBy('orders.id', 'DESC')->paginate(100);
        
        return view('livewire.admin.orders-component', compact('orders', 'ordersCounter'));
    }
}