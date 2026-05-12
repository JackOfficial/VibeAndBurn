<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\fund as Fund;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FundController extends Controller
{
    /**
     * Display the parent view containing the Livewire component.
     */
    public function index()
    {
        return view('admin.funds.index');
    }

    /**
     * Store a manual payment and update user wallet.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'amount'    => 'required|numeric|min:0',
            'method'    => 'required|string|max:50',
            'Payedwith' => 'nullable|string|max:100',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // 1. Create the Fund record
                Fund::create($validated);

                // 2. Update the User's Wallet
                $user = User::findOrFail($validated['user_id']);
                
                // Retrieve or create wallet
                $wallet = $user->wallet ?: $user->wallet()->create(['money' => 0]);
                
                // Increment triggers your 'setMoneyAttribute' mutator automatically
                $wallet->increment('money', $validated['amount']);
            });

            return redirect()->back()->with('success', 'Funds added successfully and wallet updated.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Remove the fund record.
     */
    public function destroy($id)
    {
        $fund = Fund::findOrFail($id);
        $fund->delete();

        // Standardized session key to match your Blade alert
        return redirect()->back()->with('deleteFundSuccess', 'Transaction record has been removed.');
    }

    /**
     * Helper for Admin to see specific user history
     */
    public function show($id)
    {
        $user = User::with(['funds', 'wallet'])->findOrFail($id);
        return view('admin.funds.show', compact('user'));
    }
}