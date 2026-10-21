<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{wallet, fund, UserPayment, sharedlink, bonus};
use Illuminate\Support\Facades\{DB, Log};

class InTouchController extends Controller
{
    public function handleCallback(Request $request)
    {
        // 1. Capture and Log the raw hit
        $rawData = $request->all();
        Log::info('InTouch Callback Received:', $rawData);

        /**
         * 2. Handle the "jsonpayload" Wrapper
         */
        $data = isset($rawData['jsonpayload']) ? $rawData['jsonpayload'] : $rawData;

        // 3. Extract variables with flexible fallbacks for keys
        $status = $data['status'] ?? null; 
        $gatewayTransactionId = $data['transactionid'] ?? null; 
        $localRequestId = $data['requesttransactionid'] ?? null; 
        
        // Error description can come in multiple formats
        $errorMessage = $data['statusdesc'] ?? $data['description'] ?? $data['message'] ?? 'Unknown Error';

        if (!$gatewayTransactionId || !$localRequestId) {
            Log::error('InTouch Callback: Missing IDs.', ['payload' => $data]);
            return response()->json([
                'message' => 'Invalid Callback Data',
                'success' => false,
                'request_id' => $localRequestId ?? 'unknown'
            ], 400);
        }

        /**
         * 4. Success Logic
         */
        if (in_array(strtolower($status), ['successfull', 'success'])) {
            
            // Find fund or payment record by request transaction ID / reference
            $userPayment = UserPayment::where('transaction_ref', $localRequestId)
                            ->orWhere('transaction_ref', $gatewayTransactionId)
                            ->first();

            $userId = $userPayment ? $userPayment->user_id : null;

            if (!$userId) {
                Log::error("InTouch Callback: User payment not found for reference {$localRequestId}");
                return response()->json([
                    'message' => 'Transaction reference not found',
                    'success' => false,
                    'request_id' => $localRequestId
                ], 404);
            }

            // Prevent double-crediting if payment is already marked success
            if ($userPayment->status !== 'Success') {
                DB::beginTransaction();
                try {
                    $converted_amount = $userPayment->amount;

                    // Calculate final amount with 5% bonus if amount > 100
                    $finalCreditedAmount = ($converted_amount > 100) 
                        ? $converted_amount + ($converted_amount * 5) / 100 
                        : $converted_amount;

                    // 1. Credit or Create Wallet
                    $walletRecord = wallet::where('user_id', $userId)->first();
                    if (!$walletRecord) {
                        wallet::create([
                            'user_id' => $userId,
                            'money' => $finalCreditedAmount,
                        ]);
                    } else {
                        $walletRecord->increment('money', $finalCreditedAmount);
                    }

                    // 2. Record Fund & Update User Payment status
                    fund::create([
                        'user_id' => $userId,
                        'method' => $userPayment->payment_method ?? 'momo',
                        'amount' => $finalCreditedAmount,
                        'Payedwith' => 'InTouch'
                    ]);

                    $userPayment->update([
                        'transaction_ref' => $gatewayTransactionId,
                        'status' => 'Success'
                    ]);

                    // 3. Handle Referral Bonus if applicable
                    $user = \App\Models\User::find($userId);
                    $linkOwner = $user->linkOwner ?? null;
                    if ($linkOwner && $user->id != $linkOwner) {
                        $referralBonus = ($finalCreditedAmount * 5) / 100;

                        sharedlink::create([
                            'user_id' => $userId,
                            'amount' => $referralBonus,
                            'descendant' => $linkOwner,
                        ]);

                        $existingBonus = bonus::where('user_id', $linkOwner)->first();
                        if (!$existingBonus) {
                            bonus::create([
                                'user_id' => $linkOwner,
                                'bonus' => $referralBonus,
                                'status' => 3,
                            ]);
                        } else {
                            $existingBonus->increment('bonus', $referralBonus);
                        }
                    }

                    DB::commit();
                    Log::info("[InTouch Callback] Wallet successfully credited for User ID: {$userId}");

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('InTouch Callback Processing Exception: ' . $e->getMessage());
                    return "Something went wrong. Please try again!";
                }
            }

            // Retrieve updated wallet balance
            $walletCounter = wallet::where('user_id', '=', $userId)->count();
            $wallet = ($walletCounter == 0) ? 0 : wallet::where('user_id', '=', $userId)->value('money');
            
            $message = $data['statusdesc'] ?? "Transaction has been completed successfully";
            
            return view('payment.pay-with-afripay', compact('message', 'wallet')); 
        }

        // 7. Handle Failed/Canceled Payments
        Log::warning("InTouch Payment failed for Request ID: {$localRequestId}. Status: {$status}");
        return response()->json([
            'message' => $errorMessage,
            'success' => false,
            'request_id' => $localRequestId
        ], 200); 
    }
}