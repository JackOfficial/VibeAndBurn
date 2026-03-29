<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\order;

class OrderStatus extends Command
{
    protected $signature = 'update:status';
    protected $description = 'Batch update order statuses and auto-cancel invalid IDs';

    public function handle()
    {
        $sources = [
            3 => 'Amazing',
            4 => 'Bulkmedya',
            5 => 'Smmsun',
            'default' => 'Bulkfollows' 
        ];

        foreach ($sources as $sourceId => $providerName) {
            // Filter: Active orders only, with valid IDs, created in the last 30 days
            $query = order::whereNotIn('status', [1, 2, 5])
                          ->whereNotNull('orderId')
                          ->where('orderId', '!=', '')
                          ->where('created_at', '>', now()->subDays(30));

            if ($sourceId === 'default') {
                $query->whereNotIn('source_id', [3, 4, 5]);
            } else {
                $query->where('source_id', $sourceId);
            }

            $orders = $query->limit(100)->get();

            if ($orders->isEmpty()) continue;

            $this->processProviderBatch($providerName, $orders);
        }
    }

    private function processProviderBatch($providerName, $orders)
{
    $controllerClass = "App\\Http\\Controllers\\apis\\{$providerName}Controller";
    
    if (!class_exists($controllerClass)) {
        $this->error("CRITICAL: Controller $controllerClass not found!");
        return;
    }

    $api = new $controllerClass();
    $externalIds = $orders->pluck('orderId')->toArray();

    try {
        $response = $api->multiStatus($externalIds);

        if (!$response) {
            $this->error("[$providerName] API returned an empty or null response for " . count($externalIds) . " IDs.");
            return;
        }

        foreach ($orders as $order) {
            $id = $order->orderId;

            // REASON 1: ID missing from API response keys
            if (!isset($response->{$id})) {
                $this->warn("MISSING: Order ID $id exists in your DB but was NOT returned by {$providerName} API.");
                continue;
            }

            $data = $response->{$id};

            // REASON 2: API returned an error object for this specific ID
            if (isset($data->error)) {
                $this->error("API ERROR: Order #{$order->id} (API ID: $id) -> " . ($data->error));
                
                if ($data->error === 'Incorrect order ID') {
                    $order->update(['status' => 2]);
                    $this->line("   -> Action: Auto-canceled in local DB.");
                }
                continue;
            }

            // REASON 3: Data is returned but 'status' field is missing or malformed
            if (!is_object($data) || !isset($data->status)) {
                $this->error("DATA MALFORMED: Order ID $id returned invalid structure: " . json_encode($data));
                continue;
            }

            // Optional: Show status mapping issues (if API returns a status not in your match list)
            $mappedStatus = $this->mapStatus($data->status);
            if ($mappedStatus === 0 && strtolower(trim($data->status)) !== 'pending') {
                $this->warn("UNMAPPED STATUS: Order #{$order->id} returned '{$data->status}', which maps to 0 (Default/Pending). Check if this is correct.");
            }

            // Success is silent now...
        }
    } catch (\Exception $e) {
        $this->error("EXCEPTION [{$providerName}]: " . $e->getMessage());
    }
}

    private function mapStatus($statusName)
    {
        $status = strtolower(trim($statusName));
        return match ($status) {
            'completed'             => 1,
            'canceled', 'cancelled' => 2,
            'processing'            => 3,
            'in progress'           => 4,
            'partial'               => 5,
            default                 => 0, 
        };
    }
}