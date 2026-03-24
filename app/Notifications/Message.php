<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Message extends Notification
{
    use Queueable;
    
    public $admin, $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($admin, $message)
    {
       $this->admin = $admin;
       $this->message = $message;
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
                    ->from('hi@vibeandburn.com', 'Enquiry')
                    ->subject($this->message->subject)
                    ->greeting('Hi ' . $this->admin . ',')
                    ->line($this->message->message)
                    ->action('Reply', url('https://vibeandburn.com/contactus'))
                    ->line('This message was sent by ' . $this->message->name)
                    ->line('With email ' . $this->message->email);
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
