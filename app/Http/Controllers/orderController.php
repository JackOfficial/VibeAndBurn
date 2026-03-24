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
        
        // Ensure charge is handled as a clean float
        $chargeAmount = (float) str_replace(['$', ','], '', $request->charge);

        return DB::transaction(function () use ($request, $user, $service, $chargeAmount) {
            // Lock the wallet for update to prevent race conditions
            $userWallet = Wallet::where('user_id', $user->id)->lockForUpdate()->first();

            if (!$userWallet || (float)$userWallet->money < $chargeAmount) {
                // Throwing exception inside transaction triggers rollback
                throw new \Exception('Insufficient funds.');
            }

            $userWallet->decrement('money', $chargeAmount);

            $order = Order::create([
                'user_id'    => $user->id,
                'service_id' => $service->id,
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
                        $order->update(['orderId' => $apiResponse->order]);
                        
                        // Wrap Mail in try-catch so SMTP errors don't kill the order
                        try {
                            Mail::to("onesphoren8@gmail.com")->send(new confirmOrderMail(
                                $user->name, $user->email, $service->service, $request->link, $request->quantity, $chargeAmount
                            ));
                        } catch (\Exception $e) {
                            Log::warning("Order email failed but order was placed: " . $e->getMessage());
                        }

                        return redirect()->back()->with('addOrderSuccess', "Thank you {$user->name}! Order submitted.");
                    }

                    // API logic failed (e.g. "Incorrect Service ID")
                    $errorReason = $apiResponse->error ?? $apiResponse->message ?? "Provider rejected request.";
                    Log::error("SMM API Failure", ['service_id' => $service->serviceId, 'response' => $apiResponse]);
                    
                    // Rollback manually by throwing exception
                    throw new \Exception("Provider API Error: " . $errorReason);

                } catch (\Exception $e) {
                    Log::error("API Exception: " . $e->getMessage());
                    throw $e; // Re-throw to trigger DB rollback
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