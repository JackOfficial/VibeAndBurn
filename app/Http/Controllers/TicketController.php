<?php

namespace App\Http\Controllers;

use App\Models\wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
public function tickets()
{
    // Use the 'auth' middleware on your route instead of manual if/else for cleaner code
    $user = Auth::user();

    // One query: Get the money value directly. 
    // If no wallet exists, ?? 0 will set the variable to 0 automatically.
    $wallet = Wallet::where('user_id', $user->id)->value('money') ?? 0;

    return view('tickets.index', compact('wallet'));
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
     return view('admin.tickets.index');
   }
   
   public function adminTicket($id){
     return view('admin.tickets.show', compact('id'));
   }

}
