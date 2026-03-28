<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\order; 
use App\Models\fund;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrdersComponent extends Component
{
    use WithPagination;
    
    public $keyword;
    public $status;
    public $filterStatus;

    protected $paginationTheme = 'bootstrap';
    
    // Ensures search results start from page 1
    public function updatingKeyword() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }

    /**
     * Refunding the money back to the user
     */
   public function reverseOrder($orderId)
{
    // 1. Find the order
    $order = order::findOrFail($orderId);

    // 2. Prevent double refunding (Status 2 = Reversed)
    if ((int)$order->status === 2) {
        $this->dispatchBrowserEvent('toastr:info', ['message' => 'This order was already reversed.']);
        return;
    }

    try {
        DB::transaction(function () use ($order) {
            // 3. Mark Order as Reversed in the orders table
            $order->update(['status' => 2]);

            $wallet = wallet::where('user_id', $order->user_id)->firstOrFail();
            $wallet->increment('money', $order->charge);

            // 5. Create a record in your 'funds' table for the user's history
            fund::create([
                'user_id'  => $order->user_id,
                'method'   => 'Refund',
                'amount'   => $order->charge,
                'Payedwith' => 'Order #' . $order->id, // Tracking source of refund
            ]);
        });

        $this->dispatchBrowserEvent('toastr:success', [
            'message' => "Success: Order #{$orderId} reversed and funds returned!"
        ]);
        
    } catch (\Exception $e) {
        $this->dispatchBrowserEvent('toastr:error', [
            'message' => 'Refund failed: ' . $e->getMessage()
        ]);
    }
}

    public function userWalletDetails($userId)
    {
        $user = User::findOrFail($userId);
        
        // SQL-based summing is much more efficient
        $sum = order::where('user_id', $userId)->sum('charge');
        $fundAmount = fund::where('user_id', $userId)->sum('amount');
        $remain = $fundAmount - $sum;
        
        session()->flash("breathDetails", "The total funds paid by {$user->name} is: {$fundAmount}, the total amount charged: {$sum} and the balance should be: {$remain}");
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
        // 1. Eloquent Eager Loading
        $query = order::with([
            'user', 
            'service.category.socialmedia', 
            'service.source'
        ]);

        // 2. Search Logic (including relationships)
        if (!empty($this->keyword)) {
            $query->where(function($q) {
                $q->where('id', $this->keyword)
                  ->orWhere('link', 'LIKE', '%' . $this->keyword . '%')
                  ->orWhereHas('user', function($userQuery) {
                      $userQuery->where('name', 'LIKE', '%' . $this->keyword . '%')
                                ->orWhere('email', 'LIKE', '%' . $this->keyword . '%');
                  });
            });
        }

        // 3. Filter Logic
        if ($this->filterStatus !== null && $this->filterStatus !== "") {
            $query->where('status', $this->filterStatus);
        }

        // 4. Final Execution
        $ordersCounter = $query->count();
        $orders = $query->latest('id')->paginate(100);

        return view('livewire.admin.orders-component', compact('orders', 'ordersCounter'));
    }
}