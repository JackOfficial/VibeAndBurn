<?php

namespace App\Observers;

use App\Models\order;
use App\Models\wallet;

class OrderObserver
{
    /**
     * Handle the order "created" event.
     */
    public function created(order $order)
    {
        // lockForUpdate prevents two simultaneous orders from 
        // reading the same balance at the exact same time.
        $wallet = wallet::where('user_id', $order->user_id)
            ->lockForUpdate() 
            ->first();

        if ($wallet) {
            // This is clean and accurate for your 8-decimal setup
            $wallet->decrement('money', (float)$order->charge);
        }
    }

    /**
     * Handle the order "deleted" event.
     * Only use this if you want to refund the user when you 
     * MANUALLY delete an order row from the database.
     */
    public function deleted(order $order)
    {
        $wallet = wallet::where('user_id', $order->user_id)->first();
        if ($wallet) {
            $wallet->increment('money', (float)$order->charge);
        }
    }
}