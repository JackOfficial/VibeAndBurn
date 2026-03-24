<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // Use Laravel's HTTP client instead of CURL

class paymentController extends Controller
{
    public function payment(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'deriver' => 'required',
            'phone' => 'required|string',
            'address' => 'required',
            'city' => 'required',
            'residence' => 'required',
            'dog_id' => 'required',
            'price' => 'required|numeric',
            'currency' => 'required',
            'payment_method' => 'required'
        ]);

        if ($request->has('setDefault')) {
            User::where('id', Auth::id())->update([
                'deriver' => $request->deriver,
                'phone' => $request->phone,
                'city' => $request->city,
                'street_address' => $request->address,
                'house_number' => $request->residence,
            ]);
        }

        // Logic for delivery fee
        $deliveryFee = ($request->deriver == "Kigali") ? 1500 : 3000;
        $totalAmount = $request->price + $deliveryFee;

        // Store in session
        session([
            'amountToPay' => $totalAmount,
            'contact_name' => $request->name,
            'deriver' => $request->deriver,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'residence' => $request->residence,
            'dog_id' => $request->dog_id,
            'currency' => $request->currency,
            'payment_method' => $request->payment_method,
            'comment' => $request->comment,
        ]);

        // Prepare Flutterwave Data
        $paymentData = [
            'tx_ref' => 'FLW_' . time(),
            'amount' => $totalAmount, // Using the calculated total instead of hardcoded 100
            'currency' => $request->currency,
            'payment_options' => $request->payment_method,
            'redirect_url' => route('process.dog'), // Use named routes for cleaner code
            'customer' => [
                'email' => Auth::user()->email,
                'name' => Auth::user()->name
            ],
            'customizations' => [
                'title' => 'Foxx-Kennels Payment',
                'description' => 'Expert In Pethood'
            ]
        ];

        // Using Laravel's HTTP Client (much cleaner than CURL)
        $response = Http::withToken(config('services.flutterwave.secret_key'))
            ->post('https://api.flutterwave.com/v3/payments', $paymentData);

        $res = $response->object();

        if ($response->successful() && $res->status == 'success') {
            return redirect($res->data->link);
        }

        return back()->with('error', 'We cannot process your payment at this time.');
    }
}