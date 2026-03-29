<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination; // Required for pagination
use App\Models\wallet as Client_wallet;
use App\Models\fund;
use App\Models\BFCurency;
use App\Models\Currencies;
use Illuminate\Support\Facades\DB;

class Wallet extends Component
{
    use WithPagination;

    // Properties for Funds Adjustment
    public $currency = 'USD', $money;
    public $thisWallet;
    public $feedback;
    public $bf;

    // Properties for Search & UI
    public $search = '';
    protected $paginationTheme = 'bootstrap';

    protected $rules = [
       'currency' => 'required',
       'money' => 'required|numeric|min:0.01',
    ];

    // Reset pagination when search query changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->bf = BFCurency::where('id', 1)->value('currency');
    }
    
    public function edit($id)
    {
        // Load with user relationship to avoid "User details show nothing"
        $this->thisWallet = Client_wallet::with('user')->findOrFail($id);
        $this->money = null; // Clear previous input
        $this->feedback = null;
    }

    /**
     * Converts selected currency amount to USD based on DB rates
     */
    private function getConvertedAmount() 
    {
        if ($this->currency === "USD") {
            return (float) $this->money;
        }

        $rate = Currencies::where('currency', $this->currency)->value('currency_value');
        
        // If rate exists and > 0, divide. Otherwise, fallback to direct amount.
        return ($rate && $rate > 0) ? ($this->money / $rate) : (float) $this->money;
    }
    
    public function increaseFund()
    {
        $this->validate();
        $result = $this->getConvertedAmount();

        try {
            DB::transaction(function () use ($result) {
                $wallet = Client_wallet::where('user_id', $this->thisWallet->user_id)->lockForUpdate()->firstOrFail();
                $wallet->increment('money', $result);

                fund::create([
                    'user_id' => $this->thisWallet->user_id,
                    'method' => $this->currency . ' (Admin Add)',
                    'amount' => $result,
                    'Payedwith' => 'Manual Adjustment',
                ]);
            });

            session()->flash('deleteWalletSuccess', "$" . number_format($result, 2) . " added successfully.");
            $this->dispatchBrowserEvent('closeEditWalletModel');
            // No need for emit(reloadBrowser) if using standard Livewire reactivity
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
                $wallet = Client_wallet::where('user_id', $this->thisWallet->user_id)->lockForUpdate()->firstOrFail();
                
                $actualDeduction = min($result, $wallet->money);

                if ($actualDeduction > 0) {
                    $wallet->decrement('money', $actualDeduction);

                    fund::create([
                        'user_id'   => $this->thisWallet->user_id,
                        'method'    => $this->currency . ' (Admin Remove)',
                        'amount'    => -$actualDeduction, 
                        'Payedwith' => 'Manual Deduction',
                    ]);
                    
                    session()->flash('deleteWalletSuccess', "$" . number_format($actualDeduction, 2) . " removed successfully.");
                } else {
                    $this->feedback = "User has no funds to remove.";
                }
            });

            $this->dispatchBrowserEvent('closeEditWalletModel');
        } catch (\Exception $e) {
            $this->feedback = "Error: " . $e->getMessage();
        }
    }
    
    public function render()
    {
        // Build search query
        $query = Client_wallet::with('user')
            ->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });

        return view('livewire.admin.wallet', [
            'wallets' => $query->orderBy('money', 'ASC')->paginate(10),
            'walletsCounter' => Client_wallet::count(),
            'walletsTotal' => Client_wallet::sum('money'),
        ]);
    }
}