<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\message as messageModel;
use App\Notifications\Message;
use App\Models\admin;
use App\Models\User;
use App\Notifications\NewContactMessage;
use Illuminate\Support\Facades\Log;
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
    $data = $request->validate([
        'name'    => ['required', 'string', 'max:255'],
        'email'   => ['required', 'string', 'email:rfc,dns', 'max:255'],
        'subject' => ['required', 'string', 'max:255'],
        'message' => ['required', 'string', 'max:5000'],
    ]);

    $message = messageModel::create($data);

    if ($message) {
        $admins = User::role(['Admin', 'Super Admin'])
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->get();

        if ($admins->isNotEmpty()) {
            // This just adds a record to the 'jobs' table. 
            // It doesn't connect to SMTP yet, so it won't throw a 503 error here.
            Notification::send($admins, new NewContactMessage($message));
        }

        return redirect()->back()->with('sendMessageSuccess', 'Thank you! Your message was sent successfully');
    }

    return redirect()->back()->with('sendMessageFail', 'Could not save message.');
}

}
