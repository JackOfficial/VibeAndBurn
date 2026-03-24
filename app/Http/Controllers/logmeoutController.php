<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class logmeoutController extends Controller
{
   public function logmeout(Request $request){
     $request->session()->forget('adminName');
     return view('auth.admin-login');
   }
}

