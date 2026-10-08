<?php

namespace App\Http\Livewire;

use App\Mail\{OrderCallbackAdmin, OrderCallbackClient};
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\{Order, OrderItem, Address, Commission, Part, Shipping};
use Illuminate\Support\Facades\{Auth, DB, Mail, Log, Cookie};
use App\Services\InTouchPaymentService;
use Illuminate\Support\Str;

class Checkout extends Component
{
    public $payment_method = 'momo'; // Options: 'momo' or 'cod'
    public $toggleSubmit = 0;

    public function mount()
    {
        
    }

    public function updated($propertyName)
    {

    }

    private function createOrder($totalOrderAmount, $orderStatus, $localTransactionId)
    {
       // 1. Create the base Order record
        $order = Order::create([
            'user_id'                 => Auth::id(),
            'total_amount'            => $totalOrderAmount,
            'net_total_amount'        => 0, 
            'status'                  => $orderStatus,
            'order_number'            => $localTransactionId, 
        ]);

        return $order;
    }

    public function placeOrder(InTouchPaymentService $inTouch)
    {
        $rules = [
            'payment_method' => 'required|in:momo,cod',
        ];

        $this->validate($rules);

        DB::beginTransaction();
        try {
            $paymentPhone = '';
            $orderStatus = 'pending';

            $localTransactionId = 'AST-' . strtoupper(Str::random(10));
            
            $order = $this->createOrder(500, $orderStatus, $localTransactionId);
        
            Log::info('[InTouch Payment Requesting]', [
                'phone' => $paymentPhone,
                'amount' => 500,
                'transaction_id' => $localTransactionId
            ]);

            $response = $inTouch->requestPayment($paymentPhone, 500, $localTransactionId);
            
            Log::info('[InTouch Payment Response Received]', ['response' => $response]);

            if ($response && isset($response['success']) && $response['success'] == true) {
                $order->update(['transaction_id' => $response['transactionid'] ?? null]);

                DB::commit();
                Log::info("[Order Successful & Transaction Committed] Order ID: {$order->id}");


                $message = ($this->payment_method === 'cod') 
                    ? "Please pay the delivery fee of " . number_format(500) . " RWF on your phone to confirm delivery."
                    : "Payment request sent. Please check your phone.";

                session()->flash('message', $message);
                return redirect()->route('order.success', ['order' => $order->id]);
            } else {
                throw new \Exception($response['message'] ?? "Gateway Connection Failed");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout API Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            dd($e->getMessage(), $e->getFile(), $e->getLine());
            $this->dispatch('notify', message: 'Payment Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}