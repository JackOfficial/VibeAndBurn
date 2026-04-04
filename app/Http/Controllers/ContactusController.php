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
    // 1. Validation (Keep it strict)
    $data = $request->validate([
        'name'    => ['required', 'string', 'max:255'],
        'email'   => ['required', 'string', 'email:rfc,dns', 'max:255'], // Added DNS check
        'subject' => ['required', 'string', 'max:255'],
        'message' => ['required', 'string', 'max:5000'],
    ]);

    // 2. Database Record Creation
    $message = messageModel::create($data);

    if ($message) {
        try {
            // 3. Fetch Admins with Spatie Roles
            // Only fetch users who actually have an email set to avoid SMTP rejection
            $admins = User::role(['Admin', 'Super Admin'])
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->get();

            // 4. Send Notifications
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new NewContactMessage($message));
            }
        } catch (\Exception $e) {
            // 5. Silent Log (Prevents the 503 error from crashing the page)
            Log::error("Notification failed for Message ID {$message->id}: " . $e->getMessage());
            
            // Note: We don't return an error to the user because 
            // the message WAS successfully saved to the database.
        }

        return redirect()->back()->with('sendMessageSuccess', 'Thank you for talking to us. Your message was sent successfully');
    }

    return redirect()->back()->with('sendMessageFail', 'Your message could not be sent. Please try again later.');
}

}
