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
            // 1. Skip rows where orderId is NULL or an empty string
            $query = order::whereNotIn('status', [1, 2, 5])
                          ->whereNotNull('orderId')
                          ->where('orderId', '!=', '');

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
            $this->error("Controller $controllerClass not found!");
            return;
        }

        $api = new $controllerClass();
        $externalIds = $orders->pluck('orderId')->toArray();

        try {
            $response = $api->multiStatus($externalIds);

            if ($response) {
                foreach ($orders as $order) {
                    $id = $order->orderId;

                    if (isset($response->{$id})) {
                        $data = $response->{$id};

                        // 2. Validate that data is an object and has a 'status' property
                        // This prevents the "Undefined property: stdClass::$status" error
                        if (is_object($data) && isset($data->status)) {
                            $newStatus = $this->mapStatus($data->status);

                            $order->update([
                                'status'      => $newStatus,
                                'start_count' => $data->start_count ?? $order->start_count,
                                'remains'     => $data->remains ?? $order->remains,
                            ]);

                            $this->info("Order #{$order->id} (API ID: $id) updated to $newStatus ({$data->status})");
                        } else {
                            // API returned the ID but likely with an error message instead of status
                            $this->warn("Order ID $id returned invalid data format from $providerName.");
                        }
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