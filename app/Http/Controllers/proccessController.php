<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // Using Laravel's HTTP client
use App\Models\wallet;
use App\Models\service;
use App\Models\category;
use App\Models\order;

class proccessController extends Controller
{
    public function proccess(Request $request)
    {     
        $status = $request->status;
        $txid = $request->transaction_id;

        if ($status == 'cancelled') {
            return redirect('/');
        }

        if ($status == 'failed') {
            return collect(['message' => 'Transaction failed']); // Or redirect with error
        }

        if ($status == 'successful') {
            
            // 1. Verify the transaction with Flutterwave
            // We use the key from .env/config to keep GitHub happy and your site secure
            $response = Http::withToken(config('services.flutterwave.secret_key'))
                ->get("https://api.flutterwave.com/v3/transactions/{$txid}/verify");

            $result = $response->object();

            if ($response->successful() && $result->status == 'success') {
                
                $amountPaid = $result->data->charged_amount;
                $charge = session()->get('charge');

                // 2. Validate the amount matches what we expected
                if ($amountPaid >= $charge) {

                    $serviceId = session()->get('service');
                    $link = session()->get('link');
                    $quantity = session()->get('quantity');
                    $userID = Auth::id();

                    // 3. Create the Order
                    $order = order::create([
                        'user_id' => $userID,
                        'service_id' => $serviceId,
                        'link' => $link,
                        'quantity' => $quantity,
                        'charge' => $charge,
                    ]);

                    if ($order) {
                        // 4. Update Wallet 
                        // Note: Using decrement() is safer for database consistency
                        $walletUpdate = wallet::where('user_id', $userID)
                            ->decrement('money', $charge);

                        if ($walletUpdate) {
                            return redirect('/dashboard')->with('addOrderSuccess', 'Order has been submitted successfully');
                        }
                    } else {
                        return redirect()->back()->with('addOrderFail', 'Order could not be submitted');
                    }

                } else {
                    return "Fraudulent Transaction Detected";
                }
            } else {
                return "We could not process your payment verification";
            }
        } else {
            return "There is something wrong with your payment status";
        }
    }
}