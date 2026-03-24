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
            'charge'   => 'required',
        ];

        if (!$isSpecialService) {
            $rules['quantity'] = 'required|integer|min:1';
        }

        $request->validate($rules);

        $service = Service::with('category')->findOrFail($request->service);
        $user = Auth::user();
        
        $chargeAmount = (float) str_replace(['$', ','], '', $request->charge);

        return DB::transaction(function () use ($request, $user, $service, $chargeAmount) {
            $userWallet = Wallet::where('user_id', $user->id)->lockForUpdate()->first();

            if (!$userWallet || (float)$userWallet->money < $chargeAmount) {
                throw new \Exception('Insufficient funds.');
            }

            $userWallet->decrement('money', $chargeAmount);

            // UPDATED: Added source_id here to save the provider at the moment of order
            $order = Order::create([
                'user_id'    => $user->id,
                'service_id' => $service->id,
                'source_id'  => $service->source_id, // Link provider to this order
                'link'       => $request->link,
                'quantity'   => $request->quantity ?? 0,
                'comment'    => in_array((int)$service->category_id, self::CUSTOM_COMMENT_CATS) ? $request->comment : null,
                'charge'     => $chargeAmount,
                'status'     => 0 
            ]);

            if ($service->serviceId) {
                try {
                    $apiResponse = $this->sendApiOrder($service, $request);

                    if ($apiResponse && isset($apiResponse->order)) {
                        // Correctly update the provider's external order ID
                        $order->update(['orderId' => $apiResponse->order]);
                        
                        try {
                            Mail::to("onesphoren8@gmail.com")->send(new confirmOrderMail(
                                $user->name, $user->email, $service->service, $request->link, $request->quantity, $chargeAmount
                            ));
                        } catch (\Exception $e) {
                            Log::warning("Order email failed but order was placed: " . $e->getMessage());
                        }

                        return redirect()->back()->with('addOrderSuccess', "Thank you {$user->name}! Order submitted.");
                    }

                    $errorReason = $apiResponse->error ?? $apiResponse->message ?? "Provider rejected request.";
                    Log::error("SMM API Failure", ['service_id' => $service->serviceId, 'response' => $apiResponse]);
                    
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

    private function sendApiOrder($service, $request)
    {
        $params = [
            'service' => (int) $service->serviceId,
            'link'    => (string) $request->link,
        ];

        if (in_array((int)$service->category_id, self::CUSTOM_COMMENT_CATS)) {
            $params['comments'] = (string) $request->comment;
        } 
        elseif ($request->quantity > 0) {
            $params['quantity'] = (int) $request->quantity;
        }

        // Logic uses the source_id from the service model to pick the correct API controller
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