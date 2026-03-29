<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\order;      // Capitalized
use App\Models\User;
use App\Models\wallet;     // Capitalized
use App\Models\subscription; // Capitalized
use App\Models\fund;       // Capitalized
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $admin = Auth::user();

        // 1. High-Level Summary Stats (Using a single optimized query)
        $orderStats = order::selectRaw('
            count(*) as total, 
            count(case when status = "pending" or status = "0" then 1 end) as pending,
            sum(case when status = "completed" then charge else 0 end) as total_revenue
        ')->first();

        // 2. User & Subscription Stats
        $usersCounter = User::count();
        $subscribersCounter = subscription::count();

        // 3. Financial Stats (Wallets vs Funds)
        $walletsTotal = wallet::sum('money');
        $fundsTotal   = fund::sum('amount');
        $fundsCounter = fund::count();

        // 4. Chart Data: Last 6 Months Revenue
        // We use an array map to ensure we have a clean dataset for Chart.js
        $monthlyRevenue = order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->selectRaw('MONTHNAME(created_at) as month, SUM(charge) as amount, MONTH(created_at) as month_num')
            ->groupBy('month', 'month_num')
            ->orderBy('month_num')
            ->get();

        // 5. Recent Activity (Professional dashboards always show the "latest")
        $recentOrders = order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // 6. User Growth (New users this week vs total)
        $newUsersThisWeek = User::where('created_at', '>=', Carbon::now()->startOfWeek())->count();

        return view('admin.index', compact(
            'admin', 
            'orderStats', 
            'usersCounter', 
            'walletsTotal', 
            'subscribersCounter', 
            'fundsTotal',
            'fundsCounter',
            'monthlyRevenue',
            'recentOrders',
            'newUsersThisWeek'
        ));
    }

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
            'role'   => 'admin', // Ensure your migration supports this or use Spatie
        ]);

        // If using Spatie Permission:
        // $newAdmin->assignRole('Admin');

        return redirect()->route('admin.users.index')->with('success', 'Admin created successfully.');
    }
}