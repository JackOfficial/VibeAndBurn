<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http; // Use this instead of curl
use App\Models\Order; // Assuming you have an Order model
use Illuminate\Support\Facades\Log;

class Bulkmedya extends Command
{
    protected $signature = 'run:bulkmedya';
    protected $description = 'Process pending orders to Bulkmedya API';

    private $api_url = 'https://bulkmedya.org/api/v2';
    private $api_key = '8f75c5fd9f190968c49189e34523ca68';

    public function handle()
    {
        // 1. Fetch only pending orders
        $orders = Order::where('status', 'pending')
                       ->where('provider', 'bulkmedya')
                       ->limit(10) // Process in small batches
                       ->get();

        foreach ($orders as $order) {
            // 2. IMMEDIATE LOCK: Change status so another process won't grab it
            $order->update(['status' => 'processing']);

            $response = $this->order([
                'service' => $order->service_id,
                'link'    => $order->link,
                'quantity'=> $order->quantity,
                // Add runs/interval if needed for drip-feed
            ]);

            if (isset($response->order)) {
                // SUCCESS: Save the external ID from Bulkmedya
                $order->update([
                    'status' => 'inprogress',
                    'service_id' => $response->order,
                    'processing_at' => now()
                ]);
                $this->info("Order {$order->id} sent successfully.");
            } else {
                // FAILURE: Rollback to pending or mark as error
                $errorMessage = $response->error ?? 'Unknown API Error';
                $order->update(['status' => 'error', 'notes' => $errorMessage]);
                Log::error("Bulkmedya Fail: " . $errorMessage);
            }
        }

        return 0;
    }

    /** * Modernized Connection using Laravel Http Client
     */
    private function connect($params)
    {
        try {
            $response = Http::asForm()
                ->timeout(30) // Stop waiting after 30 seconds
                ->post($this->api_url, array_merge(['key' => $this->api_key], $params));

            if ($response->successful()) {
                return $response->object();
            }
            
            return (object)['error' => 'HTTP Connection Failed'];
        } catch (\Exception $e) {
            return (object)['error' => $e->getMessage()];
        }
    }

    public function order($data)
    {
        return $this->connect(array_merge(['action' => 'add'], $data));
    }
}
