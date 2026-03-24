 <div>
      <div class="row">
     <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                @if (Session::has('editOrderSuccess'))
     <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px; margin-top: 5px">
       <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> 
     <strong><i class="fas fa-check"></i></strong> {{ Session::get('editOrderSuccess') }} </div>
     @elseif(Session::has('editOrderFail'))
     <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px; margin-top: 5px">
     <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> 
     <strong>FAILED:</strong> {{ Session::get('editOrderFail') }} </div> 
     @endif
 <form wire:submit.prevent="updateOrder">
                <div class="card">
     <div class="card-header bg-primary text-white text-center">
     <h5>Edit Order ({{ $status }})</h5>
     </div>
     <div class="card-body">
          <div class="form-group">
               <label>Order ID</label>
               <input type="text" wire:model="orderId" class="form-control @error('orderId') is-invalid @enderror" /> 
               @error('orderId')
               <span class="invalid-feedback" role="alert">
               <strong>{{ $message }}</strong>
               </span>
               @enderror  
           </div>
         <div class="form-group">
             <label>Category</label>
             <select class="form-control @error('category') is-invalid @enderror" wire:model="category" disabled required>
                 <option value="">-- Select Category --</option>  
                 @foreach ($categories as $category)
               <option value="{{ $category->id }}">{{ $category->category }}</option>  
               @endforeach
             </select>
             @error('category')
             <span class="text-danger">{{ $message }}</span>
             @enderror 
           </div>
     
           <div class="form-group {{ $toggleService == 0 ? 'd-none' : '' }}">
               <label>Service</label>
               <select class="form-control @error('service') is-invalid @enderror" wire:model="service" disabled required>
                   <option value="">----- Select service -----</option>
                @foreach ($services as $service)
                <option value="{{ $service->id }}">ID-{{ $service->id }} {{ $service->service }}
                 ${{ $service->rate_per_1000 }} per 1000</option>
                @endforeach
            </select>
               @error('service')
               <span class="text-danger">{{ $message }}</span>
               @enderror 
             </div>
     
     <div class="row">
         <div class="col-md-6">
             <div class="form-group">
               <label>Link</label>
               <input type="text" wire:model="link" readonly class="form-control @error('link') is-invalid @enderror" required> 
               @error('link')
               <span class="invalid-feedback" role="alert">
               <strong>{{ $message }}</strong>
               </span>
               @enderror  
           </div>
         </div>
         <div class="col-md-6">
             <div class="form-group">
               <label>Start count</label>
               <input type="number" wire:model="startCount" class="form-control @error('startCount') is-invalid @enderror"> 
               @error('startCount')
               <span class="invalid-feedback" role="alert">
               <strong>{{ $message }}</strong>
               </span>
               @enderror  
           </div>
         </div>
     </div>
             
           
           <div class="form-group">
               <label>Comments (1 per line)</label>
               <textarea rows="4" wire:model="comment" readonly class="form-control @error('comment') is-invalid @enderror"></textarea> 
               @error('comment')
               <span class="invalid-feedback" role="alert">
               <strong>{{ $message }}</strong>
               </span>
               @enderror  
           </div>
     
     <div class="row">
     <div class="col">
         <div class="form-group">
             <label>Quantity</label>
             <input type="number" min="{{ isset($min_order) ? $min_order : '1' }}" readonly max="{{ isset($max_order) ? $max_order : '' }}" {{ $quantityToggler == 0 ? '' : 'readonly' }} wire:model="quantity" class="form-control @error('quantity') is-invalid @enderror" required> 
             <small class="{{ $range == 0 ? 'd-none' : '' }}">Min: <span>{{ $min_order }}</span> - Max: <span>{{ $max_order }}</span></small>
             @error('quantity')
             <span class="invalid-feedback" role="alert">
             <strong>{{ $message }}</strong>
             </span>
             @enderror  
         </div>
     </div>
     <div class="col">
         <div class="form-group">
             <label for="charge">Charge</label>

             <input type="text" wire:model="charge" class="form-control @error('charge') is-invalid @enderror" /> 
            @error('charge')
             <span class="invalid-feedback" role="alert">
             <strong>{{ $message }}</strong>
             </span>
             @enderror  
         </div>
     </div>
    
     </div>
      <div class="form-group">
         <label>Remains</label>
          <input type="text" wire:model="remains" class="form-control @error('remains') is-invalid @enderror" /> 
            @error('remains')
             <span class="invalid-feedback" role="alert">
             <strong>{{ $message }}</strong>
             </span>
             @enderror
     </div>
             <hr>
             <button type="submit" wire:loading.attr="disabled" class="btn btn-block btn-primary"><span><i class="fa fa-edit"></i> Edit Order</span>
             <div wire:loading wire:target="updateOrder" class="spinner-border spinner-border-sm"></div></button>
      
         
        
     </div>
             </form>
             
             </div>
     <div class="col-lg-4 col-md-4 col-sm-12 col-12">
         <div>
             <h5>User information</h5>
             <div>Username: {{ $username }}</div>
             <div>Email: {{ $email }}</div>
              <div class="{{ $phone == '' ? 'd-none' : '' }}">Phone: {{ $phone }}</div>
              <div>Status: {{ $status }}</div>
         </div>
<div class="form-group {{ (isset($description) && $description != '') ? '' : 'd-none' }}">
             <label>Description</label>
             <div class="card border p-3">
                  {!! !isset($description) ? '' : $description !!}
                   </div>
           </div>
     
     </div>
         </div>
             </div>
               