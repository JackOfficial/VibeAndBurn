<?php

namespace App\Listeners\Payment;

use App\Events\TransactionCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Notifications\Notifiable;
use App\Notifications\PaymentNotification;

class TransactionCompletedListener
{
    use Notifiable;
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
     * @param  \App\Events\TransactionCompleted  $event
     * @return void
     */
    public function handle(TransactionCompleted $event)
    {
         $updateStatus = User::where('id', $event->client_token)->update([
        'status' => 2
        ]);

        if($updateStatus){
            $user = User::where('id', $event->client_token)->first();
            $user->notify(new PaymentNotification($user, $event->transaction_ref, $event->amount, $event->currency, $event->payment_method));
           
            if($user){
                Auth::login($user);
                return redirect('/home');
            }

        } 
    }
}
