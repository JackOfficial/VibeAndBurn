<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class Chat extends Component
{
    public $ticketId;
    
    public function render()
    {
        return view('livewire.admin.chat');
    }
}
