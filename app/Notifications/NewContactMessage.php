<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // 1. Uncomment this
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewContactMessage extends Notification // 2. Add 'implements ShouldQueue'
{
    // use Queueable; // 3. Uncomment this

    public $contactMessage;

    public function __construct($contactMessage)
    {
        $this->contactMessage = $contactMessage;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            // 4. Force the 'From' header to match your authenticated SMTP user
            ->from(config('mail.from.address'), config('mail.from.name')) 
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