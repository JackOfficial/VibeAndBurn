<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\bonus;
use App\Models\sharedlink;
use App\Models\wallet;
use App\Models\User;
use Illuminate\Notifications\Notifiable;
use App\Notifications\BonusNotification;

class BonusComponent extends Component
{
    use Notifiable;
    
    public $filter = "default";
    public $bonuses, $username;
    
    public function mount(){
        $this->bonuses = collect();
    }
    
    public function offer($id, $user_id, $bonus){
        $user = User::findOrFail($user_id);
       $current_money = wallet::where('user_id', $user_id)->value('money');
       $wallet = wallet::where('user_id', $user_id)->update([
           'money' => $current_money + $bonus
           ]);
           if($wallet){
               bonus::where('id', $id)->update([
                   'bonus' => 0,
                   'status' => 2
                   ]);
              $user->notify(new BonusNotification($user, $bonus));   
              session()->flash("bonusSuccess", "Bonus has been added to " . $user->name . "'s wallet"); 
           }
           else{
             session()->flash("bonusFail", "Bonus could not be added to " . $user->name . "'s wallet"); 
            }
           
    }
    
    public function seeDetails($userId){
      $this->bonuses = collect();
      $this->bonuses = sharedlink::join('users', 'sharedlinks.user_id', 'users.id')
               ->where('sharedlinks.descendant', $userId)
               ->select('sharedlinks.*', 'users.name', 'users.email')
               ->orderBy('sharedlinks.created_at', 'DESC')
               ->get();
      $this->username = User::findOrFail($userId)->name;        
    }
    
    public function render()
    {
        switch($this->filter){
            case "default":
             $sharedlinks = bonus::join('users', 'bonuses.user_id', '=', 'users.id')
             ->select('bonuses.*', 'users.name', 'users.email')
             ->orderBy('bonuses.updated_at', 'DESC')
             ->get();
         
             $bonusesCounter = bonus::count();
          
             $bonusesTotal = bonus::sum('bonus');  
             break;
             
              case "pending":
               $sharedlinks = bonus::join('users', 'bonuses.user_id', '=', 'users.id')
             ->select('bonuses.*', 'users.name', 'users.email')
             ->where('status', 0)
             ->orderBy('bonuses.id', 'DESC')
             ->get();
         
             $bonusesCounter = bonus::where('status', 0)->count();
          
             $bonusesTotal = bonus::where('status', 0)->sum('bonus');  
             break;  
             
              case "cleamed":
               $sharedlinks = bonus::join('users', 'bonuses.user_id', '=', 'users.id')
             ->select('bonuses.*', 'users.name', 'users.email')
             ->where('status', 1)
             ->orderBy('bonuses.id', 'DESC')
             ->get();
         
             $bonusesCounter = bonus::where('status', 1)->count();
          
             $bonusesTotal = bonus::where('status', 1)->sum('bonus');  
             break;
             
             case "offered":
               $sharedlinks = bonus::join('users', 'bonuses.user_id', '=', 'users.id')
             ->select('bonuses.*', 'users.name', 'users.email')
             ->where('status', 2)
             ->orderBy('bonuses.id', 'DESC')
             ->get();
         
             $bonusesCounter = bonus::where('status', 2)->count();
          
             $bonusesTotal = bonus::where('status', 2)->sum('bonus');  
             break;
             
             default:
              $sharedlinks = bonus::join('users', 'bonuses.user_id', '=', 'users.id')
             ->select('bonuses.*', 'users.name', 'users.email')
             ->orderBy('bonuses.id', 'DESC')
             ->get();
         
             $bonusesCounter = bonus::count();
          
             $bonusesTotal = bonus::sum('bonus');    
        }
        
             
        return view('livewire.admin.bonus-component', compact('sharedlinks', 'bonusesCounter', 'bonusesTotal'));
    }
}
