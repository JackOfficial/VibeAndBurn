<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class BulkmedyaController extends Controller
{
    /** API Configuration */
    public $api_url = 'https://bulkmedya.org/api/v2';
    public $api_key;

    public function __construct() {
    $this->api_key = config('services.bulkmedya.key');
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
     * Optimized Request logic using Laravel HTTP Facade
     * Replaces the manual cURL 'connect' function
     */
   private function request($params)
    {
        $params['key'] = $this->api_key;

        try {
            $response = Http::asForm()
                ->timeout(30)
                ->retry(2, 100) // Slightly lower retry to keep user experience fast
                ->withUserAgent('Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)')
                ->post($this->api_url, $params);

            // Even if the API returns an error (400, 422), it usually returns a JSON 
            // error message like {"error": "Insufficient balance"}. 
            // We want to return that object so the Controller can read it.
            return $response->object();

        } catch (\Exception $e) {
            // This only triggers if the website is DOWN or the connection timed out
            return (object) ['error' => 'API Connection Timeout or Provider Offline'];
        }
    }
}