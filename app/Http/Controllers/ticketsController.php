<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\wallet;

class TicketsController extends Controller
{
   public function tickets(){
       if(Auth::check()){
           $userID = Auth::user()->id;
         
         $walletCounter = wallet::where('user_id', $userID)->count();
    
    if($walletCounter == 0){
      $wallet = 0;  
    }
    else{
      $wallet = wallet::where('user_id', '=', $userID)->value('money');
    }

       return view('tickets', compact('wallet'));
       }
       else{
         return redirect('login');  
       } 
   }
   
   public function ticket(Request $request){
       
 
        if(Auth::check()){
           $userID = Auth::user()->id;
         
         $walletCounter = wallet::where('user_id', $userID)->count();
    
    if($walletCounter == 0){
      $wallet = 0;  
    }
    else{
      $wallet = wallet::where('user_id', '=', $userID)->value('money');
    }
    $tickedId = $request->id;
       return view('ticket', compact('wallet', 'tickedId'));
       }
       else{
         return redirect('login');  
       } 
   }
   
   public function adminTickets(){
     return view('admin.manage.tickets');
   }
   
   public function adminTicket($id){
       //dd($id);
       $ticketID = $id;
     return view('admin.manage.chat', compact('ticketID'));
   }
   
   
   
}
