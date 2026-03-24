<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\fund;
use App\Models\wallet;
use App\Models\order;
use App\Models\User;

class addFundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $userID = Auth::user()->id;
        
         $walletCounter = wallet::where('user_id', '=', $userID)->count();
    
    if($walletCounter == 0){
      $wallet = 0;  
    }
    else{
      $wallet = wallet::where('user_id', '=', $userID)->value('money');
    }
    
        return view('add-fund', compact('wallet'));
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
            'currency' => ['required', 'string'],
            'money' => ['required', 'integer', 'min:1'],
            // 'phone' => ['min:9', 'max:10', 'string']
            ]);
            
          return redirect()->back()->with('unsupportedCurrency', 'The currency '. $request->currency.' Is temporarily unsupported. It will work again very soon!');
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
        //
    }
}
