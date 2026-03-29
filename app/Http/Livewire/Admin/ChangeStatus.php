<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\order;
use App\Models\wallet;
use App\Models\fund;
use Illuminate\Support\Facades\DB;

class ChangeStatus extends Component
{
    public $orderId;
    public $orderStatus;
    public $status;

    public function updatedStatus()
    {
        // 1. Validation
        $this->validate([
            'status' => 'required|integer|in:0,1,2,3,4,5'
        ]);

        $order = order::findOrFail($this->orderId);

        // 2. Logic Branch: If status is 'Reversed' (2), handle refund
        if ((int)$this->status === 2) {
            $this->processRefund($order);
            return;
        }

        // 3. Normal Status Update
        $updated = $order->update([
            'status' => $this->status
        ]);

        if ($updated) {
            $statuses = [
                0 => "pending",
                1 => "completed",
                3 => "processing",
                4 => "in progress",
                5 => "partial"
            ];

            $statusName = $statuses[$this->status] ?? 'updated';
            
            $this->dispatchBrowserEvent('toastr:success', [
                'message' => "Order #{$this->orderId} marked as " . strtoupper($statusName) . "!",
            ]);
        }
    }

    /**
     * Shared logic to handle the wallet refund and fund logging
     */
    protected function processRefund($order)
    {
        try {
            DB::transaction(function () use ($order) {
                // Update Order Status to 2 (Reversed)
                $order->update(['status' => 2]);

                // Wallet Update
                $wallet = wallet::where('user_id', $order->user_id)->firstOrFail();
                $refundAmount = (float) str_replace('$', '', $order->charge);
                $wallet->increment('money', $refundAmount);

                // Create Fund Record for Audit Trail
                fund::create([
                    'user_id'   => $order->user_id,
                    'method'    => 'Refund',
                    'amount'    => $refundAmount,
                    'Payedwith' => 'Order #' . $order->id,
                ]);
            });

            $this->dispatchBrowserEvent('toastr:success', [
                'message' => "Order #{$this->orderId} Reversed & Funds Refunded!",
            ]);

        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('toastr:error', [
                'message' => 'Refund failed: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.change-status');
    }
}