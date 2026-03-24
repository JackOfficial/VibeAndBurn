<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\service;

class ServicesComponent extends Component
{
    public $details, $filterServices = "All", $keyword;
    
    // public function mount(){
    //   $this->details = collect(); 
    // }
    
    public function viewService($id){
      $this->details = service::join('categories', 'services.category_id', '=', 'categories.id')
    ->where('services.status', 1)
    ->where('services.id', $id)
    ->select('services.*', 'categories.category')
     ->first();
    }
    
    
    public function services($service){
        $this->reset('keyword');
        $this->filterServices = $service;
    }
    
    public function order($id){
        if($id != ''){
             session()->put('orderService', $id);
             $this->dispatchBrowserEvent('see-detail');
             return redirect('newOrder/create'); 
        }
    }
    
    public function render()
    {
        if(isset($this->keyword) && $this->keyword != ''){
            $services = service::join('categories', 'services.category_id', '=', 'categories.id')
    ->where('services.status', '=', 1)
    ->where('services.service', 'like', '%' . $this->keyword . '%')
    ->select('services.*', 'categories.category')
     ->orderBy('services.service', 'ASC')
     ->get();

     $servicesCounter = service::join('categories', 'services.category_id', '=', 'categories.id')
     ->where('services.status', '=', 1)
     ->where('services.service', 'like', '%' . $this->keyword . '%')
     ->count(); 
        }
        else{
            if($this->filterServices == "All"){
            
             $services = service::join('categories', 'services.category_id', '=', 'categories.id')
    ->where('services.status', '=', 1)
    ->select('services.*', 'categories.category')
     ->orderBy('services.service', 'ASC')
     ->get();

     $servicesCounter = service::join('categories', 'services.category_id', '=', 'categories.id')
     ->where('services.status', '=', 1)
     ->count();
           
        }
        else{
            $services = service::join('categories', 'services.category_id', '=', 'categories.id')
            ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
            ->where('services.status', 1)
            ->where('socialmedia.socialmedia', $this->filterServices)
            ->select('services.*', 'categories.category')
            ->orderBy('services.service', 'ASC')
            ->get();

     $servicesCounter = service::join('categories', 'services.category_id', '=', 'categories.id')
            ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
            ->where('services.status', 1)
            ->where('socialmedia.socialmedia', $this->filterServices)
            ->count();
        }
        }
        
        
     
        return view('livewire.services-component', compact('services', 'servicesCounter'));
    }
}
