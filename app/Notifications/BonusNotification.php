<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BonusNotification extends Notification
{
    use Queueable;

    public $user;
    public $bonus;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $bonus)
    {
       $this->user = $user;    
       $this->bonus = $bonus;
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
                    ->subject('You have recieved a bonus!')
                    ->greeting('Hi ' . $this->user->name . ',')
                    ->line('Your wallet has been credited a bonus equal to $' . $this->bonus)
                    ->line('Keep referring vibeandburn.com to earn more bonuses')
                    ->action('Order now!', url('https://vibeandburn.com/newOrder/create'))
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
