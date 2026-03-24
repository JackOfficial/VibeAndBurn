<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Wholesalers;

class WholesalerComponent extends Component
{
    public $wholesaler;
    
    public function add(){
        $this->validate([
            'wholesaler' => 'required'
            ]);
            
       $wholesaler = Wholesalers::create([
           'wholesaler' => $this->wholesaler
           ]);
           
       $this->dispatchBrowserEvent('closeWholesalerModel');
    }
    
     public function removeSource($id){
       $delete_source = Wholesalers::where('id', $id)->delete();
       $this->dispatchBrowserEvent('sourceRemoved');
    }
    
     public function edit($id){
            
       $edit_source = Wholesalers::where('id', $id)->get();
       
       $this->dispatchBrowserEvent('closeWholesalerModel');
    }
    
    public function render()
    {
        $wholesalers = Wholesalers::all();
        $wholesalersCounter = Wholesalers::count();
        return view('livewire.admin.wholesaler-component', compact('wholesalers', 'wholesalersCounter'));
    }
}
