<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\order;
use App\Models\User;
use App\Models\wallet;
use App\Models\subscription;
use App\Models\fund;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        // $admin = Auth::user();
        
        // // Optimized counts using Eloquent
        // $ordersCounter = order::count();
        // $pendingOrders = order::where('status', 0)->count(); // Assuming 0 is pending
        // $usersCounter  = User::count();
        
        // // Wallet Stats
        // $walletsCounter = wallet::count();
        // $walletsTotal   = wallet::sum('money');
        
        // // subscription & Fund Stats
        // $subscribersCounter = subscription::count();
        // $fundsCounter       = fund::count();
        // $fundsTotal         = fund::sum('amount');

        // return view('admin.index', compact(
        //     'admin', 
        //     'ordersCounter', 
        //     'pendingOrders', 
        //     'usersCounter', 
        //     'walletsCounter', 
        //     'walletsTotal', 
        //     'subscribersCounter', 
        //     'fundsCounter', 
        //     'fundsTotal'
        // ));
    }

    /**
     * In Laravel 12 with Fortify, the 'store' (Login) and 'create' (Registration)
     * are usually handled by Fortify's internal controllers. 
     * If you are adding NEW admins manually, use this:
     */
    public function create()
    {
        return view('admin.manage.add-admin');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $newAdmin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => 'active',
        ]);

        $newAdmin->assignRole('Admin');

        return redirect()->route('users.index')->with('success', 'Admin created successfully.');
    }
}