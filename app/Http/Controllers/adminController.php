<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\order;      
use App\Models\User;
use App\Models\wallet;     
use App\Models\subscription; 
use App\Models\fund;       
use App\Models\Ticket;
use App\Models\Support; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    /**
     * Display the Executive Dashboard with live stats.
     */
    public function index()
    {
        $admin = Auth::user();

        // 1. Order & Revenue Stats (Optimized single query)
        $orderStats = order::selectRaw('
            count(*) as total, 
            count(case when status = "pending" or status = "0" then 1 end) as pending,
            sum(case when status = "completed" then charge else 0 end) as total_revenue
        ')->first();

        // 2. User & Subscription Stats
        $usersCounter = User::count();
        $subscribersCounter = subscription::count();
        $newUsersThisWeek = User::where('created_at', '>=', Carbon::now()->startOfWeek())->count();

        // 3. Financial Stats (Wallets vs Funds)
        $walletsTotal = wallet::sum('money');
        $fundsTotal   = fund::sum('amount');
        $fundsCounter = fund::count();

        // 4. Chart Data: Last 6 Months Revenue
        $monthlyRevenue = order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->selectRaw('MONTHNAME(created_at) as month, SUM(charge) as amount, MONTH(created_at) as month_num')
            ->groupBy('month', 'month_num')
            ->orderBy('month_num')
            ->get();

        // 5. Recent Activity: Orders
        $recentOrders = order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // 6. Support Tickets Logic
        $openTicketsCount = Ticket::whereIn('status', ['open', 'pending'])->count();
        
        $recentTickets = Ticket::with('user')
            ->latest()
            ->take(5)
            ->get();

        // 7. Recent Message Stream (Replies/Interactions)
        $recentMessages = Support::with('user')
            ->latest()
            ->take(5)
            ->get();

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
            'newUsersThisWeek',
            'openTicketsCount',
            'recentTickets',
            'recentMessages'
        ));
    }

    /**
     * Show form to create a new administrator.
     */
    public function create()
    {
        return view('admin.manage.add-admin');
    }

    /**
     * Store a new administrator in the system.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => 'active',
            'role'   => 'admin', 
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Admin created successfully.');
    }
}