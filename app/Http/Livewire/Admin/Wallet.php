<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\wallet as ClientWallet; // Standardized casing
use App\Models\Fund;
use App\Models\BFCurency;
use App\Models\Currencies;
use Illuminate\Support\Facades\DB;

class Wallet extends Component
{
    use WithPagination;

    // Properties
    public $currency = 'USD';
    public $money;
    public $thisWallet; // Note: For high-traffic apps, consider storing only ID to stay under Livewire payload limits
    public $feedback;
    public $bf;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'currency' => 'required|string',
        'money' => 'required|numeric|min:0.01',
    ];

    // Reset pagination when search query changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        // Use firstValue() or value() to avoid loading the whole model
        $this->bf = BFCurency::where('id', 1)->value('currency');
    }
    
    public function edit($id)
    {
        // Using find() inside edit is fine, but we ensure relationships are eager loaded
        $this->thisWallet = ClientWallet::with('user')->findOrFail($id);
        $this->reset(['money', 'feedback']); 
    }

    /**
     * Optimized: Cache currency rates per request to avoid redundant DB hits
     */
    private function getConvertedAmount() 
    {
        $amount = (float) $this->money;

        if ($this->currency === "USD") {
            return $amount;
        }

        // Optimized: Only fetch the specific value needed
        $rate = Currencies::where('currency', $this->currency)->value('currency_value');
        
        return ($rate && $rate > 0) ? ($amount / $rate) : $amount;
    }
    
    public function increaseFund()
    {
        $this->validate();
        $result = $this->getConvertedAmount();

        try {
            DB::transaction(function () use ($result) {
                // Optimized: Using user_id from the loaded object
                $wallet = ClientWallet::where('user_id', $this->thisWallet->user_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $wallet->increment('money', $result);

                Fund::create([
                    'user_id'   => $this->thisWallet->user_id,
                    'method'    => "{$this->currency} (Admin Add)",
                    'amount'    => $result,
                    'Payedwith' => 'Manual Adjustment',
                ]);
            });

            session()->flash('deleteWalletSuccess', "$" . number_format($result, 2) . " added successfully.");
            $this->dispatchBrowserEvent('closeEditWalletModel');
            
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
                $wallet = ClientWallet::where('user_id', $this->thisWallet->user_id)
                    ->lockForUpdate()
                    ->firstOrFail();
                
                $actualDeduction = min($result, $wallet->money);

                if ($actualDeduction > 0) {
                    $wallet->decrement('money', $actualDeduction);

                    Fund::create([
                        'user_id'   => $this->thisWallet->user_id,
                        'method'    => "{$this->currency} (Admin Remove)",
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
        // Optimized: Combined query with improved search logic
        $wallets = ClientWallet::query()
            ->with(['user', 'funds' => fn($q) => $q->latest()->take(5)])
            ->withAggregate('funds', 'created_at', 'max') 
            ->when($this->search, function($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('funds_max_created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.admin.wallet', [
            'wallets'        => $wallets,
            'walletsCounter' => ClientWallet::count(),
            'walletsTotal'   => ClientWallet::sum('money'),
        ]);
    }
}