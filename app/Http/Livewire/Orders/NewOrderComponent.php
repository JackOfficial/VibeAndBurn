<?php

namespace App\Http\Livewire\Orders;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\wallet;
use App\Models\service;
use App\Models\category;
use App\Models\order;
use App\Models\Advert;

class NewOrderComponent extends Component
{
  public $category;
  public $service, $services, $toggleService = 0;
  public $rate_per_1000;
  public $quantity;
  public $min_order, $max_order;
  public $charge;
  public $description;
  public $range = 0;
  public $commentToggler = 0;
  public $comment;
  public $quantityToggler = 0;
  public $custom_comments = [115, 127, 128, 129, 130, 131, 132, 132, 133];
  
//   protected $listeners = ['orderService'];
  
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
    if(session()->has('orderService')) {
       $service = service::join('categories', 'services.category_id', '=', 'categories.id')
        ->where('services.id', '=', session()->get('orderService'))
        ->where('services.status', '=', 1)
        ->select('services.*')
        ->first();
        
        $this->toggleService = 1;
        
        $this->category = $service->category_id;
        
        $this->rate_per_1000 = $service->rate_per_1000;
        $this->min_order = $service->min_order;
        $this->max_order = $service->max_order;
        $this->description = $service->description;
        $this->range = 1;
        
        if(in_array($service->category_id, $this->custom_comments)){
            $this->commentToggler = 1;
            $this->quantityToggler = 1;
        }
        else{
            $this->comment = "";
            $this->commentToggler = 0;
              $this->quantityToggler = 0;
        } 
        
          $this->services = service::join('categories', 'services.category_id', '=', 'categories.id')
        ->where('categories.id', '=', $service->category_id)
        ->where('services.status', '=', 1)
        ->select('services.*')
         ->orderBy('services.service', 'ASC')
         ->get();
         
         $this->service = $service->id;
    } 
    else{
        $this->services = collect(); 
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
        
        if(in_array($service->category_id, $this->custom_comments)){
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

public function render()
{
    $categories = category::orderBy('category', 'ASC')->get();
    $spendings = order::where('user_id', Auth::id())
        ->where('status', '!=', 2)
        ->get();
    $advert = Advert::where('status', 1)->first();

    $accountSpending = 0;

    foreach($spendings as $spending)
    {
        $accountSpending += (float) $spending->charge;
    }
    
    if(Auth::check()){
        // Simplified wallet fetch
        $moneyValue = wallet::where('user_id', Auth::id())->value('money');
        $money = (float) ($moneyValue ?? 0);
    } else {
        return redirect('login');
    }

    return view('livewire.orders.new-order-component', [
        'categories' => $categories,
        'accountSpending' => $accountSpending,
        'money' => $money,
        'advert' => $advert
    ]);
}
}
