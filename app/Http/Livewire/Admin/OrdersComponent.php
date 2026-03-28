<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\order;
use App\Models\fund;
use App\Models\User;

class OrdersComponent extends Component
{
    use WithPagination;
    
    public $keyword;
    public $orderId;
    public $status;
    public $filterStatus;
    public $thespent, $thefund;

    protected $paginationTheme = 'bootstrap';
    
    public function mount(){
         
    }
    
    public function userWalletDetails($userId){
        $username = User::findOrFail($userId)->name;
        $orders = order::where('user_id', $userId)->get();
        $sum = 0;
        foreach($orders as $order){
         $sum = $sum + (float)ltrim($order->charge, '$');
        }
        
        $fund = fund::where('user_id', $userId)->sum('amount');
        
        $remain = $fund - $sum;
        
        session()->flash("breathDetails", "The total funds paid by " . $username . " is: "  . $fund . ", the total amount charged: " . $sum . " and the balance should be: " . $remain);
    }
    
    public function changeStatus($orderId){
    //   dd("status is: " . $this->status . " and order is: " . $orderId);
      $order = order::where('id', $orderId)->update([
          'status'=>$this->status
          ]);
          
          if($order){
              
              switch($this->status){
                    case 0:
                        $feedback = "This order marked pending!";
                    break;
                    case 1:
                        $feedback = "This order marked completed!";
                    break;
                    case 2:
                        $feedback = "This order marked reversed!";
                    break;
                    case 3:
                        $feedback = "This order marked processing!";
                    break;
                    case 4:
                        $feedback = "This order marked in progress!";
                    break;
                    case 5:
                        $feedback = "This order marked partial!";
                    break;
                }  
                
            $this->dispatchBrowserEvent('toastr:success', [
                'message' => $feedback,
            ]);
          }
          
   }
    
    public function render()
    {
         if(isset($this->keyword) && $this->keyword != ''){
              $orders = order::join('users', 'orders.user_id', '=', 'users.id')
        ->join('services', 'orders.service_id', '=', 'services.id')
        ->join('sources', 'services.source_id', '=', 'sources.id')
        ->join('categories', 'services.category_id', '=', 'categories.id')
        ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
        ->select('orders.*', 'users.name', 'users.email', 'socialmedia.socialmedia', 'categories.category', 'services.service', 'services.source_id', 'sources.api_source', 'services.rate_per_1000', 'services.serviceId')
         ->where('orders.id', $this->keyword)
         ->orWhere('users.name', 'LIKE', '%' . $this->keyword . '%')
         ->orWhere('users.email', 'LIKE', '%' . $this->keyword . '%')
         ->orWhere('orders.link', $this->keyword)
         ->orderBy('orders.id', 'DESC')
         ->paginate(100); 
         
          $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
         ->join('services', 'orders.service_id', '=', 'services.id')
         ->join('sources', 'services.source_id', '=', 'sources.id')
         ->join('categories', 'services.category_id', '=', 'categories.id')
         ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
         ->where('orders.id', $this->keyword)
         ->orWhere('users.name', 'LIKE', '%' . $this->keyword . '%')
         ->orWhere('users.email', 'LIKE', '%' . $this->keyword . '%')
         ->orWhere('orders.link', $this->keyword)
         ->count();
         
         }
         elseif(isset($this->filterStatus) && $this->filterStatus != ""){
            switch($this->filterStatus){
               case 0:
                   $orders = order::join('users', 'orders.user_id', '=', 'users.id')
                    ->join('services', 'orders.service_id', '=', 'services.id')
                    ->join('sources', 'services.source_id', '=', 'sources.id')
                    ->join('categories', 'services.category_id', '=', 'categories.id')
                    ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
                    ->select('orders.*', 'users.name', 'users.email', 'socialmedia.socialmedia', 'categories.category', 'services.service', 'services.source_id', 'sources.api_source', 'services.rate_per_1000', 'services.serviceId')
                    ->where('orders.status', '=', 0)
                    ->orderBy('orders.id', 'DESC')
                    ->paginate(100);
                    
                    $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
                    ->join('services', 'orders.service_id', '=', 'services.id')
                    ->join('categories', 'services.category_id', '=', 'categories.id')
                    ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
                    ->where('orders.status', '=', 0)
                    ->count();
         
                     $this->dispatchBrowserEvent('toastr:success', [
                'message' => "Orders filtered to pending!"
            ]);
                    break;
                    case 1:
                         $orders = order::join('users', 'orders.user_id', '=', 'users.id')
                    ->join('services', 'orders.service_id', '=', 'services.id')
                    ->join('sources', 'services.source_id', '=', 'sources.id')
                    ->join('categories', 'services.category_id', '=', 'categories.id')
                    ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
                    ->select('orders.*', 'users.name', 'users.email', 'socialmedia.socialmedia', 'categories.category', 'services.service', 'services.source_id', 'sources.api_source', 'services.rate_per_1000', 'services.serviceId')
                    ->where('orders.status', '=', 1)
                    ->orderBy('orders.id', 'DESC')
                    ->paginate(100);
                    
                     $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
                    ->join('services', 'orders.service_id', '=', 'services.id')
                    ->join('categories', 'services.category_id', '=', 'categories.id')
                    ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
                    ->where('orders.status', '=', 1)
                    ->count();
                    
                     $this->dispatchBrowserEvent('toastr:success', [
                'message' => "Orders filtered to completed!"
            ]);
                    break;
                    case 2:
                         $orders = order::join('users', 'orders.user_id', '=', 'users.id')
                    ->join('services', 'orders.service_id', '=', 'services.id')
                    ->join('sources', 'services.source_id', '=', 'sources.id')
                    ->join('categories', 'services.category_id', '=', 'categories.id')
                    ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
                    ->select('orders.*', 'users.name', 'users.email', 'socialmedia.socialmedia', 'categories.category', 'services.service', 'services.source_id', 'sources.api_source', 'services.rate_per_1000', 'services.serviceId')
                    ->where('orders.status', '=', 2)
                    ->orderBy('orders.id', 'DESC')
                    ->paginate(100);
                    
                     $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
                    ->join('services', 'orders.service_id', '=', 'services.id')
                    ->join('categories', 'services.category_id', '=', 'categories.id')
                    ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
                    ->where('orders.status', '=', 2)
                    ->count();
                    
                     $this->dispatchBrowserEvent('toastr:success', [
                'message' => "Orders filtered to reversed!"
            ]);
                    break;
                    case 3:
                        $orders = order::join('users', 'orders.user_id', '=', 'users.id')
                    ->join('services', 'orders.service_id', '=', 'services.id')
                    ->join('sources', 'services.source_id', '=', 'sources.id')
                    ->join('categories', 'services.category_id', '=', 'categories.id')
                    ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
                    ->select('orders.*', 'users.name', 'users.email', 'socialmedia.socialmedia', 'categories.category', 'services.service', 'services.source_id', 'sources.api_source', 'services.rate_per_1000', 'services.serviceId')
                    ->where('orders.status', '=', 3)
                    ->orderBy('orders.id', 'DESC')
                    ->paginate(100);
                    
                     $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
                    ->join('services', 'orders.service_id', '=', 'services.id')
                    ->join('categories', 'services.category_id', '=', 'categories.id')
                    ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
                    ->where('orders.status', '=', 3)
                    ->count();
                    
                     $this->dispatchBrowserEvent('toastr:success', [
                'message' => "Orders filtered to processing!"
            ]);
                    break;
                    case 4:
                        $orders = order::join('users', 'orders.user_id', '=', 'users.id')
                    ->join('services', 'orders.service_id', '=', 'services.id')
                    ->join('sources', 'services.source_id', '=', 'sources.id')
                    ->join('categories', 'services.category_id', '=', 'categories.id')
                    ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
                    ->select('orders.*', 'users.name', 'users.email', 'socialmedia.socialmedia', 'categories.category', 'services.service', 'services.source_id', 'sources.api_source', 'services.rate_per_1000', 'services.serviceId')
                    ->where('orders.status', '=', 4)
                    ->orderBy('orders.id', 'DESC')
                    ->paginate(100);
                    
                     $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
                    ->join('services', 'orders.service_id', '=', 'services.id')
                    ->join('categories', 'services.category_id', '=', 'categories.id')
                    ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
                    ->where('orders.status', '=', 4)
                    ->count();
                    
                     $this->dispatchBrowserEvent('toastr:success', [
                'message' => "Orders filtered to progress!"
            ]);
                    break;
                    case 5:
                        $orders = order::join('users', 'orders.user_id', '=', 'users.id')
                    ->join('services', 'orders.service_id', '=', 'services.id')
                    ->join('sources', 'services.source_id', '=', 'sources.id')
                    ->join('categories', 'services.category_id', '=', 'categories.id')
                    ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
                    ->select('orders.*', 'users.name', 'users.email', 'socialmedia.socialmedia', 'categories.category', 'services.service', 'services.source_id', 'sources.api_source', 'services.rate_per_1000', 'services.serviceId')
                    ->where('orders.status', '=', 5)
                    ->orderBy('orders.id', 'DESC')
                    ->paginate(100);
                    
                     $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
                    ->join('services', 'orders.service_id', '=', 'services.id')
                    ->join('categories', 'services.category_id', '=', 'categories.id')
                    ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
                    ->where('orders.status', '=', 5)
                    ->count();
                    
                     $this->dispatchBrowserEvent('toastr:success', [
                'message' => "Orders filtered to partial!"
            ]);
                    break;
                    default:
                   $orders = order::join('users', 'orders.user_id', '=', 'users.id')
                    ->join('services', 'orders.service_id', '=', 'services.id')
                    ->join('sources', 'services.source_id', '=', 'sources.id')
                    ->join('categories', 'services.category_id', '=', 'categories.id')
                    ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
                    ->select('orders.*', 'users.name', 'users.email', 'socialmedia.socialmedia', 'categories.category', 'services.service', 'services.source_id', 'sources.api_source', 'services.rate_per_1000', 'services.serviceId')
                   ->orderBy('orders.id', 'DESC')
                    ->paginate(100);
                    
                     $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
                    ->join('services', 'orders.service_id', '=', 'services.id')
                    ->join('sources', 'orders.source_id', '=', 'sources.id')
                    ->join('categories', 'services.category_id', '=', 'categories.id')
                    ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
                    ->count();
                    
                     $this->dispatchBrowserEvent('toastr:success', [
                'message' => "All Orders!"
            ]);     
            }
         }
         else{
              $orders = order::join('users', 'orders.user_id', '=', 'users.id')
        ->join('services', 'orders.service_id', '=', 'services.id')
        ->join('sources', 'services.source_id', '=', 'sources.id')
        ->join('categories', 'services.category_id', '=', 'categories.id')
        ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
        ->select('orders.*', 'users.name', 'users.email', 'socialmedia.socialmedia', 'categories.category', 'services.service', 'services.source_id', 'sources.api_source', 'services.rate_per_1000', 'services.serviceId')
         ->orderBy('orders.id', 'DESC')
         ->paginate(50);
         
         $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
         ->join('services', 'orders.service_id', '=', 'services.id')
         ->join('categories', 'services.category_id', '=', 'categories.id')
         ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
         ->count();
         
         }
         
         
        
        return view('livewire.admin.orders-component', compact('orders', 'ordersCounter'));
    }
}
