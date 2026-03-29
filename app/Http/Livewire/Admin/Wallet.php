<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\wallet as Client_wallet;
use App\Models\fund;
use App\Models\BFCurency;
use App\Models\Currencies;
use Illuminate\Support\Facades\DB;

class Wallet extends Component
{
    public $currency, $money;
    public $thisWallet;
    public $feedback;
    public $bf;
    
    protected $rules = [
       'currency' => 'required',
       'money' => 'required|numeric|min:0.01',
    ];

    public function mount(){
        $this->currency = "USD";
        $this->bf = BFCurency::where('id', 1)->value('currency');
    }
    
    public function edit($id){
        // Using with() is cleaner than a manual join for a single record
        $this->thisWallet = Client_wallet::with('user')->findOrFail($id);
    }

    // Shared conversion logic to keep code DRY (Don't Repeat Yourself)
    private function getConvertedAmount() {
        if(in_array($this->currency, ["BIF", "KES"])) {
            $rate = Currencies::where('currency', $this->currency)->value('currency_value');
            return $rate > 0 ? ($this->money / $rate) : $this->money;
        }
        return (float) $this->money;
    }
    
    public function increaseFund()
    {
        $this->validate();
        $result = $this->getConvertedAmount();

        try {
            DB::transaction(function () use ($result) {
                $wallet = Client_wallet::where('user_id', $this->thisWallet->user_id)->firstOrFail();
                
                // 1. Atomic increment (Prevents overwriting simultaneous orders)
                $wallet->increment('money', $result);

                // 2. Create Fund record for accounting
                fund::create([
                    'user_id' => $this->thisWallet->user_id,
                    'method' => $this->currency . ' (Admin Add)',
                    'amount' => $result,
                    'Payedwith' => 'Manual Adjustment',
                ]);
            });

            $this->feedback = "$" . number_format($result, 2) . " added successfully.";
            $this->dispatchBrowserEvent('closeEditWalletModel');
            $this->emit('reloadBrowser');

        } catch (\Exception $e) {
            $this->feedback = "Error: " . $e->getMessage();
        }
    }
    
    public function decreaseFund()
    {
        $this->validate();
        $result = $this->getConvertedAmount();

        try {
            DB::transaction(function () use ($result) {
                $wallet = Client_wallet::where('user_id', $this->thisWallet->user_id)->firstOrFail();
                
                // 1. Atomic decrement
                $wallet->decrement('money', $result);

                // 2. Create NEGATIVE Fund record to keep history accurate
                fund::create([
                    'user_id' => $this->thisWallet->user_id,
                    'method' => $this->currency . ' (Admin Remove)',
                    'amount' => -$result, // Note the negative sign
                    'Payedwith' => 'Manual Deduction',
                ]);
            });

            $this->feedback = "$" . number_format($result, 2) . " removed successfully.";
            $this->dispatchBrowserEvent('closeEditWalletModel');
            $this->emit('reloadBrowser');

        } catch (\Exception $e) {
            $this->feedback = "Error: " . $e->getMessage();
        }
    }
    
    public function render()
    {
        // Simple and clean query for the table
        $wallets = Client_wallet::with('user')->orderBy('money', 'ASC')->get();
        $walletsCounter = Client_wallet::count();
        $walletsTotal = Client_wallet::sum('money');
     
        return view('livewire.admin.wallet', compact('wallets', 'walletsCounter', 'walletsTotal'));
    }
}