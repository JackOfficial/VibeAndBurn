<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\message as messageModel;
use App\Notifications\Message;
use App\Models\admin;
use App\Models\User;
use App\Notifications\NewContactMessage;
use Illuminate\Support\Facades\Notification;

class ContactusController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contactus');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  public function store(Request $request)
{
    $request->validate([
        'name'    => ['required', 'string', 'max:255'],
        'email'   => ['required', 'string', 'email', 'max:255'],
        'subject' => ['required', 'string', 'max:255'],
        'message' => ['required', 'string', 'max:5000'], // Increased max for long messages
    ]);

    $message = messageModel::create([
        'name'    => $request->name,
        'email'   => $request->email,
        'subject' => $request->subject,
        'message' => $request->message,
    ]);

    if ($message) {
        // Fetch all Admins and Super Admins using Spatie roles
        $admins = User::role(['Admin', 'Super Admin'])->get();

        // Send notification to all of them at once
        Notification::send($admins, new NewContactMessage($message));

        return redirect()->back()->with('sendMessageSuccess', 'Thank you for talking to us. Your message was sent successfully');
    }

    return redirect()->back()->with('sendMessageFail', 'Your message could not be sent');
}

}
