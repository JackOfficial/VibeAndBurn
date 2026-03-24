<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\category;
use App\Models\fund;
use App\Models\order;
use App\Models\wallet;

class newOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userID = Auth::user()->id;
        $categories = category::orderBy('category', 'ASC')->get();
        $spending = order::where('user_id','=', Auth::user()->id)
        ->where('status', '!=', 2)->get();
        $x= 0;
        foreach($spending as $spending){
            $x+= substr($spending->charge, 1, 255);
        }
        
        $walletCounter = wallet::where('user_id', '=', $userID)->count();
    
    if($walletCounter == 0){
      $wallet = 0;  
    }
    else{
      $wallet = wallet::where('user_id', '=', $userID)->value('money');
    }
    
        return view('new-order', compact('wallet', 'categories', 'x'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
