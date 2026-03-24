<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\BFCurency;
use App\Models\Currencies;

class BFCurrency extends Component
{
    public $currency, $currencyValue;
    
    public function mount(){
    //  $this->bif = BFCurency::where('id', 1)->value('currency');
    //  $this->ugx = BFCurency::where('id', 2)->value('currency');
    }
    
    public function updatedCurrency(){
       $this->currencyValue = Currencies::where('id', $this->currency)->value('currency_value');
    }
    
    public function edit($currency, $value){
        $this->currency = $currency;
        $this->currencyValue = $value;
    }
    
     public function remove($currency){
        $removeCurrency = Currencies::where('id', $currency)->update([
              'currency_value' => ''
            ]
          );
        if($removeCurrency){
            $this->reset();
           $selectedCurrency = Currencies::where('id', $currency)->first();
         $this->dispatchBrowserEvent('toastr:success', [
                'message' => $selectedCurrency->currency." has been removed!",
            ]); 
        }
        else{
            $this->dispatchBrowserEvent('toastr:success', [
                'message' => $selectedCurrency->currency." could not be removed!",
            ]); 
        }
        
    }
    
    
      public function change(){
          $this->validate([
              'currency' => 'required',
               'currencyValue' => 'nullable|numeric',
              ]);
      $update_currency = Currencies::where('id', $this->currency)->update([
         'currency_value' => $this->currencyValue 
          ]); 
          
          $selectedCurrency = Currencies::where('id', $this->currency)->first();
          
            $this->dispatchBrowserEvent('toastr:success', [
                'message' => $selectedCurrency->currency." has been updated successfully!",
            ]);
          
        //   if($bf){
        //   $this->bf = $bf->currency;
        //   $this->bifInput = 0;
        //   $this->dispatchBrowserEvent('toastr:success', [
        //         'message' => "Burundian francs has been updated successfully!",
        //     ]);
        //   }
        //   else{
        //       $this->dispatchBrowserEvent('toastr:warning', [
        //         'message' => "OOP! Something went wrong. Try again",
        //     ]);
        //   }
          
    } 
    
    public function render()
    {
        $currencies = Currencies::all();
        $customizedCurrencies = Currencies::where('currency_value', '!=', '')->orderBy('updated_at', 'DESC')->get();
        return view('livewire.admin.b-f-currency', compact('currencies', 'customizedCurrencies'));
    }
}
