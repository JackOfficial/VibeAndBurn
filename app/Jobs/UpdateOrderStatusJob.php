<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\order;

class UpdateOrderStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $api_url = 'https://bulkfollows.com/api/v2'; // API URL

    public $api_key = '1b87dc445d0c29c2c2f30c7899adfc89'; // Your API key

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
     public function status($order_id) { // get order status
        return json_decode($this->connect(array(
            'key' => $this->api_key,
            'action' => 'status',
            'order' => $order_id
        )));
    }

   private function connect($post) {
        $_post = Array();
        if (is_array($post)) {
            foreach ($post as $name => $value) {
                $_post[] = $name.'='.urlencode($value);
            }
        }

        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if (is_array($post)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        $result = curl_exec($ch);
        if (curl_errno($ch) != 0 && empty($result)) {
            $result = false;
        }
        curl_close($ch);
        return $result;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $orders = Order::where('orderId', '!=', null)
        ->where('status', '!=', 1)
        ->get();

       if(count($orders) != 0){
        foreach($orders as $order){
            $feedback = $this->status($order->orderId); # return status, charge, remains, start count, currency
            
            if($feedback->status == "Completed"){
                $status = 1;
            }
            elseif($feedback->status == "Canceled"){
                $status = 2;
                // $money = wallet::findOrFail($order->user_id)->money;
                // $wallet = wallet::where('user_id', $order->user_id)->update([
                // 'money' => $money + substr($order->charge, 1, 100)
                // ]);
            }
            elseif($feedback->status == "Processing"){
                $status = 3;
            }
            elseif($feedback->status == "In progress"){
                $status = 4;
            }
            elseif($feedback->status == "Partial"){
                 $status = 5;
            }
            
            $order = Order::where('orderId', $order->orderId)
            ->update([
                'status' => $status,
                'start_count' => $feedback->start_count,
                'remains' => $feedback->remains,
               ]);
               
               if($order){
                $this->info('Status has been processed');   
               }
               else{
                   $this->error('Status could not be processed');
               }
                
        }
       }
    }
}
