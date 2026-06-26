<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class broadcastMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // Public properties are automatically available in your Blade view
    public $name; 
    public $email;
    public $subject;
    public $body; // Renamed from $messages to prevent Blade variable clashing

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $subject, $body)
    {
        $this->name = $name;
        $this->email = $email;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@vibeandburn.com', 'vibeandburn')
                    ->subject($this->subject)
                    ->view('admin.add.broadcast-message'); 
                    // No ->with() needed! $name, $email, $subject, and $body are already accessible in Blade.
    }
}