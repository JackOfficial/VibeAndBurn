<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\wallet;
use App\Models\service;
use App\Models\category;
use App\Models\order;
use App\Models\fund;
use App\Models\Terms;

class pagesController extends Controller
{
   public function index(){
       return view('index');
   }

 public function dashboard()
{
    $userID = Auth::id(); // Shorthand for Auth::user()->id

    // Use value() directly; it returns null if no record is found.
    // We then use the ?? operator to default to 0.00 and cast to float.
    $walletValue = wallet::where('user_id', $userID)->value('money');
    
    $wallet = (float) ($walletValue ?? 0);
    
    return view('new-order', compact('wallet'));
}

public function payInRwanda(Request $request){

   $walletCounter = wallet::where('user_id', '=', Auth::id())->count();
    
    if($walletCounter == 0){
      $wallet = 0;  
    }
    else{
      $wallet = wallet::where('user_id', '=', Auth::id())->value('money');
    }

    $amount = $request->input('money');
    $phone = $request->input('phone'); 
   return view('payInRwanda', compact('wallet', 'amount', 'phone'));
}

public function payInBurundi(Request $request){

   $walletCounter = wallet::where('user_id', '=', Auth::id())->count();
    
    if($walletCounter == 0){
      $wallet = 0;  
    }
    else{
      $wallet = wallet::where('user_id', '=', Auth::id())->value('money');
    }

    $amount = $request->input('money');
    $phone = $request->input('phone'); 
   return view('custom-payment', compact('wallet', 'amount', 'phone'));
}

public function payInTether(){
   $walletCounter = wallet::where('user_id', '=', Auth::id())->count();
    
    if($walletCounter == 0){
      $wallet = 0;  
    }
    else{
      $wallet = wallet::where('user_id', '=', Auth::id())->value('money');
    }

   return view('payInTether', compact('wallet'));  
}

public function sharelink($id){
    
   }
   
   public function fund(Request $request)
    {
           $funds = fund::join('users', 'funds.user_id', '=', 'users.id')
    ->select('funds.*', 'users.name', 'users.email')
     ->orderBy('funds.id', 'DESC')
     ->get();
     
     $fundsCounter = fund::join('users', 'funds.user_id', '=', 'users.id')
    ->count();
    
    $fundsTotal = fund::join('users', 'funds.user_id', '=', 'users.id')
    ->sum('amount');
    
    return view('admin.funds.index', compact('name', 'funds', 'fundsCounter', 'fundsTotal'));
    }

public function services(){
    
    $userID = Auth::user()->id;
         
         $walletCounter = wallet::where('user_id', '=', $userID)->count();
    
    if($walletCounter == 0){
      $wallet = 0;  
    }
    else{
      $wallet = wallet::where('user_id', '=', $userID)->value('money');
    }
    
         return view('ourservices', compact('wallet'));
}

public function terms(){
    $terms = Terms::first();
    return view('terms', compact('terms'));
}

public function termsAndConditions(){
    $terms = Terms::first();
    $userID = Auth::user()->id;
    $walletCounter = wallet::where('user_id', '=', $userID)->count();
    
    if($walletCounter == 0){
      $wallet = 0;  
    }
    else{
      $wallet = wallet::where('user_id', '=', $userID)->value('money');
    }
    return view('terms-and-conditions', compact('terms', 'wallet'));
}

}
