<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\PaymentProcessed;
use App\Listeners\PaymentProcessedListener;
use App\Events\Payment\TransactionCompleted;
use App\Listeners\Payment\TransactionCompletedListener;
use App\Events\Payment\TransactionRefunded;
use App\Listeners\Payment\TransactionRefundedListener;
use App\Events\Payment\TransactionFailed;
use App\Listeners\Payment\TransactionFailedListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PaymentProcessed::class => [
            PaymentProcessedListener::class,
        ],
         TransactionCompleted::class => [
            TransactionCompletedListener::class,
        ],
         TransactionRefunded::class => [
            TransactionRefundedListener::class,
        ],
        TransactionFailed::class => [
            TransactionFailedListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
