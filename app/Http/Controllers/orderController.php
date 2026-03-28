<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Mail, DB, Log};
use App\Mail\confirmOrderMail;
use App\Models\{order, wallet, service, admin};
use App\Notifications\NewOrder;
use App\Http\Controllers\apis\{BulkmedyaController, AmazingController, BulkfollowsController, SmmsunController};

class OrderController extends Controller
{
    const CUSTOM_COMMENT_CATS = [115, 127, 128, 129, 130, 131, 132, 133];

    public function index()
    {
        $wallet = Wallet::where('user_id', Auth::id())->value('money') ?? 0;
        return view('orders', compact('wallet'));
    }

    public function store(Request $request)
    {
        $isSpecialService = ($request->category == 11 && $request->service == 90);
        
        $rules = [
            'category' => 'required',
            'service'  => 'required|exists:services,id',
            'link'     => 'required|url',
        ];

        if (!$isSpecialService) {
            $rules['quantity'] = 'required|integer|min:1';
        }

        $request->validate($rules);

        $service = Service::with('category')->findOrFail($request->service);
        $user = Auth::user();

        return DB::transaction(function () use ($request, $user, $service, $isSpecialService) {
            
            // 1. Recalculate Charge internally for security
            $quantity = $isSpecialService ? 1 : (int)$request->quantity;
            $chargeAmount = ($service->rate_per_1000 * $quantity) / 1000;
            
            // 2. Lock wallet and check funds
            $userWallet = Wallet::where('user_id', $user->id)->lockForUpdate()->first();

            if (!$userWallet || (float)$userWallet->money < $chargeAmount) {
                throw new \Exception('Insufficient funds.');
            }

            // 3. Create Order (Triggering OrderObserver to deduct money)
            $order = Order::create([
                'user_id'    => $user->id,
                'service_id' => $service->id,
                'source_id'  => $service->source_id,
                'link'       => $request->link,
                'quantity'   => $quantity,
                'comment'    => in_array((int)$service->category_id, self::CUSTOM_COMMENT_CATS) ? $request->comment : null,
                'charge'     => $chargeAmount,
                'status'     => 0 
            ]);

            // 4. Handle External API Providers
            if ($service->serviceId) {
                try {
                    $apiResponse = $this->sendApiOrder($service, $request);

                    if ($apiResponse && isset($apiResponse->order)) {
                        $order->update(['orderId' => $apiResponse->order]);
                        
                        try {
                            Mail::to("onesphoren8@gmail.com")->send(new confirmOrderMail(
                                $user->name, $user->email, $service->service, $request->link, $quantity, $chargeAmount
                            ));
                        } catch (\Exception $e) {
                            Log::warning("Order email failed: " . $e->getMessage());
                        }

                        return redirect()->back()->with('addOrderSuccess', "Thank you {$user->name}! Order submitted.");
                    }

                    $errorReason = $apiResponse->error ?? $apiResponse->message ?? "Provider rejected request.";
                    Log::error("SMM API Failure", ['service_id' => $service->serviceId, 'response' => $apiResponse]);
                    
                    // Triggering Exception rolls back the money deduction & deletes the order
                    throw new \Exception("Provider API Error: " . $errorReason);

                } catch (\Exception $e) {
                    Log::error("API Exception: " . $e->getMessage());
                    throw $e; 
                }
            }

            Admin::find(1)->notify(new NewOrder());
            return redirect()->back()->with('addOrderSuccess', 'Manual order received. Thank you!');

        }, 5);
    }

    /**
     * Send order to provider. Public so Livewire can access it.
     */
    public function sendApiOrder($service, $request)
    {
        $params = [
            'service' => (int) $service->serviceId,
            'link'    => (string) $request->link,
        ];

        if (in_array((int)$service->category_id, self::CUSTOM_COMMENT_CATS)) {
            $params['comments'] = (string) $request->comment;
        } 
        elseif (isset($request->quantity) && $request->quantity > 0) {
            $params['quantity'] = (int) $request->quantity;
        }

        return match ((int)$service->source_id) {
            3 => (new AmazingController())->order($params),
            4 => (new BulkmedyaController())->order($params),
            5 => (new SmmsunController())->order($params),
            default => (new BulkfollowsController())->order($params),
        };
    }

    public function show($id)
    {
        return DB::transaction(function () use ($id) {
            $order = Order::lockForUpdate()->findOrFail($id);

            if ($order->status == 2) {
                return redirect()->back()->with('approveOrderFail', 'Already reversed.');
            }

            Wallet::where('user_id', $order->user_id)->increment('money', (float)$order->charge);
            $order->update(['status' => 2]);

            return redirect()->back()->with('approveOrderSucces', 'Order reversed and funds returned!');
        });
    }

}
