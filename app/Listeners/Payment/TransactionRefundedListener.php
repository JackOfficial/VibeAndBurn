<?php

namespace App\Listeners\Payment;

use App\Events\TransactionRefunded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TransactionRefundedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TransactionRefunded  $event
     * @return void
     */
    public function handle(TransactionRefunded $event)
    {
        //
    }
}
