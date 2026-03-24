<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\order;

class ReverseComponent extends Component
{
   public $orderId;
   public $orderStatus;
   
   public function make_reverse(){
    //   dd($this->orderId);
      $order = order::where('id', $this->orderId)->update([
          'status'=>6
          ]);
          
          if($order){
            $this->orderStatus = 6;
                
            $this->dispatchBrowserEvent('toastr:success', [
            'message' => 'Marked Reversed!',
            ]);
        //   $this->dispatchBrowserEvent('swal:reverse', [
        //     'type' => 'success',
        //     'title' => 'Order has been reversed',
        //     'text' => '',
        //   ]);
            
          }
          
   }
   
    public function render()
    {
        return view('livewire.reverse-component');
    }
}
