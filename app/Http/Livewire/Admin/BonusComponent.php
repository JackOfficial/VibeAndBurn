<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\bonus;
use App\Models\sharedlink;
use App\Models\wallet;
use App\Models\User;
use App\Models\fund; // Added for accounting
use Illuminate\Notifications\Notifiable;
use App\Notifications\BonusNotification;
use Illuminate\Support\Facades\DB; // Added for safety

class BonusComponent extends Component
{
    use Notifiable;
    
    public $filter = "default";
    public $bonuses, $username;
    
    public function mount(){
        $this->bonuses = collect();
    }
    
    public function offer($id, $user_id, $bonusAmount){
        $user = User::findOrFail($user_id);

        try {
            DB::transaction(function () use ($id, $user_id, $bonusAmount, $user) {
                // 1. Update Wallet using increment for atomicity
                $wallet = wallet::where('user_id', $user_id)->firstOrFail();
                $wallet->increment('money', (float)$bonusAmount);

                // 2. Create a Fund record (Crucial for your "Total Profit" math)
                fund::create([
                    'user_id'   => $user_id,
                    'method'    => 'Bonus',
                    'amount'    => $bonusAmount,
                    'Payedwith' => 'Referral/System Bonus', 
                ]);

                // 3. Mark Bonus as processed
                bonus::where('id', $id)->update([
                    'bonus' => 0,
                    'status' => 2
                ]);
            });

            // 4. Notify and Feedback
            $user->notify(new BonusNotification($user, $bonusAmount));   
            session()->flash("bonusSuccess", "Bonus of " . $bonusAmount . " has been added to " . $user->name . "'s wallet"); 

        } catch (\Exception $e) {
            session()->flash("bonusFail", "Error adding bonus: " . $e->getMessage()); 
        }
    }
    
    public function seeDetails($userId){
        $this->bonuses = sharedlink::join('users', 'sharedlinks.user_id', 'users.id')
                 ->where('sharedlinks.descendant', $userId)
                 ->select('sharedlinks.*', 'users.name', 'users.email')
                 ->orderBy('sharedlinks.created_at', 'DESC')
                 ->get();
        $this->username = User::findOrFail($userId)->name;        
    }
    
    public function render()
    {
        // Using a base query to avoid repeating the join logic
        $baseQuery = bonus::join('users', 'bonuses.user_id', '=', 'users.id')
            ->select('bonuses.*', 'users.name', 'users.email');

        switch($this->filter){
            case "pending":
                $query = (clone $baseQuery)->where('status', 0);
                break;
            case "cleamed": // Kept your original spelling
                $query = (clone $baseQuery)->where('status', 1);
                break;
            case "offered":
                $query = (clone $baseQuery)->where('status', 2);
                break;
            default:
                $query = clone $baseQuery;
                break;
        }

        $sharedlinks = $query->orderBy('bonuses.updated_at', 'DESC')->get();
        $bonusesCounter = $query->count();
        $bonusesTotal = $query->sum('bonus');
             
        return view('livewire.admin.bonus-component', compact('sharedlinks', 'bonusesCounter', 'bonusesTotal'));
    }
}