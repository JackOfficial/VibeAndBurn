<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\order;
use App\Models\User;
use App\Models\wallet;
use App\Models\fund;
use App\Models\BFCurency;

class clientOrdersController extends Controller
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

        return view('admin.manage.orders', compact('name'));
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
    public function create(Request $request)
    {
        if($request->session()->has('adminName')){
            
        $name = $request->Session()->get('adminName');
        $users = User::orderBy('name', 'ASC')->get();
        return view('admin.add.fund', compact('name', 'users'));
        }
        else{
            return view('auth.admin-login'); 
        }
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
           'user' => ['required'],
          'currency' => ['required'],
            'money' => ['required'],
           ]);

       $userID = $request->user;
       $currency = $request->currency;
       $amount = $request->money;
       $bf = BFCurency::where('id', 1)->value('currency');
       
        if($currency == "BIF"){
              $result = $amount/$bf;
            }
            elseif($currency == "USD"){
              $result = $amount;  
            }
            else{
                $result = false; 
            }
            
//             else{
//              //Currency Conversion
//       $endpoint = 'convert';
// $access_key = 'cbee96985754e4844413ba838ffb7e76';

// $from = $currency;
// $to = 'USD';
// // initialize CURL:
// $ch = curl_init('https://api.exchangeratesapi.io/v1/'.$endpoint.'?access_key='.$access_key.'&from='.$from.'&to='.$to.'&amount='.$amount.'');
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// // get the JSON data:
// $json = curl_exec($ch);
// curl_close($ch);

// // Decode JSON response:
// $conversionResult = json_decode($json, true);
// $result = $conversionResult['result'];   
//             }

if($result != false){
    $wallet = wallet::where('user_id', '=', $userID)->count();
       if($wallet == 0){
        wallet::create([
            'user_id' => $userID,
            'money' => $result,
        ]); 
       }
       else{
        $mywallet = wallet::where('user_id', '=', $userID)->sum('money');
        wallet::where('user_id', '=', $userID)->update([
            'money' => $mywallet + $result,
        ]);
       }

       $fund = fund::create([
        'user_id' => $userID,
        'method' => $currency,
        'amount' => $result,
    ]);

    if($fund){
    return redirect()->back()->with('adminAddFundSuccess','Fund has been added successfully');
 }
 else{
     return redirect()->back()->with('adminAddFundFail','Fund could not be added');
 }
}
 else{
    return redirect()->back()->with('adminAddFundFail','BIF and USD are the only currency supported currently!'); 
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
        $orderID = $id;
        return view('admin.edit.order', compact('orderID'));
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
