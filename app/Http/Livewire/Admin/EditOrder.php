<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\order;
use App\Models\wallet;
use App\Models\service;
use App\Models\category;

class EditOrder extends Component
{
  public $orderID; 
  public $category;
  public $service, $toggleService = 1;
  public $rate_per_1000;
  public $quantity;
  public $min_order, $max_order;
  public $charge;
  public $description;
  public $range = 0;
  public $commentToggler = 0;
  public $comment;
  public $quantityToggler = 0;
  public $custom_comments = [90, 113, 182, 183, 192, 198];
  public $link, $status;
  public $userID, $username, $email, $phone;
  public $startCount, $remains, $orderId;
  
  public function updatedComment(){
      $lines = preg_split('/\n|\r/',$this->comment);
      $this->quantity = count($lines); 
      
      if($this->category == ""){
        $this->validate(['category' => 'required']);
      }
       if($this->service == ""){
        $this->validate(['service' => 'required']);
      }
     elseif($this->quantity == ""){
        $this->resetErrorBag();
        $this->charge = 0;
      }
      else{
          if($this->quantity != "" && $this->rate_per_1000 != ""){
        $this->charge = '$'.($this->rate_per_1000 * $this->quantity)/1000;
    }
    else{
      $this->charge = "";
    }
      }
  }
  
   public function mount(){
    $order = order::join('users', 'orders.user_id', '=', 'users.id')
        ->join('services', 'orders.service_id', '=', 'services.id')
        ->join('categories', 'services.category_id', '=', 'categories.id')
        ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
        ->select('orders.*', 'users.name', 'users.email', 'users.phone', 'socialmedia.socialmedia', 'services.category_id', 'categories.category', 'services.service', 'services.description', 'services.rate_per_1000', 'services.serviceId')
        ->where('orders.id', $this->orderID)
        ->first();
        
  $this->category = $order->category_id;  
   $this->service = $order->service_id;
   $this->quantity = $order->quantity;  
   $this->charge = ltrim($order->charge, '$');  
   $this->rate_per_1000 = $order->rate_per_1000; 
   $this->description = $order->description;
   $this->userID = $order->user_id;
   $this->link = $order->link;
   $this->username = $order->name;
   $this->email = $order->email;
   $this->phone = $order->phone;
   $this->startCount = $order->start_count;
   $this->remains = $order->remains;
   $this->orderId = $order->orderId;
   $this->comment = $order->comment;
   switch($order->status){
       case 1:
           $this->status = "Completed";
           break;
        case 2:
             $this->status = "Canceled";
           break;
           case 3:
             $this->status = "Processing";
           break;
           case 4:
             $this->status = "In progress";
           break;
           case 5:
             $this->status = "Partial";
           break;
           default:
               $this->status = "Unknown"; 
   }
   
  }
  
   public function updatedCategory()
    {
        $this->services = service::join('categories', 'services.category_id', '=', 'categories.id')
        ->where('categories.id', '=', $this->category)
        ->where('services.status', '=', 1)
        ->select('services.*')
         ->orderBy('services.service', 'ASC')
         ->get();

         $this->service = "";

         if($this->category != ""){
              $this->toggleService = 1;
         }
         else{
              $this->toggleService = 0;
         }
        
         $this->range = 0;
         $this->reset('description');

         
    }
    
     public function updatedService()
    {
        $service = service::join('categories', 'services.category_id', '=', 'categories.id')
        ->where('services.id', '=', $this->service)
        ->where('services.status', '=', 1)
        ->select('services.*')
        ->first();

        $this->rate_per_1000 = $service->rate_per_1000;
        $this->min_order = $service->min_order;
        $this->max_order = $service->max_order;
        $this->description = $service->description;
        $this->range = 1;
        
        if(in_array($this->service, $this->custom_comments)){
            $this->commentToggler = 1;
            $this->quantityToggler = 1;
        }
        else{
            $this->comment = "";
            $this->commentToggler = 0;
            $this->quantityToggler = 0;
        }

        $this->reset('quantity', 'charge');
    }
    
     public function updatedQuantity()
    {
      if($this->category == ""){
        $this->validate(['category' => 'required']);
      }
       if($this->service == ""){
        $this->validate(['service' => 'required']);
      }
     elseif($this->quantity == ""){
        $this->resetErrorBag();
        $this->charge = 0;
      }
      else{
          if($this->quantity != "" && $this->rate_per_1000 != ""){
        $this->charge = '$'.($this->rate_per_1000 * $this->quantity)/1000;
    }
    else{
      $this->charge = "";
    }
      }
      
    }
    ////////////////////////////////////
    public function updateOrder(){
        $this->validate([
            'startCount' => 'nullable',
            'charge' => 'required'
            ]);
        $order = order::where('id', $this->orderID)->update([
                    'start_count' => $this->startCount,
                    'charge' => '$'.$this->charge,
                    'remains' => $this->remains,
                    'orderId' => $this->orderId,
                     'status'=>7
                    ]);
                    if($order){
                        return redirect()->back()->with('editOrderSuccess', "Order has been updated successfully");
                    }
                    else{
                        return redirect()->back()->with('editOrderFail', "Order could not be updated, Something went wrong!");
                    }
    }
    //////////////////////////////////////////
    public function render()
    {
        $categories = category::orderBy('category', 'ASC')->get();
        $services = service::orderBy('service', 'ASC')->get();
        return view('livewire.admin.edit-order', compact('categories', 'services'));
    }
}
