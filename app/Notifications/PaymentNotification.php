<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentNotification extends Notification
{
    use Queueable;
    
    public $user;
    public $transaction_ref;
    public $amount;
    public $currency;
    public $payment_method;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $transaction_ref, $amount, $currency, $payment_method)
    {
        $this->user = $user;
        $this->transaction_ref = $transaction_ref;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->payment_method = $payment_method;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Payment was processed')
                    ->greeting('Hi '.$this->user->last_name.',')
                    ->line('Your payment was processed with the following details:')
                    ->line('Transaction reference: '.$this->transaction_ref)
                    ->line('Amount payed: '. $this->amount .' '. $this->currency)
                    ->line('Payment method: '.$this->payment_method)
                    ->action('Go to dashboard', url('/home'))
                    ->line('Thank you for being part of what we do!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
