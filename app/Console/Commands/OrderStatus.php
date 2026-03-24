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

                        // Check if data is valid and contains a status
                        if (is_object($data) && isset($data->status)) {
                            $newStatus = $this->mapStatus($data->status);

                            $order->update([
                                'status'      => $newStatus,
                                'start_count' => $data->start_count ?? $order->start_count,
                                'remains'     => $data->remains ?? $order->remains,
                            ]);

                            $this->info("Order #{$order->id} (API ID: $id) updated to $newStatus ({$data->status})");
                        } else {
                            $this->warn("Order ID $id returned invalid data: " . json_encode($data));
                            
                            // If the provider explicitly says the ID is wrong, mark as Canceled (2) 
                            // to stop checking it in future cron runs.
                            if (isset($data->error) && $data->error === 'Incorrect order ID') {
                                $order->update(['status' => 2]);
                                $this->error("Order #{$order->id} auto-canceled: Incorrect API ID.");
                            }
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