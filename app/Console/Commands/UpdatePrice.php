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

    // Property to map IDs to Names
    public $providerNames = [
        2 => 'Bulkfollows',
        3 => 'Amazing',
        4 => 'Bulkmedya',
        5 => 'Smmsun'
    ];

    public function handle()
    {
        $apis = [
            2 => new BulkfollowsController(),
            3 => new AmazingController(),
            4 => new BulkmedyaController(),
            5 => new SmmsunController(),
        ];

        $services = our_service::where('status', 1)
            ->whereNotNull('serviceId')
            ->where('serviceId', '!=', '')
            ->get();

        if ($services->isEmpty()) {
            $this->warn("No active services found.");
            return 0;
        }

        $rateSetting = Rate::first();
        // Margin safety check
        $margin = ($rateSetting && $rateSetting->rate > 0) ? $rateSetting->rate : 1;

        $providerData = [];
        foreach ($apis as $id => $api) {
            try {
                $response = $api->services();
                $providerData[$id] = collect($response)->keyBy('service');
            } catch (\Exception $e) {
                $this->error("Provider {$id} Error: " . $e->getMessage());
            }
        }

        $updatedCount = 0;

        foreach ($services as $service) {
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
            } else {
                // Auto-disable if missing from provider API
                if ($service->status == 1) {
                    $service->update(['status' => 0]); 
                    // Added $this-> here:
                    $pName = $this->providerNames[$service->source_id] ?? 'Unknown Provider'; 
                    $this->warn("Service #{$service->serviceId} disabled: Not found on $pName");
                    Log::warning("SMM Sync: Service #{$service->serviceId} was disabled because it is missing from the $pName API.");
                }
            }
        }

        $this->info("Update complete! {$updatedCount} prices modified.");
        Log::info("Daily Price Update: {$updatedCount} services updated across " . count($providerData) . " providers.");
        return 0;
    }
}