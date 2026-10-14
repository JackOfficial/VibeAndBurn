<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\{Auth, DB, Log};
use App\Services\InTouchPaymentService;
use App\Models\{Wallet, Fund, UserPayment, SharedLink, Bonus};
use Illuminate\Support\Str;

class Checkout extends Component
{
    public $money;
    public $phone;
    public $payment_method = 'momo';
    public $toggleSubmit = 0;

    public function mount()
    {
        // Initialization if needed
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'money' || $propertyName === 'phone') {
            // Enable submit only if money is provided and phone looks valid
            $this->toggleSubmit = (!empty($this->money) && $this->money >= 1 && !empty($this->phone)) ? 1 : 0;
        }
    }

    public function placeOrder(InTouchPaymentService $inTouch)
    {
        $this->validate([
            'money' => 'required|numeric|min:1',
            'phone' => 'required|string|min:9|max:9',
        ], [
            'money.min' => 'The amount cannot be below $1 (or equivalent minimum).',
            'phone.required' => 'Please enter your mobile money phone number.',
            'phone.min' => 'The phone number must be 9 digits.',
            'phone.max' => 'The phone number must be 9 digits.',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $localTransactionId = 'VBN-' . strtoupper(Str::random(10));
            $converted_amount = $this->money;
            $fullPhone = '250' . $this->phone;

            Log::info('[InTouch Payment Requesting]', [
                'phone' => $fullPhone,
                'amount' => $converted_amount,
                'transaction_id' => $localTransactionId
            ]);

            $response = $inTouch->requestPayment($fullPhone, $converted_amount, $localTransactionId);
            
            Log::info('[InTouch Payment Response Received]', ['response' => $response]);

            if ($response && isset($response['success']) && $response['success'] == true) {
                $transactionRef = $response['transactionid'] ?? $localTransactionId;

                // Calculate final amount with your 5% bonus rule if applicable (> 100)
                $finalCreditedAmount = ($converted_amount > 100) ? $converted_amount + ($converted_amount * 5) / 100 : $converted_amount;

                // 1. Credit or Create Wallet
                $wallet = Wallet::where('user_id', $user->id)->first();
                if (!$wallet) {
                    Wallet::create([
                        'user_id' => $user->id,
                        'money' => $finalCreditedAmount,
                    ]);
                } else {
                    $wallet->increment('money', $finalCreditedAmount);
                }

                // 2. Record Fund & User Payment
                Fund::create([
                    'user_id' => $user->id,
                    'method' => $this->payment_method,
                    'amount' => $finalCreditedAmount,
                    'Payedwith' => 'InTouch'
                ]);

                UserPayment::create([
                    'transaction_ref' => $transactionRef,
                    'user_id' => $user->id,
                    'amount' => $converted_amount,
                    'currency' => 'RWF',
                    'payment_method' => $this->payment_method,
                    'status' => "Success",
                ]);

                // 3. Handle Referral Bonus if applicable
                $linkOwner = $user->linkOwner ?? null;
                if ($linkOwner && $user->id != $linkOwner) {
                    $bonus = ($finalCreditedAmount * 5) / 100;

                    SharedLink::create([
                        'user_id' => $user->id,
                        'amount' => $bonus,
                        'descendant' => $linkOwner,
                    ]);

                    $existingBonus = Bonus::where('user_id', $linkOwner)->first();
                    if (!$existingBonus) {
                        Bonus::create([
                            'user_id' => $linkOwner,
                            'bonus' => $bonus,
                            'status' => 3,
                        ]);
                    } else {
                        $existingBonus->increment('bonus', $bonus);
                    }
                }

                DB::commit();
                Log::info("[Wallet Fund Successful & Transaction Committed] User ID: {$user->id}");

                session()->flash('message', "Payment request sent to your phone. Please authorize to complete.");
                return redirect()->route('wallet.success');

            } else {
                $gatewayMessage = $response['message'] ?? $response['error'] ?? 'Gateway Connection Failed';
                throw new \Exception($gatewayMessage);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout API Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('minimunAmount', 'Payment Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}