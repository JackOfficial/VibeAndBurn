<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\fund as Fund;
use App\Models\User;
use App\Models\wallet as Wallet;
use App\Models\BFCurency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FundController extends Controller
{
    /**
     * Display a listing of funds.
     */
    public function index(): View
    {
        return view('admin.funds.index');
    }

    /**
     * Show the form for creating a new fund.
     */
    public function create(): View
    {
        // Don't fetch all users here! That's what causes the lag.
        // We only fetch the rate for Alpine.js to use for real-time math.
        $rateRecord = BFCurency::find(1);
        $bifRate = ($rateRecord && $rateRecord->currency > 0) ? $rateRecord->currency : 1;

        return view('admin.funds.create', compact('bifRate'));
    }

    /**
     * AJAX Search for Select2
     */
    public function searchUsers(Request $request)
    {
        $term = $request->term;

        // Use a simple query to keep it fast
        $users = User::where('name', 'LIKE', "%$term%")
            ->orWhere('email', 'LIKE', "%$term%")
            ->select('id', 'name', 'email')
            ->limit(15) 
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'text' => ucfirst($user->name) . " ({$user->email})"
                ];
            });

        return response()->json($users);
    }

    /**
     * Store a manual payment with BIF/USD conversion logic.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user'     => ['required', 'exists:users,id'],
            'currency' => ['required', 'in:BIF,USD'], 
            'money'    => ['required', 'numeric', 'min:0.01'],
        ]);

        // 1. Determine Conversion Rate
        $amountInUSD = (float) $validated['money'];

        if ($validated['currency'] === "BIF") {
            // Ensure we don't divide by zero if the record is missing
            $rateRecord = BFCurency::find(1);
            $rate = ($rateRecord && $rateRecord->currency > 0) ? $rateRecord->currency : 1;
            
            $amountInUSD = $amountInUSD / $rate;
        }

        try {
            return DB::transaction(function () use ($validated, $amountInUSD) {
                
                // 2. Ensure Wallet exists and update balance
                // Using firstOrCreate is safer than updateOrCreate here
                $wallet = Wallet::firstOrCreate(
                    ['user_id' => $validated['user']],
                    ['money' => 0]
                );
                
                // Atomic increment handles race conditions and triggers mutators
                $wallet->increment('money', $amountInUSD);

                // 3. Create the Fund History Record
                Fund::create([
                    'user_id'   => $validated['user'],
                    'method'    => 'Manual Deposit',
                    'amount'    => $amountInUSD,
                    'Payedwith' => $validated['currency'], 
                ]);

                return redirect()->back()->with('adminAddFundSuccess', 'Funds successfully added to wallet.');
            });

        } catch (\Exception $e) {
            // Log the error if needed: Log::error($e->getMessage());
            return redirect()->back()->with('adminAddFundFail', 'Transaction failed. Please try again.');
        }
    }

    /**
     * Remove the specified fund from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $fund = Fund::findOrFail($id);
        $fund->delete();

        return redirect()->back()->with('deleteFundSuccess', 'Transaction record has been removed.');
    }

    /**
     * Display the specified user's fund history.
     */
    public function show(int $id): View
    {
        $user = User::with(['funds', 'wallet'])->findOrFail($id);
        return view('admin.funds.show', compact('user'));
    }
}