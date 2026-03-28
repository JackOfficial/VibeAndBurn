<?php

namespace App\Http\Livewire\Orders;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\wallet;
use App\Models\service;
use App\Models\category;
use App\Models\order;
use App\Models\Advert;
use Illuminate\Support\Facades\DB;

class NewOrderComponent extends Component
{
    public $category;
    public $service, $services, $toggleService = 0;
    public $rate_per_1000;
    public $quantity;
    public $min_order, $max_order;
    public $charge = 0; // Initialize as 0 for decimal math
    public $description;
    public $link; // Added property for the order link
    public $range = 0;
    public $commentToggler = 0;
    public $comment;
    public $quantityToggler = 0;
    public $custom_comments = [115, 127, 128, 129, 130, 131, 132, 133];

    /**
     * Helper to calculate charge correctly for decimal storage.
     * We remove the '$' symbol here so it's ready for the database.
     */
    private function calculateCharge()
    {
        if (is_numeric($this->quantity) && is_numeric($this->rate_per_1000)) {
            $this->charge = ($this->rate_per_1000 * $this->quantity) / 1000;
        } else {
            $this->charge = 0;
        }
    }

    public function updatedComment()
{
    // Filter out empty lines so the user isn't charged for blank rows
    $lines = preg_split('/\n|\r/', $this->comment);
    $filteredLines = array_filter(array_map('trim', $lines)); 
    
    $this->quantity = count($filteredLines);
    
    $this->validate([
        'category' => 'required',
        'service' => 'required'
    ]);

    $this->calculateCharge();
}

    public function mount()
    {
        if (session()->has('orderService')) {
            $service = service::where('id', session()->get('orderService'))
                ->where('status', 1)
                ->first();

            if ($service) {
                $this->toggleService = 1;
                $this->category = $service->category_id;
                $this->rate_per_1000 = $service->rate_per_1000;
                $this->min_order = $service->min_order;
                $this->max_order = $service->max_order;
                $this->description = $service->description;
                $this->range = 1;

                if (in_array($service->category_id, $this->custom_comments)) {
                    $this->commentToggler = 1;
                    $this->quantityToggler = 1;
                } else {
                    $this->reset(['comment', 'commentToggler', 'quantityToggler']);
                }

                $this->services = service::where('category_id', $service->category_id)
                    ->where('status', 1)
                    ->orderBy('service', 'ASC')
                    ->get();

                $this->service = $service->id;
            }
        } else {
            $this->services = collect();
        }
    }

    public function updatedCategory()
    {
        $this->services = service::where('category_id', $this->category)
            ->where('status', 1)
            ->orderBy('service', 'ASC')
            ->get();

        $this->service = "";
        $this->toggleService = $this->category != "" ? 1 : 0;
        $this->range = 0;
        $this->reset(['description', 'quantity', 'charge']);
    }

    public function updatedService()
    {
        $service = service::find($this->service);

        if ($service) {
            $this->rate_per_1000 = $service->rate_per_1000;
            $this->min_order = $service->min_order;
            $this->max_order = $service->max_order;
            $this->description = $service->description;
            $this->range = 1;

            if (in_array($service->category_id, $this->custom_comments)) {
                $this->commentToggler = 1;
                $this->quantityToggler = 1;
            } else {
                $this->reset(['comment', 'commentToggler', 'quantityToggler']);
            }
        }

        $this->reset(['quantity', 'charge']);
    }

    public function updatedQuantity()
    {
        $this->validate([
            'category' => 'required',
            'service' => 'required',
        ]);

        $this->calculateCharge();
    }

  public function store()
{
    // 1. Validation
    $this->validate([
        'category' => 'required',
        'service' => 'required|exists:services,id',
        'link' => 'required|url',
        'quantity' => "required|numeric|min:{$this->min_order}|max:{$this->max_order}",
    ]);

    $service = service::findOrFail($this->service);

    try {
        // Use the transaction return value
        return DB::transaction(function () use ($service) {
            
            // 2. Security: Recalculate charge based on DB rate, not frontend variable
            $finalCharge = ($service->rate_per_1000 * $this->quantity) / 1000;

            // 3. Double check balance (Locking is handled by the Observer re-fetching)
            if (Auth::user()->wallet->money < $finalCharge) {
                throw new \Exception('Insufficient funds.');
            }

            // 4. Create Order (Observer handles wallet deduction automatically)
            $order = order::create([
                'user_id'    => Auth::id(),
                'service_id' => $this->service,
                'source_id'  => $service->source_id,
                'link'       => $this->link,
                'quantity'   => $this->quantity,
                'comment'    => $this->comment, // Added this back for custom comments
                'charge'     => $finalCharge,
                'status'     => 0,
            ]);

            // 5. API Logic
            if ($service->serviceId) {
                $apiController = new \App\Http\Controllers\OrderController();
                
                /**
                 * We pass 'this' because the Controller's sendApiOrder 
                 * can read properties from it if we treat it as an object.
                 */
                $apiResponse = $apiController->sendApiOrder($service, $this);

                if ($apiResponse && isset($apiResponse->order)) {
                    $order->update(['orderId' => $apiResponse->order]);
                } else {
                    $errorReason = $apiResponse->error ?? $apiResponse->message ?? "Provider API Error";
                    throw new \Exception($errorReason);
                }
            }

            session()->flash('addOrderSuccess', 'Order placed successfully!');
            return redirect()->to('/orders');
        });
    } catch (\Exception $e) {
        // This keeps the user on the page and shows the error via Toastr
        $this->dispatchBrowserEvent('toastr:error', ['message' => $e->getMessage()]);
    }
}

    public function render()
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Database-level sum is much faster than a PHP foreach loop
        $accountSpending = order::where('user_id', $user->id)
            ->where('status', '!=', 2)
            ->sum('charge');

        $money = $user->wallet ? $user->wallet->money : 0;

        return view('livewire.orders.new-order-component', [
            'categories' => category::orderBy('category', 'ASC')->get(),
            'accountSpending' => $accountSpending,
            'money' => $money,
            'advert' => Advert::where('status', 1)->first()
        ]);
    }
}