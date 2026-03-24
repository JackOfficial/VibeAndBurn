<?php

namespace App\Http\Livewire\Fund;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\BFCurency;
use App\Models\Currencies;

class AddFunds extends Component
{
    public $currency;
    public $money, $feedback;
    public $email, $name;
    public $togglePhone = 0;
    public $afripay = ['USD', 'EUR', 'RWF', 'KES', 'KSH', 'BIF', 'UGX', 'TZS'];
    //public $workingCurrency = array('BIF', 'USD', 'UGX', 'KES');
    public $endpoint = "addFund";
    public $toggler = "Flutterwave";
    public $getCurrency;
    public $phone;
    public $toggleSubmit = 1;
    public $amount;
    public $getCurrencies;

    public function mount(){
        if(Auth::check()){
        $this->email = Auth::user()->email;
        $this->name = Auth::user()->name;
        $this->phone = substr(Auth::user()->phone, 3, 9);
        }
    }
    
    public function updatedPhone(){
      $this->validate([
          'phone' => 'required|string|min:9|max:9'
          ]);
          
          if($this->currency == "RWF"){
           if($this->phone != '' || strlen($this->phone) == 9){
               $this->toggleSubmit = 1; 
           }
           else{
                  $this->toggleSubmit = 0;
           }
        }
          
         User::where('id', Auth::id())->update(
             [
              'phone' => "250".$this->phone   
             ]); 
    }
    
    public function updatedCurrency(){
        if($this->currency == "BIFManual" || $this->currency == "RWFManual"){
          $phoneNumber = '+250791888471'; // Replace with the WhatsApp phone number
        $message = ($this->currency == "BIFManual") ? "Hello, I want to pay in BIF" : "Hello, I want to pay in RWF"; // Pre-filled message

        $url = "https://wa.me/{$phoneNumber}?text=" . urlencode($message);

        return redirect()->away($url);
        }
       elseif(in_array($this->currency, $this->afripay)){
        $this->togglePhone == 1;
        $this->endpoint = "https://afripay.africa/checkout/index.php";
        $this->toggler = "AfriPay";
        
       if($this->currency == "RWF"){
            // $this->endpoint = "/payInRwanda";
            if($this->money < 500 && $this->money != ""){
               $this->toggleSubmit = 0;
               session()->flash('minimunAmount', "Minimun amount to pay is 500 RWF");
           }
           elseif($this->phone != '' || strlen($this->phone) == 9){
               $this->toggleSubmit = 1; 
           }
           else{
                  $this->toggleSubmit = 1;
           }
        }
        
       }
       elseif($this->currency == "Tether"){
           return redirect("/payInTether");
       }
       else{
             $this->endpoint = "addFund";
       }
       
       $this->getCurrency = ($this->currency == "KES") ? 'KSH' : $this->currency;
       
        if($this->money != ''){
            $c = Currencies::where('currency', $this->currency)
            ->value('currency_value');
            if($c != ''){
                $this->amount = $this->money != '' ? $this->money/$c : '';
            }
          else{
            // send currency conversion API
         $endpoint = 'convert';
        $access_key = 'cbee96985754e4844413ba838ffb7e76';

        $from = $this->currency;
        $to = 'USD';

        // initialize CURL:
        $ch = curl_init('https://api.exchangeratesapi.io/v1/'.$endpoint.'?access_key='.$access_key.'&from='.$from.'&to='.$to.'&amount='.$this->money.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // get the JSON data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $conversionResult = json_decode($json, true);

        // access the conversion result
        $this->amount = $conversionResult['result'] ?? '';
        // end convert API  
          }
          
        
        ////////////////////////////////////////////
        
        // $endpoint = 'convert';
        // $access_key = 'VvpQERF7bPihDgN9xbPLfpR5eFHx90tG';
        // $from = $this->currency;
        // $to = 'USD';
        
// $curl = curl_init();
// curl_setopt_array($curl, array(
//   CURLOPT_URL => "https://api.apilayer.com/exchangerates_data/$endpoint?to=$to&from=$from&amount=$this->money",
//   CURLOPT_HTTPHEADER => array(
//     "Content-Type: text/plain",
//     "apikey: VvpQERF7bPihDgN9xbPLfpR5eFHx90tG"
//   ),
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "GET"
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// // Decode JSON response:
// $conversionResult = json_decode($response, true);
// $this->amount = $conversionResult['result'] ?? '';
///////////////////////

//  $endpoint = 'convert';
//         $access_key = 'YmovWajEc3wxoMaQdUClHFxRe3SZXYRx';
//         $from = $this->currency;
//         $to = 'USD';
        
// $curl = curl_init();
// curl_setopt_array($curl, array(
//   CURLOPT_URL => "https://api.apilayer.com/fixer/$endpoint?to=$to&from=$from&amount=$this->money",
//   CURLOPT_HTTPHEADER => array(
//     "Content-Type: text/plain",
//     "apikey: YmovWajEc3wxoMaQdUClHFxRe3SZXYRx"
//   ),
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "GET"
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// $conversionResult = json_decode($response, true);
// $this->amount = $conversionResult['result'] ?? '';
/////////////////////////

       // if($this->amount < 1){
     //       $this->feedback = "At least $1";
         // $this->toggleSubmit = 0; 
    //    }
       // else{
    //        $this->toggleSubmit = 1; 
        //    $this->feedback = ""; 
      //  }
      }}
    
    public function updatedMoney()
    {
        if(in_array($this->currency, $this->afripay)){
        $this->togglePhone = 1;
        $this->endpoint = "https://afripay.africa/checkout/index.php";
         $this->toggler = "AfriPay";
         
         if($this->currency == "RWF"){
            // $this->endpoint = "/payInRwanda";
             if($this->money < 500 && $this->money != ""){
               $this->toggleSubmit = 0;
               session()->flash('minimunAmount', "Minimun amount to pay is 500 RWF");
           }
           elseif($this->phone != '' || strlen($this->phone) == 9){
               $this->toggleSubmit = 1; 
           }
           else{
                  $this->toggleSubmit = 1;
           }
        }
       }
       else{
             $this->endpoint = "addFund";
       }
       
      if($this->currency != ''){
           $c = Currencies::where('currency', $this->currency)
            ->value('currency_value');
            if($c != ''){
                $this->amount = $this->money != '' ? $this->money/$c : '';
            }
          else{
            // send currency conversion API
         $endpoint = 'convert';
        $access_key = 'cbee96985754e4844413ba838ffb7e76';

        $from = $this->currency;
        $to = 'USD';

        // initialize CURL:
        $ch = curl_init('https://api.exchangeratesapi.io/v1/'.$endpoint.'?access_key='.$access_key.'&from='.$from.'&to='.$to.'&amount='.$this->money.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // get the JSON data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $conversionResult = json_decode($json, true);

        // access the conversion result
            $this->amount = $conversionResult['result'] ?? '';
        // end convert API  
          }
      //if($this->amount < 1){
          //  $this->toggleSubmit = 0; 
          //  $this->feedback = "At least $1";
      //  }
     //   else{
      //      $this->toggleSubmit = 1; 
         //   $this->feedback = "";
     //   }
        
        //////////////////////////////////////////////////////////////////////
        
//          $endpoint = 'convert';
//         $access_key = 'VvpQERF7bPihDgN9xbPLfpR5eFHx90tG';

//         $from = $this->currency;
//         $to = 'USD';
        
// $curl = curl_init();
// curl_setopt_array($curl, array(
//   CURLOPT_URL => "https://api.apilayer.com/exchangerates_data/$endpoint?to=$to&from=$from&amount=$this->money",
//   CURLOPT_HTTPHEADER => array(
//     "Content-Type: text/plain",
//     "apikey: VvpQERF7bPihDgN9xbPLfpR5eFHx90tG"
//   ),
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "GET"
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// // Decode JSON response:
// $conversionResult = json_decode($response, true);

///////////////////////////////////

//  $endpoint = 'convert';
//         $access_key = 'YmovWajEc3wxoMaQdUClHFxRe3SZXYRx';
//         $from = $this->currency;
//         $to = 'USD';
        
// $curl = curl_init();
// curl_setopt_array($curl, array(
//   CURLOPT_URL => "https://api.apilayer.com/fixer/$endpoint?to=$to&from=$from&amount=$this->money",
//   CURLOPT_HTTPHEADER => array(
//     "Content-Type: text/plain",
//     "apikey: YmovWajEc3wxoMaQdUClHFxRe3SZXYRx"
//   ),
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "GET"
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// $conversionResult = json_decode($response, true);
/////////////////////////
// $this->amount = $conversionResult['result'] ?? '';

        // if($this->amount < 1){
        //     $this->feedback = "At least $1";
        //   $this->toggleSubmit = 0; 
        // }
        // else{
        //     $this->feedback = "";
        //     $this->toggleSubmit = 1; 
        // }
      }}
    public function render()
    {
        return view('livewire.fund.add-funds');
    }
}
