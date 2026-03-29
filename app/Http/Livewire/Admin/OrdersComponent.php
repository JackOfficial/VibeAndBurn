<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\order; 
use App\Models\fund;
use App\Models\User;
use App\Models\wallet;
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
    $order = order::findOrFail($orderId);

    // Prevent double refunding
    if ((int)$order->status === 2) {
        $this->dispatchBrowserEvent('toastr:info', ['message' => 'This order was already reversed.']);
        return;
    }

    try {
        DB::transaction(function () use ($order) {
            // STRATEGY: Use property assignment + save() to trigger ALL Observer events
            $order->status = 2;
            $order->save(); // This definitely triggers 'updating' and 'updated' in the Observer

            // Wallet Update
            $wallet = wallet::where('user_id', $order->user_id)->firstOrFail();
            
            // Ensure $order->charge is treated as a clean float (removing '$' if it exists)
            $refundAmount = (float) str_replace('$', '', $order->charge);
            $wallet->increment('money', $refundAmount);

            // Record in funds table
            fund::create([
                'user_id'   => $order->user_id,
                'method'    => 'Refund',
                'amount'    => $refundAmount,
                'Payedwith' => 'Order #' . $order->id, 
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
    
    // 1. Financial Overview
    $totalCharged = order::where('user_id', $userId)->sum('charge');
    $totalDeposits = fund::where('user_id', $userId)->sum('amount');
    $currentBalance = $totalDeposits - $totalCharged;

    // 2. Profit Calculation 
    // (User Price - Your Cost = Your Pocket Money)
    $totalCost = order::where('user_id', $userId)->sum('cost'); 
    $totalProfit = $totalCharged - $totalCost;

    // 3. Status Breakdown for Context
    $completedOrders = order::where('user_id', $userId)->where('status', 1)->count();
    $refundedOrders = order::where('user_id', $userId)->where('status', 2)->count();

    $message = "Financial Profile for {$user->name}:
                - Total Deposits: {$totalDeposits}
                - Total Spent: {$totalCharged}
                - Estimated Profit: {$totalProfit}
                - Current Wallet: {$currentBalance}
                - Activity: {$completedOrders} Completed / {$refundedOrders} Refunded";

    session()->flash("breathDetails", $message);
}
    
  public function changeStatus($orderId)
{
   $this->validate([
        'status' => 'required|integer|in:0,1,2,3,4,5'
    ]);

    // If they picked status 2 (Reversed), redirect them to the proper refund logic
    if ((int)$this->status === 2) {
        return $this->reverseOrder($orderId);
    }
    
    $order = order::findOrFail($orderId);
    $updated = $order->update(['status' => $this->status]);
      
    if($updated) {
        $statuses = [
            0 => "pending", 
            1 => "completed", 
            2 => "reversed", 
            3 => "processing", 
            4 => "in progress", 
            5 => "partial"
        ];
        
        $statusName = $statuses[$this->status] ?? 'updated';
        $feedback = "Order #{$orderId} marked as " . strtoupper($statusName) . "!";
        
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