<?php

namespace App\Observers;

use App\Models\order;
use App\Models\wallet;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the order "creating" event.
     * Happens BEFORE the order is saved to the database.
     */
    public function creating(order $order)
    {
        // 1. Lock the wallet for update to prevent race conditions (especially for API orders)
        $wallet = wallet::where('user_id', $order->user_id)
            ->lockForUpdate() 
            ->first();

        // 2. Strict Balance Check
        $chargeAmount = (float) $order->charge;

        if (!$wallet || $wallet->money < $chargeAmount) {
            Log::warning("Order Blocked: User #{$order->user_id} has insufficient funds for Order Charge: {$chargeAmount}");
            
            // Returning false stops the 'save()' or 'create()' action entirely
            return false; 
        }

        // 3. Deduct the money immediately
        $wallet->decrement('money', $chargeAmount);
        
        Log::info("Order Authorized: User #{$order->user_id} charged {$chargeAmount}. Balance remaining: " . ($wallet->money - $chargeAmount));
    }

    /**
     * Handle the order "deleted" event.
     * Ensures automated refunding if an admin deletes a row manually.
     */
    public function deleted(order $order)
    {
        // 4. Status Check: Do not refund if the order was already Reversed (2) or Refunded (4)
        // This prevents "Double Refunding" if you use a manual refund button before deleting.
        if (in_array((int)$order->status, [2, 4])) {
            return;
        }

        $wallet = wallet::where('user_id', $order->user_id)->first();
        
        if ($wallet) {
            $wallet->increment('money', (float)$order->charge);
            Log::info("Order #{$order->id} Deleted: Refunded " . (float)$order->charge . " to User #{$order->user_id}");
        }
    }
}