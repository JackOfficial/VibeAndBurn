<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\wallet as Client_wallet;
use App\Models\fund;
use App\Models\BFCurency;
use App\Models\Currencies;

class Wallet extends Component
{
        public $currency, $money;
        public $thisWallet;
        public $feedback;
        public $bf;
        
        protected $rules = [
           'currency' => 'required',
           'money' => 'required',
         ];
    
    public function mount(){
    
      $this->currency = "USD";
      $this->bf = BFCurency::where('id', 1)->value('currency');
        
    }
    
    public function edit($id){
    $this->thisWallet = Client_wallet::join('users', 'wallets.user_id', '=', 'users.id')
    ->where('wallets.id', $id)
    ->select('wallets.*', 'users.name', 'users.email')
    ->first();
    }
    
    public function increaseFund(){
        $this->validate();
        
        if($this->currency == "BIF" || $this->currency == "KES"){
             $c = Currencies::where('currency', $this->currency)
            ->value('currency_value');
              $result = $this->money/$c;
              
            }
            else{
                $result = $this->money;
               
            }
//             else{
//                 //Currency Conversion
//       $endpoint = 'convert';
// $access_key = 'cbee96985754e4844413ba838ffb7e76';

// $from = $this->currency;
// $to = 'USD';
// // initialize CURL:
// $ch = curl_init('https://api.exchangeratesapi.io/v1/'.$endpoint.'?access_key='.$access_key.'&from='.$from.'&to='.$to.'&amount='.$this->money.'');
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// // get the JSON data:
// $json = curl_exec($ch);
// curl_close($ch);

// // Decode JSON response:
// $conversionResult = json_decode($json, true);
// $result = $conversionResult['result']; 
//             }

        $wallet = Client_wallet::where('user_id', $this->thisWallet->user_id)->update([
            'money' => $this->thisWallet->money + $result,
        ]);
        
        if($wallet){
           $fund = fund::create([
        'user_id' => $this->thisWallet->user_id,
        'method' => $this->currency,
        'amount' => $result,
    ]); 
    
     if($fund){
$this->feedback = "$" . $result . " has been added to this wallet successfully";
 $this->emit('reloadBrowser');
 }
 else{
     $this->feedback = "Wallet could not be added";
      $this->emit('reloadBrowser');
 }
 
 $this->wallets = Client_wallet::join('users', 'wallets.user_id', '=', 'users.id')
    ->select('wallets.*', 'users.name', 'users.email')
     ->where('wallets.id', $this->thisWallet->id)
     ->get();
 
 $this->dispatchBrowserEvent('closeEditWalletModel');
        } 
    }
    
    public function decreaseFund(){
        
        $this->validate();
        
           if($this->currency == "BIF" || $this->currency == "KES"){
             $c = Currencies::where('currency', $this->currency)
            ->value('currency_value');
              $result = $this->money/$c;
            }
            else{
                $result = $this->money;
            }
//             else{
//                 //Currency Conversion
//       $endpoint = 'convert';
// $access_key = 'cbee96985754e4844413ba838ffb7e76';

// $from = $this->currency;
// $to = 'USD';
// // initialize CURL:
// $ch = curl_init('https://api.exchangeratesapi.io/v1/'.$endpoint.'?access_key='.$access_key.'&from='.$from.'&to='.$to.'&amount='.$this->money.'');
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// // get the JSON data:
// $json = curl_exec($ch);
// curl_close($ch);

// // Decode JSON response:
// $conversionResult = json_decode($json, true);
// $result = $conversionResult['result']; 
//             }

    $wallet = Client_wallet::where('user_id', '=', $this->thisWallet->user_id)->update([
            'money' => $this->thisWallet->money - $result,
        ]);
    
     if($wallet){
$this->feedback = "$" . $result . " was removed from this wallet.";
 }
 else{
        $this->feedback = "Wallet could not be added";
 }
 
  $this->dispatchBrowserEvent('closeEditWalletModel');
 
 $this->wallets = Client_wallet::join('users', 'wallets.user_id', '=', 'users.id')
    ->select('wallets.*', 'users.name', 'users.email')
     ->where('wallets.id', $this->thisWallet->id)
     ->get();
     
    }
    
    public function render()
    {
        $wallets = Client_wallet::join('users', 'wallets.user_id', '=', 'users.id')
    ->select('wallets.*', 'users.name', 'users.email')
     ->orderBy('wallets.money', 'ASC')
     ->get();
     
     $walletsCounter = Client_wallet::join('users', 'wallets.user_id', '=', 'users.id')
    ->count();
    
    $walletsTotal = Client_wallet::join('users', 'wallets.user_id', '=', 'users.id')
    ->sum('money');
    
        return view('livewire.admin.wallet', compact('wallets', 'walletsCounter', 'walletsTotal'));
    }
}
