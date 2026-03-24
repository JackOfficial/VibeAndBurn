<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\order;

class ChangeStatus extends Component
{
    public $orderId;
   public $orderStatus;
   public $status;
   
   public function updatedStatus(){
      dd("status is: " . $this->status . " and order is: " . $this->orderId);
      $order = order::where('id', $this->orderId)->update([
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
        return view('livewire.admin.change-status');
    }
}
