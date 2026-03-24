<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http; // The modern way

class AmazingAPIs extends Command
{
    protected $signature = 'create:amazingapi';
    protected $description = 'Amazing APIs';

    public $api_url = 'https://amazingsmm.com/api/v2';
    public $api_key = '8b6cc0d942ab563f9b0d229f74e3ce04';

    public function __construct()
    {
        parent::__construct();
    }

    /** Add order */
    public function order($data)
    {
        return $this->request(['action' => 'add'] + $data);
    }

    /** Get order status */
    public function status($order_id)
    {
        return $this->request([
            'action' => 'status',
            'order'  => $order_id
        ]);
    }

    /** Get orders status */
    public function multiStatus($order_ids)
    {
        return $this->request([
            'action' => 'status',
            'orders' => implode(",", (array)$order_ids)
        ]);
    }

    /** Get services */
    public function services()
    {
        return $this->request(['action' => 'services']);
    }

    /** Get balance */
    public function balance()
    {
        return $this->request(['action' => 'balance']);
    }

    /** * Optimized Request logic using Laravel HTTP Facade
     */
    private function request($params)
    {
        $params['key'] = $this->api_key;

        // asForm() sends the data as 'application/x-www-form-urlencoded'
        // which is what SMM APIs expect.
        $response = Http::asForm()
            ->timeout(30) // Important for Namecheap stability
            ->withUserAgent('Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)')
            ->post($this->api_url, $params);

        if ($response->successful()) {
            return $response->object(); // Automatically decodes JSON
        }

        return null;
    }

    public function handle()
    {
        $balance = $this->balance();
        $this->info("Balance checked.");

        $status = $this->status(590972);
        
        if ($status) {
            $this->line("Order Status: " . ($status->status ?? 'Unknown'));
            // dd($status); 
        } else {
            $this->error("Failed to connect to Amazing API.");
        }

        return 0;
    }
}