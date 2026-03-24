<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\subscription;

class subscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         if($request->session()->has('adminName')){
            
             $name = $request->Session()->get('adminName');
        $subscribers = subscription::orderBy('id', 'DESC')->get();
        $subscribersCounter = subscription::count();
        return view('admin.manage.subscribers', compact('name', 'subscribers', 'subscribersCounter'));
         }
         else{
            return view('auth.admin-login'); 
        }
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
        $email = $request->email;
        $request->validate([
            'email'=> ['required', 'string', 'email', 'max:255', 'unique:subscriptions']
            ]);
         
         $sub= subscription::create([
             'email'=>$request->input('email')
         ]);
         
         if($sub==TRUE){
            //Mail::to($email)->send(new confirmSubscription($email));  
           return redirect()->back()->with('successSubscription', 'You have been subscribed');
         }
         else{
           return redirect()->back()->with('failSubscription', 'Your subscription could not be sent. There might be something wrong!');
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $subscription = subscription::where('id',$id)->delete();

        if($subscription){
            return redirect()->back()->with('deleteSubscriberSuccess', 'Subscriber was deleted successfully');
        }
        else{
            return redirect()->back()->with('deleteSubscriberFail', 'Subscriber could not be deleted');
        }
    }
}
