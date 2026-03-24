<?php

namespace App\Listeners\Payment;

use App\Events\TransactionFailed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TransactionFailedListener
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
     * @param  \App\Events\TransactionFailed  $event
     * @return void
     */
    public function handle(TransactionFailed $event)
    {
        //
    }
}
