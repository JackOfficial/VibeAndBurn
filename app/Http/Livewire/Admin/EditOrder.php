<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\order; 
use App\Models\service; 
use App\Models\category; 
use App\Models\wallet; // Added
use App\Models\fund;   // Added
use Illuminate\Support\Facades\DB;

class EditOrder extends Component
{
    public $orderID; 
    public $category, $service; 
    public $quantity, $charge, $rate_per_1000;
    public $link, $status, $comment;
    public $username, $email, $phone, $avatar;
    public $startCount, $remains, $orderId; 
    public $description;

    protected $rules = [
        'startCount' => 'nullable|numeric',
        'remains' => 'nullable|numeric',
        'charge' => 'required',
    ];

    public function mount()
    {
        $orderData = order::with(['user', 'service.category.socialmedia'])
            ->findOrFail($this->orderID);

        $this->loadOrderData($orderData);
    }

    public function loadOrderData($orderData)
    {
        $this->category = $orderData->service->category_id ?? null;
        $this->service  = $orderData->service_id;
        $this->quantity = $orderData->quantity;
        $this->charge   = ltrim($orderData->charge, '$');
        $this->rate_per_1000 = $orderData->service->rate_per_1000 ?? 0;
        $this->link        = $orderData->link;
        $this->startCount  = $orderData->start_count;
        $this->remains     = $orderData->remains;
        $this->orderId     = $orderData->orderId; 
        $this->comment     = $orderData->comment;

        $this->username    = $orderData->user->name ?? 'N/A';
        $this->email       = $orderData->user->email ?? 'N/A';
        $this->phone       = $orderData->user->phone ?? 'N/A';
        $this->avatar      = $orderData->user->avatar ?? '';
        $this->description = $orderData->service->description ?? '';

        $statuses = [
            0 => "Pending", 1 => "Completed", 2 => "Canceled", 3 => "Processing", 
            4 => "In progress", 5 => "Partial", 7 => "Updated"
        ];
        $this->status = $statuses[$orderData->status] ?? "Unknown";
    }

public function updateOrder()
{
    $this->validate();

    try {
        $orderModel = order::findOrFail($this->orderID);

        // 1. Clean the input (remove $, commas, and spaces)
       // 2. Safety Check to prevent accidental 0.00 saves
        if ($this->charge <= 0 && $orderModel->charge > 0) {
            throw new \Exception("Charge cannot be zero. Please enter the amount paid.");
        }

        // 3. Assign values to the model
        $orderModel->start_count = $this->startCount;
        $orderModel->remains     = $this->remains;
        $orderModel->orderId     = $this->orderId; // External API ID
        
        // Save as a pure decimal (8 places) to match your DB precision
        $orderModel->charge      = $this->charge;

        // 4. Update Status (This will be detected by your Observer)
        $orderModel->status = 7; 

        // 5. Save the model
        // The Observer will catch this 'updated' event
        $orderModel->save(); 

        session()->flash('editOrderSuccess', "Order #{$this->orderID} updated and synced.");

        return redirect()->route('admin.clientOrders.index');
        
    } catch (\Exception $e) {
        session()->flash('editOrderFail', "Error: " . $e->getMessage());
    }
}

    public function manualRefund()
    {
        try {
            DB::transaction(function () {
                $orderModel = order::findOrFail($this->orderID);

                // Prevent double refunding
                if ((int)$orderModel->status === 2) {
                    throw new \Exception("This order is already refunded.");
                }

                $refundAmount = (float) str_replace('$', '', $this->charge);
                
                // 1. Update Wallet (Not User->balance)
                $wallet = wallet::where('user_id', $orderModel->user_id)->firstOrFail();
                $wallet->increment('money', $refundAmount);

                // 2. Create Fund record for the "Total Profit" math we built earlier
                fund::create([
                    'user_id'   => $orderModel->user_id,
                    'method'    => 'Refund',
                    'amount'    => $refundAmount,
                    'Payedwith' => 'Manual Refund Order #' . $orderModel->id,
                ]);

                // 3. Update Order Status
                $orderModel->status = 2; 
                $orderModel->save();

                session()->flash('editOrderSuccess', "Refunded $" . number_format($refundAmount, 2) . " successfully.");
                $this->loadOrderData($orderModel);
            });
        } catch (\Exception $e) {
            session()->flash('editOrderFail', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.edit-order', [
            'categories' => category::orderBy('category', 'ASC')->get(),
            'services'   => service::where('category_id', $this->category)->get()
        ]);
    }
}