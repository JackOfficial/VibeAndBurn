<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class broadcastMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name; 
    public $email;
    public $subject;
    public $messages; 
    public function __construct($name, $email, $subject, $messages)
    {
      $this->name = $name;
      $this->email = $email;
      $this->subject = $subject;
      $this->messages = $messages;
      
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
       ->view('admin.add.broadcast-message')->with([
            'name'=>$this->name,
            'email'=>$this->email,
            'subject'=>$this->subject,
            'messages'=>$this->messages,
        ]);
    }
}
