<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\service as our_service;
use App\Models\Rate;
use App\Http\Controllers\apis\BulkmedyaController;
use App\Http\Controllers\apis\AmazingController;
use App\Http\Controllers\apis\BulkfollowsController;
use App\Http\Controllers\apis\SmmsunController;
use Illuminate\Support\Facades\Log;

class UpdatePrice extends Command
{
    protected $signature = 'update:price';
    protected $description = 'Update Services Price using efficient key-mapping';

    public function handle()
{
    $apis = [
        2 => new BulkfollowsController(), // Corrected ID to match your previous logic
        3 => new AmazingController(),
        4 => new BulkmedyaController(),
        5 => new SmmsunController(),
    ];

    // Fetch only active services that have an external ID
    $services = our_service::where('status', 1) // Changed 'state' to 'status' if that's your column name
        ->whereNotNull('serviceId')
        ->where('serviceId', '!=', '')
        ->get();

    if ($services->isEmpty()) {
        $this->warn("No active services found.");
        return 0;
    }

    $rateSetting = Rate::first();
    $margin = $rateSetting ? $rateSetting->rate : 1;

    $providerData = [];
    foreach ($apis as $id => $api) {
        try {
            $response = $api->services();
            // Ensure we have a valid collection
            $providerData[$id] = collect($response)->keyBy('service');
        } catch (\Exception $e) {
            $this->error("Provider {$id} Error: " . $e->getMessage());
        }
    }

    $updatedCount = 0;

    foreach ($services as $service) {
        // Skip if the provider failed to return data
        if (!isset($providerData[$service->source_id])) continue;

        $externalServices = $providerData[$service->source_id];

        if ($externalServices->has($service->serviceId)) {
            $remote = $externalServices->get($service->serviceId);
            $remoteRate = is_object($remote) ? ($remote->rate ?? 0) : ($remote['rate'] ?? 0);
            
            if ($remoteRate > 0) {
                $newRate = (float)$remoteRate * (float)$margin;

                if (round((float)$service->rate_per_1000, 4) !== round($newRate, 4)) {
                    $service->update(['rate_per_1000' => $newRate]);
                    $updatedCount++;
                }
            }
        }
    }

    $this->info("Update complete! {$updatedCount} prices modified.");
    Log::info("Daily Price Update: {$updatedCount} services updated across " . count($providerData) . " providers.");
    return 0;
}
}