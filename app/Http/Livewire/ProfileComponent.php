<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileComponent extends Component
{
    public $name, $phone;
    
    public function mount(){
      $user = User::findOrFail(Auth::id());
      $this->name = $user->name;
      $this->phone = $user->phone;
    }
    
    public function updateProfile(){
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:13'
            ]);
            
        $user = User::where('id', Auth::id())->update([
            'name' => $this->name,
            'phone' => $this->phone,
            ]);
            
            if($user){
                $this->dispatchBrowserEvent('editProfile');
               session()->flash('updateProfileSuccess', 'Profile has been updated successfully');
            }
            else{
                 $this->dispatchBrowserEvent('editProfile');
                 session()->flash('updateProfileFail', 'Profile could not be updated');
            }
    }
    
    public function render()
    {
        return view('livewire.profile-component');
    }
}
