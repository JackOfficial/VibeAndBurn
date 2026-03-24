<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\order;

class OrderStatus extends Command
{
    protected $signature = 'update:status';
    protected $description = 'Batch update order statuses using Multi-Status API calls';

    public function handle()
    {
        $sources = [
            3 => 'Amazing',
            4 => 'Bulkmedya',
            5 => 'Smmsun',
            'default' => 'Bulkfollows' 
        ];

        foreach ($sources as $sourceId => $providerName) {
            // ONLY fetch orders not in a final state (Completed=1, Canceled=2, Partial=5)
            $query = order::whereNotIn('status', [1, 2, 5])
                          ->whereNotNull('orderId');

            if ($sourceId === 'default') {
                $query->whereNotIn('source_id', [3, 4, 5]);
            } else {
                $query->where('source_id', $sourceId);
            }

            // Most APIs limit to 100 per batch
            $orders = $query->limit(100)->get();

            if ($orders->isEmpty()) continue;

            $this->processProviderBatch($providerName, $orders);
        }
    }

    private function processProviderBatch($providerName, $orders)
{
    $controllerClass = "App\\Http\\Controllers\\apis\\{$providerName}Controller";
    
    if (!class_exists($controllerClass)) {
        $this->error("Controller $controllerClass not found!");
        return;
    }

    $api = new $controllerClass();
    $externalIds = $orders->pluck('orderId')->toArray();

    try {
        // Bulkmedya returns an object: { "123": { "status": "Completed", ... } }
        $response = $api->multiStatus($externalIds);

        if ($response) {
            foreach ($orders as $order) {
                $id = $order->orderId;

                // Check if the orderId exists as a property in the object
                if (isset($response->{$id})) {
                    $data = $response->{$id};

                    // Map string (e.g. "Completed") to your DB integer (e.g. 1)
                    $newStatus = $this->mapStatus($data->status);

                    // Update the database record
                    $order->update([
                        'status'      => $newStatus,
                        'start_count' => $data->start_count ?? $order->start_count,
                        'remains'     => $data->remains ?? $order->remains,
                    ]);

                    $this->info("Order #{$order->id} (API ID: $id) updated to $newStatus ({$data->status})");
                } else {
                    $this->warn("Order ID $id not found in {$providerName} response.");
                }
            }
        }
    } catch (\Exception $e) {
        $this->error("API Error with {$providerName}: " . $e->getMessage());
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