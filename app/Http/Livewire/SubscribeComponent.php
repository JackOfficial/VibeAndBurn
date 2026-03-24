<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\subscription;

class SubscribeComponent extends Component
{
    public $email;
    
    public function subscribe(){
        // $this->validate([
        //     'email'=> ['required', 'string', 'email', 'max:255', 'unique:subscriptions']
        //     ]);
            
          $isSubscribed = subscription::where('email', $this->email)->count();
         
         if($isSubscribed == 0){
             $subscribe = subscription::create([
             'email'=>$this->email
         ]);
         
         if($subscribe){  
              $this->dispatchBrowserEvent('swal:subscribe', [
            'type' => 'success',
            'title' => 'You have been subscribed',
            'text' => '',
          ]);
         }
         else{
            $this->dispatchBrowserEvent('toastr:subscribe', [
            'message' => 'Your subscription could not be sent. There might be something wrong!',
            ]);
         } 
         }
         else{
              $this->dispatchBrowserEvent('toastr:subscribeFailed', [
            'message' => 'You already subscribed!',
            ]);
         }
        
    }
    
    public function render()
    {
        return view('livewire.subscribe-component');
    }
}
