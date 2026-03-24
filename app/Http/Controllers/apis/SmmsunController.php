<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class SmmsunController extends Controller
{
    /** API Configuration */
    public $api_url = 'https://smmsun.com/api/v2';
    public $api_key = 'fbe137dacccef54c4cedae739effd10e';

    /** Add order */
    public function order($data)
    {
        return $this->request(['action' => 'add'] + $data);
    }

    /** Get order status */
    public function status($order_id)
    {
        return $this->request(['action' => 'status', 'order' => $order_id]);
    }

    /** Get orders status */
    public function multiStatus($order_ids)
    {
        return $this->request([
            'action' => 'status', 
            'orders' => implode(",", (array)$order_ids)
        ]);
    }

    /** Get services */
    public function services()
    {
        return $this->request(['action' => 'services']);
    }

    /** Refill order */
    public function refill(int $orderId)
    {
        return $this->request(['action' => 'refill', 'order' => $orderId]);
    }

    /** Refill orders */
    public function multiRefill(array $orderIds)
    {
        return $this->request(['action' => 'refill', 'orders' => implode(',', $orderIds)]);
    }

    /** Get refill status */
    public function refillStatus(int $refillId)
    {
         return $this->request(['action' => 'refill_status', 'refill' => $refillId]);
    }

    /** Get refill statuses */
    public function multiRefillStatus(array $refillIds)
    {
         return $this->request(['action' => 'refill_status', 'refills' => implode(',', $refillIds)]);
    }

    /** Cancel orders */
    public function cancel(array $orderIds)
    {
        return $this->request(['action' => 'cancel', 'orders' => implode(',', $orderIds)]);
    }

    /** Get balance */
    public function balance()
    {
        return $this->request(['action' => 'balance']);
    }

    /**
     * Optimized Centralized Request logic
     * Replaces manual cURL and handles JSON decoding automatically
     */
    private function request($params)
    {
        $params['key'] = $this->api_key;

        $response = Http::asForm()
            ->timeout(30)   // Safety for Namecheap process limits
            ->retry(3, 200) // Handles temporary API "hiccups"
            ->withUserAgent('Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)')
            ->post($this->api_url, $params);

        if ($response->successful()) {
            return $response->object(); 
        }

        return false;
    }
}