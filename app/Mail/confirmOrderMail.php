<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class confirmOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $name;
    public $email;
    public $service;
    public $link;
    public $quantity;
    public $charge;

    public function __construct($name, $email, $service, $link, $quantity, $charge)
    {
        $this->name=$name;
        $this->email= $email;
        $this->service=$service;
        $this->link= $link;
        $this->quantity=$quantity;
        $this->charge=$charge;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('approve-order')->with([
            'name'=>$this->name,
            'email'=>$this->email,
            'service'=>$this->service,
            'link'=>$this->link,
            'quantity'=>$this->quantity,
            'charge'=>$this->charge
        ]);
    }
}
