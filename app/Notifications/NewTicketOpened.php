<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class NewTicketOpened extends Notification
{
    // use Queueable;

    public $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            // Namecheap fix: Force 'From' to match your authorized SMTP user
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('New Support Ticket: #' . $this->ticket->id . ' - ' . $this->ticket->subject)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new support ticket has been opened by ' . Auth::user()->name)
            ->line('Subject: ' . $this->ticket->subject)
            ->line('Status: ' . $this->ticket->status == 0 ? 'Pending': 'Closed')
            ->action('View Ticket', url('/admin/ticket/' . $this->ticket->id))
            ->line('Please respond to the user as soon as possible.');
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'subject' => $this->ticket->subject,
            'user_name' => Auth::user()->name,
        ];
    }
}