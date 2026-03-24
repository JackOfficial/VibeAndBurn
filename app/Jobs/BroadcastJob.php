<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Mail\broadcastMail;
use Illuminate\Support\Facades\Mail;

class BroadcastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $subject;
    public $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subject, $message)
    {
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        // $name = "Jack";
        // $myemail = "musengimanajacques@gmail.com";
        // Mail::to($myemail)->send(new broadcastMail($name, $myemail, $this->subject, $this->message)); 
        $users = User::all();
        foreach($users as $user)
        {  
          Mail::bcc($user->email)->send(new broadcastMail($user->name, $user->email, $this->subject, $this->message)); 
        }
    }
}
