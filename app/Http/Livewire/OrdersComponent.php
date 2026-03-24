<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\order;
use Illuminate\Support\Facades\Auth;

class OrdersComponent extends Component
{
    public $filter = "All";
    
    public function render()
    {
       switch($this->filter){
                case "All":
                $userID = Auth::user()->id;
       $orders = order::join('users', 'orders.user_id', '=', 'users.id')
       ->join('services', 'orders.service_id', '=', 'services.id')
       ->join('categories', 'services.category_id', '=', 'categories.id')
       ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
       ->where('users.id', '=', $userID)
       ->select('orders.*', 'services.service', 'services.rate_per_1000',  'users.name', 'users.email', 'socialmedia.socialmedia', 'categories.category')
        ->orderBy('orders.id', 'DESC')
        ->get();

        $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
        ->join('services', 'orders.service_id', '=', 'services.id')
        ->join('categories', 'services.category_id', '=', 'categories.id')
        ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
        ->where('users.id', '=', $userID)
        ->count();
        break;
        
         case "Completed":
                $userID = Auth::user()->id;
       $orders = order::join('users', 'orders.user_id', '=', 'users.id')
       ->join('services', 'orders.service_id', '=', 'services.id')
       ->join('categories', 'services.category_id', '=', 'categories.id')
       ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
       ->where('users.id', '=', $userID)
       ->where('orders.status', 1)
       ->select('orders.*', 'services.service', 'services.rate_per_1000',  'users.name', 'users.email', 'socialmedia.socialmedia', 'categories.category')
        ->orderBy('orders.id', 'DESC')
        ->get();

        $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
        ->join('services', 'orders.service_id', '=', 'services.id')
        ->join('categories', 'services.category_id', '=', 'categories.id')
        ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
        ->where('orders.status', 1)
        ->where('users.id', '=', $userID)
        ->count();
        break;
        
        case "Processing":
                $userID = Auth::user()->id;
       $orders = order::join('users', 'orders.user_id', '=', 'users.id')
       ->join('services', 'orders.service_id', '=', 'services.id')
       ->join('categories', 'services.category_id', '=', 'categories.id')
       ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
       ->where('users.id', '=', $userID)
       ->where('orders.status', 3)
       ->select('orders.*', 'services.service', 'services.rate_per_1000',  'users.name', 'users.email', 'socialmedia.socialmedia', 'categories.category')
        ->orderBy('orders.id', 'DESC')
        ->get();

        $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
        ->join('services', 'orders.service_id', '=', 'services.id')
        ->join('categories', 'services.category_id', '=', 'categories.id')
        ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
        ->where('orders.status', 3)
        ->where('users.id', '=', $userID)
        ->count();
        break;
        
         case "In progress":
                $userID = Auth::user()->id;
       $orders = order::join('users', 'orders.user_id', '=', 'users.id')
       ->join('services', 'orders.service_id', '=', 'services.id')
       ->join('categories', 'services.category_id', '=', 'categories.id')
       ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
       ->where('users.id', '=', $userID)
       ->where('orders.status', 4)
       ->select('orders.*', 'services.service', 'services.rate_per_1000',  'users.name', 'users.email', 'socialmedia.socialmedia', 'categories.category')
        ->orderBy('orders.id', 'DESC')
        ->get();

        $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
        ->join('services', 'orders.service_id', '=', 'services.id')
        ->join('categories', 'services.category_id', '=', 'categories.id')
        ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
        ->where('orders.status', 4)
        ->where('users.id', '=', $userID)
        ->count();
        break;
        
         case "Partial":
                $userID = Auth::user()->id;
       $orders = order::join('users', 'orders.user_id', '=', 'users.id')
       ->join('services', 'orders.service_id', '=', 'services.id')
       ->join('categories', 'services.category_id', '=', 'categories.id')
       ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
       ->where('users.id', '=', $userID)
       ->where('orders.status', 5)
       ->select('orders.*', 'services.service', 'services.rate_per_1000',  'users.name', 'users.email', 'socialmedia.socialmedia', 'categories.category')
        ->orderBy('orders.id', 'DESC')
        ->get();

        $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
        ->join('services', 'orders.service_id', '=', 'services.id')
        ->join('categories', 'services.category_id', '=', 'categories.id')
        ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
        ->where('orders.status', 5)
        ->where('users.id', '=', $userID)
        ->count();
        break;
        
        case "Canceled":
                $userID = Auth::user()->id;
       $orders = order::join('users', 'orders.user_id', '=', 'users.id')
       ->join('services', 'orders.service_id', '=', 'services.id')
       ->join('categories', 'services.category_id', '=', 'categories.id')
       ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
       ->where('users.id', '=', $userID)
       ->where('orders.status', 2)
       ->select('orders.*', 'services.service', 'services.rate_per_1000',  'users.name', 'users.email', 'socialmedia.socialmedia', 'categories.category')
        ->orderBy('orders.id', 'DESC')
        ->get();

        $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
        ->join('services', 'orders.service_id', '=', 'services.id')
        ->join('categories', 'services.category_id', '=', 'categories.id')
        ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
        ->where('orders.status', 2)
        ->where('users.id', '=', $userID)
        ->count();
        break;
        
        default:
                $userID = Auth::user()->id;
       $orders = order::join('users', 'orders.user_id', '=', 'users.id')
       ->join('services', 'orders.service_id', '=', 'services.id')
       ->join('categories', 'services.category_id', '=', 'categories.id')
       ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
       ->where('users.id', '=', $userID)
       ->select('orders.*', 'services.service', 'services.rate_per_1000',  'users.name', 'users.email', 'socialmedia.socialmedia', 'categories.category')
        ->orderBy('orders.id', 'DESC')
        ->get();

        $ordersCounter = order::join('users', 'orders.user_id', '=', 'users.id')
        ->join('services', 'orders.service_id', '=', 'services.id')
        ->join('categories', 'services.category_id', '=', 'categories.id')
        ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
        ->where('users.id', '=', $userID)
        ->count();
        break;
                  
            }
       
        return view('livewire.orders-component', compact('orders', 'ordersCounter'));
    }
}
