<?php

namespace App\Http\Livewire\Admin\Funds;

use App\Models\User;
use App\Models\fund as Fund;
use App\Models\wallet as Wallet;
use App\Models\BFCurency;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateComponent extends Component
{
    // Form Properties
    public $userId;
    public $search = '';
    public $money = 0;
    public $currency = 'BIF';
    
    // Config Properties
    public $bifRate;

    /**
     * Initialize the component with the current BIF conversion rate.
     */
    public function mount()
    {
        $rateRecord = BFCurency::find(1);
        $this->bifRate = ($rateRecord && $rateRecord->currency > 0) ? $rateRecord->currency : 1;
    }

    /**
     * Computed Property: Calculates the USD equivalent for display.
     */
    public function getConvertedAmountProperty()
    {
        $amount = (float) ($this->money ?: 0);
        
        if ($this->currency === 'BIF') {
            return number_format($amount / $this->bifRate, 2);
        }
        
        return number_format($amount, 2);
    }

    /**
     * Handles the user selection from the search results.
     */
    public function selectUser($id, $name)
    {
        $this->userId = $id;
        $this->search = $name;
    }

    /**
     * Store the manual deposit in the database.
     */
    public function store()
    {
        $this->validate([
            'userId'   => ['required', 'exists:users,id'],
            'currency' => ['required', 'in:BIF,USD'],
            'money'    => ['required', 'numeric', 'min:0.01'],
        ]);

        try {
            DB::transaction(function () {
                $amountInUSD = ($this->currency === 'BIF') 
                    ? (float)$this->money / $this->bifRate 
                    : (float)$this->money;

                // 1. Update or create wallet balance
                $wallet = Wallet::firstOrCreate(
                    ['user_id' => $this->userId],
                    ['money' => 0]
                );
                
                $wallet->increment('money', $amountInUSD);

                // 2. Create transaction history
                Fund::create([
                    'user_id'   => $this->userId,
                    'method'    => 'Manual Deposit',
                    'amount'    => $amountInUSD,
                    'Payedwith' => $this->currency,
                ]);
            });

            session()->flash('adminAddFundSuccess', 'Funds successfully deposited.');
            $this->reset(['money', 'userId', 'search']);

        } catch (\Exception $e) {
            session()->flash('adminAddFundFail', 'Transaction failed. Please try again.');
        }
    }

    public function render()
    {
        $users = [];
        if (strlen($this->search) >= 2 && !$this->userId) {
            $users = User::where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->select('id', 'name', 'email')
                ->limit(5)
                ->get();
        }

        return view('livewire.admin.funds.create-component', [
            'users' => $users
        ]);
    }
}