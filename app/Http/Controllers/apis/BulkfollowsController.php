<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class BulkfollowsController extends Controller
{
    /** API Configuration */
    public $api_url = 'https://bulkfollows.com/api/v2';
    public $api_key = '1b87dc445d0c29c2c2f30c7899adfc89';

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
     * Optimized Request logic using Laravel HTTP Facade
     * Replaces the manual cURL 'connect' function
     */
    private function request($params)
    {
        $params['key'] = $this->api_key;

        $response = Http::asForm()
            ->timeout(30) // Prevents script hanging on Namecheap shared hosting
            ->retry(3, 200) // Tries 3 times if there is a network glitch
            ->withUserAgent('Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)')
            ->post($this->api_url, $params);

        if ($response->successful()) {
            return $response->object(); // Returns as a PHP object
        }

        return false;
    }
}