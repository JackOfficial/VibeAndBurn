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

    // Increased to 90 days to ensure older pending orders are caught
    $this->info("Checking for orders created after: " . now()->subDays(90)->toDateTimeString());

    foreach ($sources as $sourceId => $providerName) {
        $this->comment("--> Checking Provider: $providerName");

        $query = order::whereNotIn('status', [1, 2, 5])
                      ->whereNotNull('orderId')
                      ->where('orderId', '!=', '')
                      ->where('created_at', '>', now()->subDays(90));

        if ($sourceId === 'default') {
            $query->whereNotIn('source_id', [3, 4, 5]);
        } else {
            $query->where('source_id', $sourceId);
        }

        // Use chunk to process EVERYTHING, not just 100
        $count = $query->count();
        if ($count === 0) {
            $this->line("    Result: 0 orders found.");
            continue;
        }

        $this->info("    Result: $count orders found. Processing in batches...");
        
        $query->chunk(100, function ($orders) use ($providerName) {
            $this->processProviderBatch($providerName, $orders);
        });
    }

    $this->info("Command finished execution.");
}

private function processProviderBatch($providerName, $orders)
{
    $this->line("    -> Loading Controller for $providerName...");
    $controllerClass = "App\\Http\\Controllers\\apis\\{$providerName}Controller";
    
    if (!class_exists($controllerClass)) {
        $this->error("    -> ERROR: class $controllerClass does not exist!");
        return;
    }

    try {
        $api = new $controllerClass();
        $externalIds = $orders->pluck('orderId')->toArray();
        
        $this->line("    -> Calling multiStatus for IDs: " . implode(',', $externalIds));
        
        $response = $api->multiStatus($externalIds);

        if (is_null($response)) {
            $this->error("    -> ERROR: API returned NULL. Check your API Key or Connection.");
            return;
        }

        $this->info("    -> API Responded. Analyzing " . count((array)$response) . " keys...");

        foreach ($orders as $order) {
            $id = $order->orderId;

            if (!isset($response->{$id})) {
                $this->warn("    -> ID $id: Missing from API response.");
                continue;
            }

            $data = $response->{$id};

            if (isset($data->error)) {
                $this->error("    -> ID $id: API Error -> " . $data->error);
                continue;
            }

            if (isset($data->status)) {
                $newStatus = $this->mapStatus($data->status);
                
                // FORCE UPDATE CHECK
                $updated = $order->update(['status' => $newStatus]);
                
                if($updated) {
                    $this->info("    -> ID $id: Updated to $newStatus ({$data->status})");
                } else {
                    $this->error("    -> ID $id: Database UPDATE FAILED.");
                }
            }
        }
    } catch (\Throwable $e) { // Use Throwable to catch BOTH Errors and Exceptions
        $this->error("    -> CRITICAL FAILURE in $providerName: " . $e->getMessage());
        $this->error("    -> File: " . $e->getFile() . " on line " . $e->getLine());
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