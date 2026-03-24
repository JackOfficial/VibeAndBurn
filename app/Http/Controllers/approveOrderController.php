<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\order;
use App\Mail\confirmOrderMail;
use Illuminate\Support\Facades\Mail;

class approveOrderController extends Controller
{
   public function approve($id){
    $order = order::where('id', '=', $id)->update([
    'status' => 1
    ]);
    if($order){
      return redirect()->back()->with('approveOrderSuccess','Order has been initiated');  
    }
    else{
        return redirect()->back()->with('approveOrderFail','Order could not be approved');
    }
   }
}
