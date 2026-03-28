<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class BulkfollowsController extends Controller
{
    /** API Configuration */
    public $api_url = 'https://bulkfollows.com/api/v2';
    public $api_key;

    public function __construct() 
    {
        $this->api_key = config('services.bulkfollows.key');
    }

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
     * Optimized Request logic
     */
    private function request($params)
    {
        $params['key'] = $this->api_key;

        try {
            $response = Http::asForm()
                ->timeout(30) 
                ->retry(2, 100) 
                ->withUserAgent('Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)')
                ->post($this->api_url, $params);

            // Returns the provider's JSON as an object (including their error messages)
            return $response->object();

        } catch (\Exception $e) {
            // Catches connection timeouts or DNS issues
            return (object) ['error' => 'Bulkfollows API Connection Timeout'];
        }
    }
}