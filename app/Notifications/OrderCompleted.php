<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCompleted extends Notification
{
    use Queueable;
    
    public $user, $order_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
     
    public function __construct($user, $order_id)
    {
        $this->user = $user;
        $this->order_id = $order_id;
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
                    ->from('orders@vibeandburn.com', 'vibeandburn')
                    ->subject('Order Completed!')
                    ->greeting('Hi ' . $this->user->name)
                    ->line('The order ' . $this->order_id . ' has been completed.')
                    ->line('Kindly refer vibeandburn.com to your friends and gain some bonuses')
                    ->action('New Order', url('https://vibeandburn.com/home'))
                    ->line('Keep enjoying our services!');
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
