<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;

class RefreshPriceComponent extends Component
{
    public $isUpdating = false;

    public function refreshBtn()
    {
        $this->isUpdating = true;

        // 1. Run the command
        Artisan::call('update:price');
        $feedback = Artisan::output();

        // 2. Notify the browser
        $this->dispatchBrowserEvent('toastr:success', [
            'message' => $feedback ?: 'Prices updated successfully!',
        ]);

        // 3. Emit an event to other components (Optional)
        // This tells the Services Table to refresh its data automatically
        $this->emit('serviceTableUpdated');

        $this->isUpdating = false;
    }

    public function render()
    {
        return view('livewire.admin.refresh-price-component');
    }
}