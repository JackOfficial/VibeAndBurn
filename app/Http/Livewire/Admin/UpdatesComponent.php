<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Updates;

class UpdatesComponent extends Component
{
    public function render()
    {
        $myupdates = Updates::orderBy('id', 'DESC')->get();
        return view('livewire.admin.updates-component', compact('myupdates'));
    }
}
