<div class="row">
    <div class="col-md-6">
<div class="card">
    <div class="card-header bg-dark text-light">
       <i class="fas fa-coins"></i> Set Currencies 
    </div>
    <div class="card-body">
        <form wire:submit.prevent="change">
        <div class="form-group">
            <label>Select Currency</label>
           <select wire:model="currency" class="form-control" /> 
           <option value="">Select Currency</option>
           @foreach($currencies as $currency)
           <option value="{{ $currency->id }}">{{ $currency->currency }}</option>
           @endforeach
           </select>
           @error('currency') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Currency Value <div wire:loading wire:target="currency">Loading...</div></label>
           <input type="number" step="any" wire:model="currencyValue" placeholder="Set currency value" class="form-control" /> 
           @error('currencyValue') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    <button class="btn btn-dark btn-sm rounded-pill mt-1"><i class="fa fa-edit"></i> Change
     <div wire:loading wire:target="change" class="spinner-border spinner-border-sm"></div>
    </button>
    </form>
    </div>
</div>
 </div>
   <div class="col-md-6">
     <div><b>{{ count($customizedCurrencies) }} Customized Currencies:</b></div>
     <ul>
     @forelse($customizedCurrencies as $customizedCurrency)
     <li><span type="button" wire:click="edit({{ $customizedCurrency->id }}, {{ $customizedCurrency->currency_value }})">{{ $customizedCurrency->currency }}: <span class="text-success">{{ $customizedCurrency->currency_value }}</span></span> &nbsp; 
     <span wire:click="remove({{ $customizedCurrency->id }})"><i class="fas fa-times text-danger"></i></span></li>
     @empty
     <li>No customized currency</li>
     @endforelse
     </ul>
       </div>
</div>
