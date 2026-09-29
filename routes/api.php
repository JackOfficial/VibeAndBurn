<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AfriPay\ProcessPaymentController;
use App\Http\Controllers\Payment\InTouchController;
use App\Http\Controllers\Payment\InTouchDepositController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * InTouchPay Callback Route
 * URL: /api/payments/intouch/callback
 * This route is automatically exempt from CSRF protection.
 */
Route::post('/payments/intouch/callback', [InTouchController::class, 'handleCallback'])
    ->name('api.payments.intouch.callback');

Route::middleware(['auth'])->group(function () {
    
    // Route to trigger the payout
    Route::post('/payments/intouch/payout', [InTouchDepositController::class, 'processPayout'])
         ->name('api.payments.intouch.payout');
});   

Route::middleware(['auth', 'role:admin|super-admin'])->prefix('admin')->name('admin.')->group(function () {
     // Check current balance   
    Route::get('/payments/intouch/balance', [InTouchDepositController::class, 'checkBalance'])
         ->name('payments.intouch.balance');  
});

Route::get('/test-callback/{orderNumber}', function ($orderNumber) {
    $request = new Request([
        'jsonpayload' => [ // Added the wrapper to match real InTouch behavior
            'status' => 'Successfull',
            'transactionid' => 'FAKE_' . time(),
            'requesttransactionid' => $orderNumber,
            'amount' => 100,
            'currency' => 'RWF'
        ]
    ]);

    return (new InTouchController())->handleCallback($request);
});  

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/process-payment', [ProcessPaymentController::class, 'process']);