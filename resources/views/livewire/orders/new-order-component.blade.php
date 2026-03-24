<div>
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
             <div class="nk-block-head-content">
                 <h3 class="nk-block-title page-title">Hey {{ Auth::user()->name }}, Your balance is <span class="text-danger">${{ $money }}</span></h3>
             </div>
                
                <!-- .nk-block-head-content -->
                <div class="nk-block-head-content">
                    <div class="toggle-wrap nk-block-tools-toggle">
                        <span class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></span>
                        <div class="toggle-expand-content" data-content="pageMenu">
                            <ul class="nk-block-tools g-3">
                             <li class="nk-block-tools-opt"><a class="btn btn-primary text-white" data-toggle="modal" data-target="#info"><em class="icon ni ni-reports"></em>
                                 <span>Account Status @if($accountSpending<100) (New)
                                 @elseif($accountSpending>100 && $accountSpending<1000) (Junior)
                                 @elseif ($accountSpending>1000 && $accountSpending<2500) (Frequent)
                                 @elseif ($accountSpending>2500 && $accountSpending<15000) (Elite)
                                 @elseif ($accountSpending>15000 && $accountSpending<50000) (VIP)
                                 @elseif($accountSpending>50000) (Master) @endif
                                 </span></a></li>
                              <li class="nk-block-tools-opt"><a class="btn btn-danger text-white"><em class="icon ni ni-reports"></em><span>Account Spent (${{ $accountSpending }})</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div><!-- .nk-block-head-content -->
            </div><!-- .nk-block-between -->
        </div><!-- .nk-block-head -->
        <div class="nk-block">
            <div class="row g-gs">
                <div class="col-xxl-6">
                 <!-- .row -->
                 @if($advert != NULL)
                    <div class="alert alert-dark alert-dismissible mb-2">
       <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> 
         {!! $advert->advert !!} </div>
                 @endif
     
                 @if (Session::has('addOrderSuccess'))
     <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px; margin-top: 5px">
       <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> 
     <strong><i class="fas fa-check"></i></strong> {{ Session::get('addOrderSuccess') }} </div>
     @elseif(Session::has('addOrderFail'))
     <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px; margin-top: 5px">
     <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> 
     <strong>FAILED:</strong> {{ Session::get('addOrderFail') }} </div> 
     @endif
     <form method="POST" action="{{ route('order.store') }}">
         @csrf
                <div class="card">
     <div class="card-header bg-primary text-white text-center">
     <h5>
         <div class="d-flex justify-content-between">
             <div>New Order</div>
             <div>
                 <a class="btn btn-danger" href="{{ route('addFund.create') }}">Add Funds</a></div>
         </div></h5>
     </div>
     <div class="card-body">
         <div class="row">
     <div class="col-lg-8 col-md-8 col-sm-12 col-12">
         <div class="form-group">
             <label for="category">Category</label>
             <select class="form-control form-control-lg @error('category') is-invalid @enderror" wire:model="category" name="category" required>
                 <option value="">-- Select Category --</option>  
                 @foreach ($categories as $category)
               <option value="{{ $category->id }}">{{ $category->category }}</option>  
               @endforeach
             </select>
             @error('category')
             <span class="invalid-feedback" role="alert">
             <strong>{{ $message }}</strong>
             </span>
             @enderror 
           </div>
           <div wire:loading>Loading...</div>
     
           <div class="form-group {{ $toggleService == 0 ? 'd-none' : '' }}">
               <label for="service">Service</label>
               <select class="form-control form-control-lg @error('service') is-invalid @enderror" wire:model="service"  name="service" required>
                   <option value="">----- Select service -----</option>
                @foreach ($services as $service)
                <option value="{{ $service->id }}" class="{{$service->status == 2 ? 'text-primary' : '' }}">
                 ID-{{ $service->id }} {{ $service->service }} ${{ $service->rate_per_1000 }} per 1000
                 </option>
                @endforeach
            </select>
               @error('service')
               <span class="invalid-feedback" role="alert">
               <strong>{{ $message }}</strong>
               </span>
               @enderror 
             </div>
     
             <div class="form-group">
               <label for="link">Link</label>
               <input type="text" name="link" value="{{ old('link') }}" class="form-control form-control-lg @error('link') is-invalid @enderror" id="link" required> 
               @error('link')
               <span class="invalid-feedback" role="alert">
               <strong>{{ $message }}</strong>
               </span>
               @enderror  
           </div>
           
           <div class="form-group {{ $commentToggler == 0 ? 'd-none' : '' }}">
               <label for="comment">Comments (1 per line)</label>
               <textarea rows="4" wire:model="comment" name="comment" class="form-control form-control-lg @error('comment') is-invalid @enderror" id="comment"></textarea> 
               @error('comment')
               <span class="invalid-feedback" role="alert">
               <strong>{{ $message }}</strong>
               </span>
               @enderror  
           </div>
     
           <div class="form-group d-none">
             <label for="price">Price</label>
             <input type="text" wire:model="rate_per_1000" name="price" class="form-control form-control-lg @error('price') is-invalid @enderror" required> 
         </div>
     
     <div class="row">
     <div class="col">
         <div class="form-group">
             <label for="quantity">Quantity</label>
             <input type="number" min="{{ isset($min_order) ? $min_order : '1' }}" max="{{ isset($max_order) ? $max_order : '' }}" {{ $quantityToggler == 0 ? '' : 'readonly' }} wire:model="quantity" name="quantity" value="{{ old('quantity') }}" class="form-control form-control-lg @error('quantity') is-invalid @enderror" required> 
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
<label for="charge">Charge &nbsp; <div wire:loading wire:target="charge" class="spinner-border spinner-border-sm" role="status">
  <span class="sr-only">Loading...</span>
</div>
</label>
             <input type="text" wire:model="charge" name="charge" value="{{ old('charge') }}" class="form-control form-control-lg @error('charge') is-invalid @enderror" id="charge" readonly required> 
            @error('charge')
             <span class="invalid-feedback" role="alert">
             <strong>{{ $message }}</strong>
             </span>
             @enderror  
         </div>
     </div>
     </div>
             <hr>
             <button type="submit" wire:loading.attr="disabled" class="btn btn-block btn-primary">Submit</button>
     </div>
     <div class="col-lg-4 col-md-4 col-sm-12 col-12">
<div class="form-group {{ (isset($description) && $description != '') ? '' : 'd-none' }}">
             <label>Description</label>
             <div class="card border p-3">
                  {!! !isset($description) ? '' : $description !!}
                   </div>
           </div>
     
     </div>
         </div>
             
               
         </div>
         
        
     </div>
                </div>
             </form>
                </div><!-- .col -->
             <!-- .col -->
            </div><!-- .row -->
        </div><!-- .nk-block -->
     
     
     <div class="modal fade" id="info">
         <div class="modal-dialog modal-lg modal-dialog-centered">
           <div class="modal-content">
           
             <!-- Modal Header -->
             <div class="modal-header">
               <h4 class="modal-title">Account statuses</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
             </div>
             
             <!-- Modal body -->
             <div class="modal-body">
               <div class="row">
     <div class="col-lg-4 col-md-4 col-sm-12 col-12">
     <div class="card bg-primary text-white p-1 text-center">
         <b>NEW</b>
         HAVEN'T SPENT MORE THAN<br>
         <b>$100</b>
     </div>
     
     <div class="card bg-primary text-white p-1 text-center">
        <b>ELITE</b>
         HAVE SPENT MORE THAN<br>
        <b>$2,5K</b> 
     </div>
     </div>
     
     <div class="col-lg-4 col-md-4 col-sm-12 col-12">
         <div class="card bg-primary text-white p-1 text-center">
             <b>JUNIOR</b>
             HAVE SPENT MORE THAN<br>
            <b>$100</b> 
         </div>
         <div class="card bg-primary text-white p-1 text-center">
             <b>VIP</b>
     HAVE SPENT MORE THAN<br>
     <b>$15K</b>
         </div>
     </div>
     <div class="col-lg-4 col-md-4 col-sm-12 col-12">
         <div class="card bg-primary text-white p-1 text-center">
           <b>FREQUENT</b>
     HAVE SPENT MORE THAN <br>
     <b>$1K</b>
         </div>
         <div class="card bg-primary text-white p-1 text-center">
            <b>MASTER</b> 
             HAVE SPENT MORE THAN<br>
             <b>$50K</b>
         </div>
     </div>
               </div>
             </div>
             
           </div>
         </div>
       </div>
</div>
