<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\service as our_service;
use App\Models\Rate;
use App\Http\Controllers\apis\BulkmedyaController;
use App\Http\Controllers\apis\AmazingController;
use App\Http\Controllers\apis\BulkfollowsController;
use App\Http\Controllers\apis\SmmsunController;

class UpdatePrice extends Command
{
    protected $signature = 'update:price';
    protected $description = 'Update Services Price using efficient key-mapping';

    public function handle()
    {
        $apis = [
            3 => new AmazingController(),
            4 => new BulkmedyaController(),
            5 => new SmmsunController(),
            'default' => new BulkfollowsController(),
        ];

        $services = our_service::where('state', 1)
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
                
                // Safety: Ensure response is an array before collecting
                if (is_array($response) || is_object($response)) {
                    $providerData[$id] = collect($response)->keyBy('service');
                } else {
                    $this->error("Invalid response from provider ID: {$id}");
                    $providerData[$id] = collect();
                }
            } catch (\Exception $e) {
                $this->error("Provider {$id} Connection Error: " . $e->getMessage());
                $providerData[$id] = collect();
            }
        }

        $updatedCount = 0;

        foreach ($services as $service) {
            // Determine which provider's data to use
            $lookupId = isset($providerData[$service->source_id]) ? $service->source_id : 'default';
            $externalServices = $providerData[$lookupId];

            if ($externalServices->has($service->serviceId)) {
                $remote = $externalServices->get($service->serviceId);
                
                // Handle both object and array formats (Flexible Access)
                $remoteRate = is_object($remote) ? ($remote->rate ?? 0) : ($remote['rate'] ?? 0);
                
                if ($remoteRate > 0) {
                    $newRate = $remoteRate * $margin;

                    // Use round() to avoid tiny decimal changes triggering an update
                    if (round((float)$service->rate_per_1000, 4) !== round((float)$newRate, 4)) {
                        $service->update(['rate_per_1000' => $newRate]);
                        $updatedCount++;
                    }
                }
            }
        }

        $this->info("Update complete! {$updatedCount} prices modified.");
        return 0;
    }
}