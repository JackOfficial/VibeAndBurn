<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\admin;
use App\Models\order;
use App\Models\User;
use App\Models\wallet;
use App\Models\subscription;
use App\Models\fund;

class adminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->session()->has('adminId')){
            
             $name = $request->Session()->get('adminName');
            
            $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
         ->join('services', 'orders.service_id', '=', 'services.id')
         ->join('categories', 'services.category_id', '=', 'categories.id')
         ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
         ->count();
         
         $pendingOrders = order::join('users', 'orders.user_id', '=', 'users.id')
         ->join('services', 'orders.service_id', '=', 'services.id')
         ->join('categories', 'services.category_id', '=', 'categories.id')
         ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
         ->where('orders.status', '=', 0)
         ->count();
         
         $usersCounter = User::count();
         
          $walletsCounter = wallet::join('users', 'wallets.user_id', '=', 'users.id')
    ->count();
    
     $walletsTotal = wallet::join('users', 'wallets.user_id', '=', 'users.id')
    ->sum('money');
    
    $subscribersCounter = subscription::count();
    
     $fundsCounter = fund::join('users', 'funds.user_id', '=', 'users.id')
    ->count();
    
    $fundsTotal = fund::join('users', 'funds.user_id', '=', 'users.id')
    ->sum('amount');
  $admin = admin::where('id', session()->get('adminId'))->first();  
 return view('admin.index', compact('name', 'admin', 'ordersCounter', 'usersCounter', 'walletsCounter', 'walletsTotal', 'pendingOrders', 'subscribersCounter', 'fundsCounter', 'fundsTotal'));
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
        if($request->session()->has('adminId')){
             return view('auth.add-admin'); 
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
        $email = $request->email;
        $password = $request->password;

        $admin = admin::where('email', '=', $email)
        ->where('password', '=', $password)
        ->count();
        
        $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
         ->join('services', 'orders.service_id', '=', 'services.id')
         ->join('categories', 'services.category_id', '=', 'categories.id')
         ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
         ->count();
         
         $pendingOrders = order::join('users', 'orders.user_id', '=', 'users.id')
         ->join('services', 'orders.service_id', '=', 'services.id')
         ->join('categories', 'services.category_id', '=', 'categories.id')
         ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
         ->where('orders.status', '=', 0)
         ->count();
         
         $usersCounter = User::count();
         
          $walletsCounter = wallet::join('users', 'wallets.user_id', '=', 'users.id')
    ->count();
    
     $walletsTotal = wallet::join('users', 'wallets.user_id', '=', 'users.id')
    ->sum('money');
    
    $subscribersCounter = subscription::count();
    
     $fundsCounter = fund::join('users', 'funds.user_id', '=', 'users.id')
    ->count();
    
    $fundsTotal = fund::join('users', 'funds.user_id', '=', 'users.id')
    ->sum('amount');
    
        if($admin != 0){

        $info = admin::where('email', '=', $email)
        ->where('password', '=', $password)
        ->get();

        foreach($info as $myinfo){
            $email = $myinfo->email;
            $name = $myinfo->name;
            $request->Session()->put('adminId', $myinfo->id);
            $request->Session()->put('adminName', $name);
        }

  return view('admin.index', compact('email', 'name', 'ordersCounter', 'usersCounter', 'walletsCounter', 'walletsTotal', 'subscribersCounter', 'fundsCounter', 'fundsTotal', 'pendingOrders'));
        }
        else{
            return redirect()->back()->with('loginFail','Login failed');
           
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
        //
    }
}
