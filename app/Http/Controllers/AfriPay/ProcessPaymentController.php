<?php

namespace App\Http\Controllers\AfriPay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPayment;
use App\Events\Payment\TransactionCompleted;
use App\Events\Payment\TransactionRefunded;
use App\Events\Payment\TransactionFailed;
use Illuminate\Support\Facades\Auth;
use App\Models\wallet;
use App\Models\Currencies;
use App\Models\fund;
use App\Models\sharedlink;
use App\Models\bonus;

class ProcessPaymentController extends Controller
{
    public function pay(Request $request){
    
    $walletCounter = wallet::where('user_id', '=', Auth::id())->count();
    
    if($walletCounter == 0){
      $wallet = 0;  
    }
    else{
      $wallet = wallet::where('user_id', '=', Auth::id())->value('money');
    }
    
        $message = $request->message;
        
        if($request->status){
          return view('payment.pay-with-afripay', compact('message', 'wallet')); 
        }
        else{
          return "Something went wrong. Please try again!";
        }
    }
    
    public function process(Request $request){
        $status = $request->status;
        $transaction_ref = $request->transaction_ref;
        $amount	= $request->amount;
        $currency = ($request->currency == "KSH") ? "KES" : $request->currency;
        $payment_method = $request->payment_method;
        $user = User::where('id', $request->client_token)->first();
        
        if($status == "success"){
        $getcurrency_value = Currencies::where('currency', $currency)->value('currency_value'); 
            if($getcurrency_value){
               $converted_amount = $amount/$getcurrency_value;
            }
            else{
                // send currency conversion API
         $endpoint = 'convert';
        $access_key = 'cbee96985754e4844413ba838ffb7e76';

        $from = $currency;
        $to = 'USD';

        // initialize CURL:
        $ch = curl_init('https://api.exchangeratesapi.io/v1/'.$endpoint.'?access_key='.$access_key.'&from='.$from.'&to='.$to.'&amount='.$amount.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // get the JSON data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $conversionResult = json_decode($json, true);

        // access the conversion result
        $converted_amount = $conversionResult['result'] ?? 0;
        // end convert API
            }

        $wallet = wallet::where('user_id', $user->id)->count();
        if($wallet == 0){
        wallet::create([
            'user_id' => $user->id,
            'money' => ($converted_amount > 100) ? $converted_amount + ($converted_amount*5)/100 : $converted_amount,
        ]); 
       }
       else{
        $mywallet = wallet::where('user_id', '=', $user->id)->value('money');
        $converted_amount = ($converted_amount > 100) ? $converted_amount + ($converted_amount*5)/100 : $converted_amount;
        wallet::where('user_id', $user->id)->update([
            'money' => $mywallet + $converted_amount,
        ]);
       }
       
        $fund = fund::create([
        'user_id' => $user->id,
        'method' => $payment_method,
        'amount' => $converted_amount,
        'Payedwith' => 'AfriPay'
         ]);
         
         if($fund){
             
           $UserPayment = UserPayment::create([
            'transaction_ref' => $transaction_ref,
            'user_id' => $user->id,
            'amount' => $amount,
            'currency' => $currency,
            'payment_method' => $payment_method,
            'status' => "Success",
            ]);
            
     $linkOwner = $user->linkOwner;
     if($linkOwner != NULL){
    $bonus = ($converted_amount*5)/100;
      
      if($user->id != $linkOwner){
          //set bonuses
     sharedlink::create([
         'user_id' => $user->id,
        'amount' => $bonus,
        'descendant' => $linkOwner,
        ]);
        
      //set total bonus      
    $theBonus = bonus::where('user_id', $linkOwner)->count();
       if($theBonus == 0){
        bonus::create([
          'user_id' => $linkOwner,
          'bonus' => $bonus,
            ]);
       }
       else{
        $mybonus = bonus::where('user_id', $linkOwner)->value('bonus');
        bonus::where('user_id', $linkOwner)->update([
            'bonus' => $mybonus + $bonus,
            'status' => 3
        ]);   
       }  
      }
    
        $feedback = "Transaction has been completed";
                echo $feedback;
                // event(new TransactionCompleted($feedback, $status, $transaction_ref, $amount, $currency, $payment_method, $client_token));
                // return "Transaction now completed";
                
        // return redirect()->route('newOrder.create')->withInput();;  
     }  
 }
 else{
     
                     $feedback = "Transaction failed to save in the database";
                echo $feedback;
                // event(new TransactionFailed($feedback));
                // return "Transaction failed to save in the database";
 } 
        }
            
        elseif($status == "refunded"){
	    // Transaction refunded
        $UserPayment = UserPayment::create([
            'transaction_ref' => $transaction_ref,
            'user_id' => $user->id,
            'amount' => $amount,
            'currency' => $currency,
            'payment_method' => $payment_method,
            'status' => "Refunded",
            ]);
            
            if($UserPayment){
                 $feedback = "Transaction refunded";
                  echo $feedback;
            }
       
        // event(new TransactionRefunded($feedback));
        // return "Transaction refunded";
	
        }
        else{
	    // Transaction failed
         $UserPayment = UserPayment::create([
            'transaction_ref' => $transaction_ref,
            'user_id' => $user->id,
            'amount' => $amount,
            'currency' => $currency,
            'payment_method' => $payment_method,
            'status' => "Failed",
            ]);
             if($UserPayment){
                $feedback = "Transaction failed";
                  echo $feedback;
            }
        // event(new TransactionFailed($feedback));
        // return "Transaction failed";
        }
    }
    
}
