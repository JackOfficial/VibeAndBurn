<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\order; // Restored lowercase
use App\Models\service; // Restored lowercase
use App\Models\category; // Restored lowercase
use Illuminate\Support\Facades\DB;

class EditOrder extends Component
{
    // Keeping your exact naming conventions
    public $orderID; 
    public $category, $service; // Restored original names
    public $quantity, $charge, $rate_per_1000;
    public $link, $status, $comment;
    public $username, $email, $phone, $avatar;
    public $startCount, $remains, $orderId; // $orderID (DB PK) vs $orderId (API ID)

    protected $rules = [
        'startCount' => 'nullable|numeric',
        'remains' => 'nullable|numeric',
        'charge' => 'required',
    ];

    public function mount()
    {
        // Eager load relationships to replace manual joins
        $orderData = order::with(['user', 'service.category.socialmedia'])
            ->findOrFail($this->orderID);

        $this->loadOrderData($orderData);
    }

    public function loadOrderData($orderData)
    {
        $this->category = $orderData->service->category_id ?? null;
        $this->service  = $orderData->service_id;
        $this->quantity = $orderData->quantity;
        
        // Strip '$' for calculation/display
        $this->charge   = ltrim($orderData->charge, '$');
        
        $this->rate_per_1000 = $orderData->service->rate_per_1000 ?? 0;
        $this->link        = $orderData->link;
        $this->startCount  = $orderData->start_count;
        $this->remains     = $orderData->remains;
        $this->orderId     = $orderData->orderId; // API Provider ID
        $this->comment     = $orderData->comment;

        // User Details
        $this->username    = $orderData->user->name ?? 'N/A';
        $this->email       = $orderData->user->email ?? 'N/A';
        $this->phone       = $orderData->user->phone ?? 'N/A';
        $this->avatar      = $orderData->user->avatar ?? '';

        $statuses = [
            1 => "Completed", 2 => "Canceled", 3 => "Processing", 
            4 => "In progress", 5 => "Partial", 7 => "Updated"
        ];
        $this->status = $statuses[$orderData->status] ?? "Unknown";
    }

    /**
     * Updates order and triggers Observers
     */
    public function updateOrder()
    {
        $this->validate();

        try {
            // Finding the model instance triggers observers on save()
            $orderModel = order::findOrFail($this->orderID);

            $orderModel->start_count = $this->startCount;
            $orderModel->remains     = $this->remains;
            $orderModel->charge      = '$' . number_format((float)str_replace('$', '', $this->charge), 2, '.', '');
            $orderModel->orderId     = $this->orderId;
            $orderModel->status      = 7; 

            $orderModel->save(); 

            session()->flash('editOrderSuccess', "Order #{$this->orderID} updated successfully.");
        } catch (\Exception $e) {
            session()->flash('editOrderFail', "Error: " . $e->getMessage());
        }
    }

    /**
     * Transactional Manual Refund
     */
    public function manualRefund()
    {
        DB::transaction(function () {
            $orderModel = order::findOrFail($this->orderID);
            $user = $orderModel->user;

            // Extract numeric amount
            $refundAmount = (float) str_replace('$', '', $this->charge);
            
            // Logic: Increment user balance (assuming wallet column on user or related table)
            // If you have a separate 'wallet' model, use: App\Models\wallet::where('user_id', ...)->increment(...)
            $user->increment('balance', $refundAmount); 

            $orderModel->status = 2; // Canceled
            $orderModel->save();

            session()->flash('editOrderSuccess', "Refunded $$refundAmount successfully.");
            $this->loadOrderData($orderModel);
        });
    }

    public function render()
    {
        return view('livewire.admin.edit-order', [
            'categories' => category::orderBy('category', 'ASC')->get(),
            'services'   => service::where('category_id', $this->category)->get()
        ]);
    }
}