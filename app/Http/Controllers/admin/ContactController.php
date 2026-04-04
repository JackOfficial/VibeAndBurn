<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\message as messageModel;
// use App\Notifications\Message;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    use Notifiable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.messages.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email', 'max:255'],
    //         'subject' => ['required', 'string', 'max:255'],
    //         'message' => ['required', 'string', 'max:255'],
    //         ]);

    //    $message = messageModel::create([
    //        'name' => $request->input('name'),
    //        'email' => $request->input('email'),
    //        'subject' => $request->input('subject'),
    //        'message' => $request->input('message')
    //    ]);

    //    if($message){
    //     $user = Auth::user()->id;   
    //     $user->notify(new Message($user->name, $message));   
    //     return redirect()->back()->with('sendMessageSuccess','Thank you for talking to us. Your message was sent successfully');
    // }
    // else{
    //     return redirect()->back()->with('sendMessageFail','Your message could not be sent');
    // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
               $message = messageModel::findOrFail($id);
        $messageCounter = messageModel::count();
        return view('admin.messages.show', compact('message', 'messageCounter'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
