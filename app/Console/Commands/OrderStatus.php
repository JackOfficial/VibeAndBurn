<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

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
            $query = Order::whereNotIn('status', [1, 2, 5])
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
        // Using double backslashes for the namespace string
        $controllerClass = "App\\Http\\Controllers\\apis\\{$providerName}Controller";
        
        if (!class_exists($controllerClass)) {
            $this->error("Controller $controllerClass not found!");
            return;
        }

        $api = new $controllerClass();
        $externalIds = $orders->pluck('orderId')->toArray();

        try {
            $response = $api->multiStatus($externalIds);

            // Cast to object to ensure the ->{$id} syntax works regardless of API return type
            $responseObj = (object)$response;

            if ($responseObj) {
                foreach ($orders as $order) {
                    $id = $order->orderId;
                    
                    // Accessing dynamic property names like $response->{"12345"}
                    if (isset($responseObj->{$id})) {
                        $data = $responseObj->{$id};
                        
                        if (isset($data->status)) {
                            $newStatus = $this->mapStatus($data->status);

                            $order->update([
                                'status'      => $newStatus,
                                'start_count' => $data->start_count ?? $order->start_count,
                                'remains'     => $data->remains ?? $order->remains,
                            ]);
                        }
                    }
                }
                $this->info("Processed batch for {$providerName}");
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