<div class="row">
    <div class="col-md-6">
<div class="card">
    <div class="card-header bg-dark text-light">
       <i class="fas fa-coins"></i> Set Rate 
    </div>
    <div class="card-body">
        <form wire:submit.prevent="change">
        <div class="form-group">
            <label>Rate <div wire:loading wire:target="change">Loading...</div></label>
           <input type="number" step="any" wire:model="rate" placeholder="Set rate" class="form-control" /> 
           @error('rate') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    <button class="btn btn-dark btn-sm rounded-pill mt-1"><i class="fa fa-edit"></i> Set
     <div wire:loading wire:target="change" class="spinner-border spinner-border-sm"></div>
    </button>
    </form>
    </div>
</div>
 </div>
   <div class="col-md-6">
     
       </div>
</div>
