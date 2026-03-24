<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\fund;
use App\Models\wallet;
use App\Models\category;
use App\Models\order;
use App\Models\sharedlink;
use App\Models\bonus;

class proccessPaymentController extends Controller
{
    public function proccessPayment(Request $request)
    {
        $status = $request->status;
        $txid = $request->transaction_id;

        if ($status == 'cancelled') {
            return redirect('/');
        }

        if ($status == 'failed') {
            return redirect()->route('add-fund.index')->with('addFundFail', 'Transaction failed');
        }

        if ($status == 'successful') {
            
            // 1. Securely verify the transaction using the key from .env
            $response = Http::withToken(config('services.flutterwave.secret_key'))
                ->get("https://api.flutterwave.com/v3/transactions/{$txid}/verify");

            $result = $response->object();

            if ($response->successful() && $result->status == 'success') {
                $userID = Auth::id();
                $amountInDollar = session()->get('amountInDollar');

                // 2. Update or Create Wallet
                // Using updateOrCreate is cleaner for "if wallet exists" logic
                wallet::updateOrCreate(
                    ['user_id' => $userID],
                    ['money' => \DB::raw("money + $amountInDollar")]
                );

                // 3. Record the Funding
                $fund = fund::create([
                    'user_id' => $userID,
                    'method' => $result->data->payment_type,
                    'amount' => $amountInDollar,
                    'Payedwith' => 'Flutterwave'
                ]);

                if ($fund) {
                    $linkOwner = Auth::user()->linkOwner;

                    if ($linkOwner != NULL) {
                        $bonusAmount = ($amountInDollar * 5) / 100;

                        // Set bonuses record
                        sharedlink::create([
                            'user_id' => $userID,
                            'amount' => $bonusAmount,
                            'descendant' => $linkOwner,
                        ]);

                        // Update total bonus
                        bonus::updateOrCreate(
                            ['user_id' => $userID],
                            ['bonus' => \DB::raw("bonus + $bonusAmount")]
                        );
                    }

                    return redirect()->route('newOrder.create')->withInput();
                }

                $wallet = wallet::where('user_id', $userID)->get();
                return view('add-fund', compact('wallet'));
            } else {
                $userID = Auth::id();
                $wallet = wallet::where('user_id', $userID)->get();
                return view('add-fund', compact('wallet'))->with('addFundFail', 'We could not process your payment');
            }
        } else {
            $userID = Auth::id();
            $wallet = wallet::where('user_id', $userID)->get();
            return view('add-fund', compact('wallet'))->with('addFundFail', 'There is something wrong with your payment');
        }
    }
}