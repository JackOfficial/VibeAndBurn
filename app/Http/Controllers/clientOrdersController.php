<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\order;
use App\Models\User;
use App\Models\wallet;
use App\Models\fund;
use App\Models\BFCurency;
use Illuminate\Support\Facades\DB;

class clientOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         return view('admin.orders.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $users = User::orderBy('name', 'ASC')->get();
        return view('admin.funds.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $request->validate([
        'user'     => ['required', 'exists:users,id'], // Added exists check for safety
        'currency' => ['required', 'in:BIF,USD'],      // Validates currency immediately
        'money'    => ['required', 'numeric', 'min:0.01'],
    ]);

    $userID   = $request->user;
    $currency = $request->currency;
    $amount   = (float) $request->money;
    
    // 1. Calculate the result based on currency
    if ($currency === "BIF") {
        $bf = BFCurency::where('id', 1)->value('currency') ?: 1; // Avoid division by zero
        $result = $amount / $bf;
    } else {
        $result = $amount; // Since validation limits currency to BIF/USD, else is always USD
    }

    // 2. Use a Database Transaction for data integrity
    return DB::transaction(function () use ($userID, $currency, $result) {
        
        // 3. Update or Create Wallet (Replaces count, sum, and manual update)
        // This handles both the 'first time' deposit and 'subsequent' deposits
        $wallet = Wallet::updateOrCreate(
            ['user_id' => $userID],
            ['money' => 0] // Default if not found
        );
        
        // Use increment for atomic database updates (prevents race conditions)
        $wallet->increment('money', $result);

        // 4. Create Fund Record
        $fund = Fund::create([
            'user_id' => $userID,
            'method'  => $currency,
            'amount'  => $result,
        ]);

        if ($fund) {
            return redirect()->back()->with('adminAddFundSuccess', 'Fund has been added successfully');
        }

        return redirect()->back()->with('adminAddFundFail', 'Fund could not be added');
    });
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
        $orderID = $id;
        return view('admin.orders.edit', compact('orderID'));
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
