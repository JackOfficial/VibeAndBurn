<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.exchangerates.key');
        $this->baseUrl = config('services.exchangerates.url');
    }

    public function convert($from, $to, $amount)
    {
        $response = Http::get($this->baseUrl . 'convert', [
            'access_key' => $this->apiKey,
            'from' => $from,
            'to' => $to,
            'amount' => $amount,
        ]);

        return $response->json();
    }
}