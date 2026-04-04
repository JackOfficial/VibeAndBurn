<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
//use Illuminate\Contracts\Queue\ShouldQueue;

class NewContactMessage extends Notification
{
    //use Queueable;

    public $contactMessage;

    public function __construct($contactMessage)
    {
        $this->contactMessage = $contactMessage;
    }

    public function via($notifiable)
    {
        // You can use 'database' to show it in the admin panel 
        // and 'mail' to send an email
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Website Message: ' . $this->contactMessage->subject)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have received a new message from ' . $this->contactMessage->name)
            ->line('Email: ' . $this->contactMessage->email)
            ->line('Message: ' . $this->contactMessage->message)
            ->action('View Message', url('/admin/messages'))
            ->line('Thank you for choosing VibeandBurn!');
    }

    public function toArray($notifiable)
    {
        return [
            'message_id' => $this->contactMessage->id,
            'name' => $this->contactMessage->name,
            'subject' => $this->contactMessage->subject,
        ];
    }
}