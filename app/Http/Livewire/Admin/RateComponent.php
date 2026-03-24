<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Rate;

class RateComponent extends Component
{
    public $rate;
    
    public function mount(){
        $rate = Rate::first();
        $this->rate = $rate->rate;
    }
    
    public function change(){
        $this->validate([
            'rate' => 'required'
            ]);
            $rate = Rate::where('id', 1)->update([
                'rate' => $this->rate
                ]);
                if($rate){
                     $this->dispatchBrowserEvent('toastr:success', [
                'message' => "Rate has been updated!",
            ]);
                }
                else{
                   $this->dispatchBrowserEvent('toastr:success', [
                'message' => "Rate could not be updated!",
            ]);
                }
    }
    
    public function render()
    {
        return view('livewire.admin.rate-component');
    }
}
