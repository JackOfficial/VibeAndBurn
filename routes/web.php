<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\pagesController;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\socialmediaController;
use App\Http\Controllers\serviceController;
use App\Http\Controllers\ourServicesController;
use App\Http\Controllers\contactusController;
use App\Http\Controllers\faqController;
use App\Http\Controllers\aboutUsController;
use App\Http\Controllers\subscriptionController;
use App\Http\Controllers\addFundController;
use App\Http\Controllers\newOrderController;
use App\Http\Controllers\ajaxController;
use App\Http\Controllers\orderController;
use App\Http\Controllers\clientOrdersController;
use App\Http\Controllers\approveOrderController;
use App\Http\Controllers\faqsController;
use App\Http\Controllers\broadcastController;
use App\Http\Controllers\usersController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\proccessController;
use App\Http\Controllers\proccessPaymentController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\logmeoutController;
use App\Http\Controllers\walletController;
use App\Http\Controllers\sharedlinkController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\BFCurrencyController;
use App\Http\Controllers\admin\UpdatesController;
use App\Http\Controllers\admin\AdvertsController;
use App\Events\PaymentProcessed;
use App\Http\Controllers\AfriPay\ProcessPaymentController;
use App\Http\Controllers\BinanceController;
use App\Http\Controllers\WholesalersController;
use App\Http\Controllers\admin\RateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//---------------------------- USER --------------------------------

Route::get('/', [pagesController::class, 'index']);
Route::get('/terms-and-conditions', [pagesController::class, 'terms']);

Route::resource('our-services', ourServicesController::class);
Route::resource('contactus', contactusController::class);
Route::resource('faq', faqController::class);
Route::resource('aboutus', aboutUsController::class);
Route::resource('subscription', subscriptionController::class);

Route::get('/testBulkmedya', [orderController::class, 'testBulkmedya']); //for testing purpose
Route::resource('faqs', faqsController::class);
Route::get('/sharelink/{id}', [pagesController::class, 'sharelink'])->name('sharelink');
Route::get('payment', [paymentController::class, 'payment'])->name('payment');
Route::get('proccess', [proccessController::class, 'proccess'])->name('proccess');
Route::get('proccessPayment', [proccessPaymentController::class, 'proccessPayment'])->name('proccessPayment');
Route::post('bulkfollow-order', function(){
   echo "redirected";
});
Route::get('/getServices/{id}', [ajaxController::class, 'getServices']);
Route::get('/getPrice/{id}', [ajaxController::class, 'getPrice']);
Route::get('/ticket/{id}', [TicketController::class, 'ticket']);
Route::post('sendTicket', [TicketController::class, 'sendTicket']);

Route::middleware(['auth'])->group(function () {
    // This is the missing route for your "Back to List" button
    Route::get('/tickets', [TicketController::class, 'tickets'])->name('user.tickets.list');
    Route::get('/home', [pagesController::class, 'dashboard']);
    Route::resource('addFund', addFundController::class);
    Route::resource('newOrder', newOrderController::class);
    Route::resource('profile', ProfilesController::class);
    Route::resource('order', orderController::class);
    Route::get('/terms-police', [pagesController::class, 'termsAndConditions']);
    Route::post('/payInRwanda', [pagesController::class, 'payInRwanda']);
    Route::get('/payInBurundi', [pagesController::class, 'payInBurundi']);
    Route::get('/payInTether', [pagesController::class, 'payInTether']);
    Route::get('ourservices', [pagesController::class, 'services'])->name('ourservices');
    Route::get('/mybonus', [sharedlinkController::class, 'mybonus'])->name('mybonus');
    Route::get('/cleambonus', [sharedlinkController::class, 'cleambonus'])->name('cleambonus');
    Route::get('/testOrder', [orderController::class, 'testOrder']);
    
    // This is the route for the individual ticket view we just built
    Route::get('/support/ticket/{id}', function ($id) {
        return view('tickets.show', ['ticketId' => $id]);
    })->name('user.tickets.view');
});

//---------------------------- SOCIALITE ROUTES --------------------------------
Route::get('redirect', [SocialController::class, 'redirect']);
Route::get('callback', [SocialController::class, 'callback']);

//Binance APIs
Route::get('/binance/price/{symbol}', [BinanceController::class, 'getPrice']);
Route::post('/binance/order', [BinanceController::class, 'placeOrder']);
Route::get('/binance/account/balance', [BinanceController::class, 'getAccountBalance']);

Route::get('/binance/test', [BinanceController::class, 'test']);
//.............................. Payment Controller ......................................
Route::get('/afripay-callback', [ProcessPaymentController::class, 'pay'])->name('afripay.callback');

//---------------------------- ADMIN ---------------------------------

Route::middleware(['auth', 'role:Admin|Super Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('dashboard', AdminController::class);
    Route::resource('wholesalers', WholesalersController::class);
    Route::get('/tickets', [TicketController::class, 'adminTickets'])->name('tickets');
    Route::get('/ticket/{id}', [TicketController::class, 'adminTicket'])->name('ticket.show');
    Route::get('/fund', [pagesController::class, 'fund'])->name('fund');
    Route::get('/rate', [RateController::class, 'index']);
    Route::get('/referrals/{id}', [sharedlinkController::class, 'referral'])->name('referrals');
    Route::get('/offer/bonus/{id}', [sharedlinkController::class, 'bonus'])->name('bonus');
    Route::resource('wallet', walletController::class);
    Route::resource('advert', AdvertsController::class);
    Route::get('/bfcurrency', [BFCurrencyController::class, 'index'])->name('bfcurrency');
    Route::resource('sharedlink', sharedlinkController::class);
});


Route::resource('update', UpdatesController::class);
Route::resource('socialmedia', socialmediaController::class);
Route::resource('category', categoryController::class);
Route::resource('service', serviceController::class);
Route::get('/toggle-service/{id}', [serviceController::class, 'toggler']);

Route::resource('clientOrders', clientOrdersController::class);
Route::resource('broadcast', broadcastController::class);
Route::resource('users', usersController::class);
Route::get('approve/{id}', [approveOrderController::class, 'approve'])->name('approve');
Route::get('/logmeout', [logmeoutController::class, 'logmeout'])->name('logmeout');
Route::resource('terms', TermsController::class);
Route::get('/event', function(){
event(new PaymentProcessed("Hello world"));
echo "Event has been broadcast";
});

Route::get('/listener', function(){
    return view('listener');
    });