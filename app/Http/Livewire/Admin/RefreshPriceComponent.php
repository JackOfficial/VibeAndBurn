<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;

class RefreshPriceComponent extends Component
{
    public function refreshBtn(){
        Artisan::call('update:price');
        $feedback = Artisan::output();
         $this->dispatchBrowserEvent('toastr:success', [
            'message' => $feedback,
            ]);
        return redirect()->route('service.index');    
    }
    
    public function render()
    {
        return view('livewire.admin.refresh-price-component');
    }
}
