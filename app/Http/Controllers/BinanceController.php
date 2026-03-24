<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TechTailor\BinanceApi\BinanceAPI;

class BinanceController extends Controller
{
    protected $binance;

    public function __construct(BinanceAPI $binance)
    {
        $this->binance = $binance;
    }
    
    public function test(){
        $binance = new BinanceAPI();

         $time = $binance->getTime();
          return response()->json($time);
         
    }

    public function getPrice($symbol)
    {
        $price = $this->binance->tickerPrice($symbol);
        return response()->json($price);
    }
    
    public function getAccountBalance()
    {
        // Fetch account information, including balances
        $account = $this->binance->getAccountInfo();

        // You can return the entire account object, or filter it to show only the balance
        return response()->json($account);
    }

    public function placeOrder()
    {
        $order = $this->binance->orderTest('BNBBTC', 'BUY', 'LIMIT', [
            'quantity' => 1,
            'price' => 0.002,
        ]);
        return response()->json($order);
    }
}
