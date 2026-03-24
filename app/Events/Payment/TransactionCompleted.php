<?php

namespace App\Events\Payment;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $feedback;
    public $status;
    public $transaction_ref;
    public $amount;
    public $currency;
    public $payment_method;
    public $client_token;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($feedback, $status, $transaction_ref, $amount, $currency, $payment_method, $client_token)
    {
      $this->feedback = $feedback;
      $this->status = $status; 
      $this->transaction_ref = $transaction_ref;
      $this->amount = $amount; 
      $this->currency = $currency; 
      $this->payment_method = $payment_method; 
      $this->client_token = $client_token;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new Channel('transaction-completed');
    }
    
     public function broadcastAs()
    {
        return 'transaction.completed';
    }
}
