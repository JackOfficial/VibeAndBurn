<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Wholesalers;
use Illuminate\Support\Facades\Session;

class WholesalerComponent extends Component
{
    public $wholesaler, $wholesaler_id;
    public $isEditMode = false;

    // Reset fields after actions
    public function resetFields()
    {
        $this->wholesaler = '';
        $this->wholesaler_id = null;
        $this->isEditMode = false;
    }

    public function add()
    {
        $this->validate([
            'wholesaler' => 'required|min:3|unique:wholesalers,wholesaler'
        ]);

        Wholesalers::create([
            'wholesaler' => $this->wholesaler,
            'status' => 1 // Default to active
        ]);

        $this->resetFields();
        $this->dispatchBrowserEvent('closeWholesalerModel');
        Session::flash('deleteWalletSuccess', 'Wholesaler added successfully!');
    }

    public function edit($id)
    {
        $this->isEditMode = true;
        $record = Wholesalers::findOrFail($id);
        $this->wholesaler_id = $id;
        $this->wholesaler = $record->wholesaler;

        // This opens the edit modal if you use a separate one, 
        // otherwise you can use the same modal with @if($isEditMode)
        $this->dispatchBrowserEvent('showEditModal');
    }

    public function update()
    {
        $this->validate([
            'wholesaler' => 'required|min:3|unique:wholesalers,wholesaler,' . $this->wholesaler_id
        ]);

        if ($this->wholesaler_id) {
            $record = Wholesalers::find($this->wholesaler_id);
            $record->update([
                'wholesaler' => $this->wholesaler
            ]);

            $this->resetFields();
            $this->dispatchBrowserEvent('closeWholesalerModel');
            Session::flash('deleteWalletSuccess', 'Wholesaler updated successfully!');
        }
    }

    public function removeSource($id)
    {
        try {
            Wholesalers::where('id', $id)->delete();
            $this->dispatchBrowserEvent('sourceRemoved');
            Session::flash('deleteWalletSuccess', 'Source deleted successfully!');
        } catch (\Exception $e) {
            Session::flash('deleteWalletFail', 'Could not delete source.');
        }
    }

    public function render()
    {
        $wholesalers = Wholesalers::orderBy('created_at', 'desc')->get();
        $wholesalersCounter = Wholesalers::count();
        
        return view('livewire.admin.wholesaler-component', [
            'wholesalers' => $wholesalers,
            'wholesalersCounter' => $wholesalersCounter
        ]);
    }
}