<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ticket;

class TicketsComponent extends Component
{
     public $checkbox = [], $selectAll = false;
    public $search;
    
    public function deleteMultipleMessages(){
        if(empty($this->checkbox)){
             session()->flash('feedback', 'No message selected');
        }
        else{
            $delete = message::whereIn('id', $this->checkbox)->delete();
       if($delete){
          $this->reset('checkbox');
        }
        }
    }
    
    public function delete($id){
        message::where('id', $id)->delete();
    }
    
    public function blockEmail($email){
        dd($email);
        $blocklist = Blocklist::create([
            'email' => $email,
            'service' => 'contact'
            ]);
            
            if($blocklist){
              session()->flash('feedback', 'Email was blocked');
            }
            else{
                session()->flash('feedback', 'Email could not be blocked');
            }
            
    }
    
     public function selectAllMessages(){
        $this->selectAll = !$this->selectAll;
        if($this->selectAll == true){
            $this->checkbox = message::pluck('id')->toArray();
        }
        else{
            $this->checkbox = [];
        }
    }
    
    public function mount(){
        // $this->checkbox = message::pluck('id');
    }
    
    public function render()
    {
        // if(isset($this->search)){
        //       $messages = message::where('email', 'LIKE', '%' . $this->search . '%')
        //       ->orWhere('name', 'LIKE', '%' . $this->search . '%')
        //       ->orderBy('id', 'DESC')
        //       ->get();
              
        //       $messageCounter = message::where('email', 'LIKE', '%' . $this->search . '%')
        //       ->orWhere('name', 'LIKE', '%' . $this->search . '%')
        //       ->count(); 
        // }
        // else{
        // $messages = message::orderBy('id', 'DESC')->get();
        // $messageCounter = message::count();   
        // }
        
        $tickets = Ticket::join('users', 'tickets.user_id', 'users.id')
        ->select('tickets.*', 'users.name', 'users.email')
        ->orderBy('tickets.id', 'DESC')
        ->get();
        
        $ticketsCounter = Ticket::count();
        
        return view('livewire.tickets-component', compact('tickets', 'ticketsCounter'));
    }
}
