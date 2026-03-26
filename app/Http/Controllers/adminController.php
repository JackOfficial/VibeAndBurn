<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Subscription;
use App\Models\Fund;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Use a constructor to ensure only Admins can access these methods.
     * This replaces your manual if(session()->has('adminId')) checks.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin|Super Admin']);
    }

    /**
     * Display the Admin Dashboard with Stats
     */
    public function index()
    {
        $admin = Auth::user();
        
        // Optimized counts using Eloquent
        $ordersCounter = Order::count();
        $pendingOrders = Order::where('status', 0)->count(); // Assuming 0 is pending
        $usersCounter  = User::count();
        
        // Wallet Stats
        $walletsCounter = Wallet::count();
        $walletsTotal   = Wallet::sum('money');
        
        // Subscription & Fund Stats
        $subscribersCounter = Subscription::count();
        $fundsCounter       = Fund::count();
        $fundsTotal         = Fund::sum('amount');

        return view('admin.index', compact(
            'admin', 
            'ordersCounter', 
            'pendingOrders', 
            'usersCounter', 
            'walletsCounter', 
            'walletsTotal', 
            'subscribersCounter', 
            'fundsCounter', 
            'fundsTotal'
        ));
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