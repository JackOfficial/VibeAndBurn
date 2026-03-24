<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\service;

class MentionComponent extends Component
{
    public $serviceID, $status;
    public $btn = 0;
    public function mention(){
       $service = service::where('id', $this->serviceID)->update([
           'status' => ($this->status == 1 || $this->status == 0) ? 2 : 1
           ]);
           
           if($service){
            $this->btn = !$this->btn;
            $this->dispatchBrowserEvent('toastr:success', [
            'message' => 'Done!',
            ]); 
           }
           else{
               $this->dispatchBrowserEvent('toastr:success', [
            'message' => 'This order could not be mentioned!',
            ]); 
           }
    }
    
    public function render()
    {
        return view('livewire.admin.mention-component');
    }
}
