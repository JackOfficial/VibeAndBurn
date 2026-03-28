<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Getservices;
use Illuminate\Support\Facades\Http;

class ServicesApi extends Command
{
    protected $signature = 'bulkfollows:services';
    protected $description = 'Fetch and sync Bulkfollows services to the database';

    public $api_url = 'https://bulkfollows.com/api/v2';
    public $api_key = 'ce7eee2590241df6dfe4c38255f6c60a';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Modernized Request Logic using Laravel HTTP Facade
     */
    private function request($params)
    {
        $params['key'] = $this->api_key;

        $response = Http::asForm()
            ->timeout(60) // Services lists can be large, so we allow more time
            ->retry(3, 200)
            ->post($this->api_url, $params);

        return $response->successful() ? $response->object() : null;
    }

    public function services()
    {
        return $this->request(['action' => 'services']);
    }

    public function handle()
    {
        $this->info("Fetching services from Bulkfollows...");
        
        $apiServices = $this->services();

        if (!$apiServices || !is_array($apiServices)) {
            $this->error("Failed to retrieve services from API.");
            return 1;
        }

        $bar = $this->output->createProgressBar(count($apiServices));
        $bar->start();

        foreach ($apiServices as $service) {
            // updateOrCreate prevents duplicates if you run the command multiple times
            Getservices::updateOrCreate(
                ['service' => $service->service], // Unique identifier
                [
                    'name'     => $service->name,
                    'type'     => $service->type,
                    'category' => $service->category,
                    'rate'     => $service->rate,
                    'min'      => $service->min,
                    'max'      => $service->max,
                    'refill'   => $service->refill ?? false,
                    'cancel'   => $service->cancel ?? false,
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Services synced successfully.");

        return 0;
    }
}