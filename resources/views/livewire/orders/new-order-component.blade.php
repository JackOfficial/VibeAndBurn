<div>
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Hey {{ Auth::user()->name }}, Your balance is <span class="text-danger">${{ number_format($money, 2) }}</span></h3>
                </div>
                
                <div class="nk-block-head-content">
                    <div class="toggle-wrap nk-block-tools-toggle">
                        <span class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></span>
                        <div class="toggle-expand-content" data-content="pageMenu">
                            <ul class="nk-block-tools g-3">
                                <li class="nk-block-tools-opt">
                                    <a class="btn btn-primary text-white" data-toggle="modal" data-target="#info">
                                        <em class="icon ni ni-reports"></em>
                                        <span>Account Status 
                                            @if($accountSpending < 100) (New)
                                            @elseif($accountSpending < 1000) (Junior)
                                            @elseif($accountSpending < 2500) (Frequent)
                                            @elseif($accountSpending < 15000) (Elite)
                                            @elseif($accountSpending < 50000) (VIP)
                                            @else (Master) @endif
                                        </span>
                                    </a>
                                </li>
                                <li class="nk-block-tools-opt"><a class="btn btn-danger text-white"><em class="icon ni ni-reports"></em><span>Account Spent (${{ number_format($accountSpending, 2) }})</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="nk-block">
            <div class="row g-gs">
                <div class="col-xxl-6">
                    @if($advert)
                        <div class="alert alert-dark alert-dismissible mb-2">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> 
                            {!! $advert->advert !!}
                        </div>
                    @endif

                    {{-- Livewire uses wire:submit.prevent to bypass traditional form submission --}}
                    <form wire:submit.prevent="store">
                        <div class="card card-bordered">
                            <div class="card-header bg-primary text-white text-center">
                                <h5 class="m-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>New Order</div>
                                        <div>
                                            <a class="btn btn-sm btn-danger" href="{{ route('addFund.create') }}">Add Funds</a>
                                        </div>
                                    </div>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        {{-- Category Selection --}}
                                        <div class="form-group">
                                            <label class="form-label" for="category">Category</label>
                                            <select class="form-control form-control-lg @error('category') is-invalid @enderror" wire:model="category" id="category" required>
                                                <option value="">-- Select Category --</option>  
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->category }}</option>  
                                                @endforeach
                                            </select>
                                            @error('category') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- Service Selection --}}
                                        <div class="form-group {{ $toggleService == 0 ? 'd-none' : '' }}">
                                            <label class="form-label" for="service">Service</label>
                                            <div class="form-control-wrap">
                                                <select class="form-control form-control-lg @error('service') is-invalid @enderror" wire:model="service" id="service" required>
                                                    <option value="">----- Select service -----</option>
                                                    @foreach ($services as $serv)
                                                        <option value="{{ $serv->id }}">
                                                            ID-{{ $serv->id }} {{ $serv->service }} - ${{ $serv->rate_per_1000 }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('service') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        {{-- Link Input --}}
                                        <div class="form-group">
                                            <label class="form-label" for="link">Link</label>
                                            <input type="url" wire:model.defer="link" class="form-control form-control-lg @error('link') is-invalid @enderror" id="link" placeholder="https://..." required> 
                                            @error('link') <span class="invalid-feedback">{{ $message }}</span> @enderror  
                                        </div>
                                        
                                        {{-- Custom Comments --}}
                                        <div class="form-group {{ $commentToggler == 0 ? 'd-none' : '' }}">
                                            <label class="form-label" for="comment">Comments (1 per line)</label>
                                            <textarea rows="4" wire:model="comment" class="form-control form-control-lg @error('comment') is-invalid @enderror" id="comment" placeholder="Place each comment on a new line"></textarea> 
                                            @error('comment') <span class="invalid-feedback">{{ $message }}</span> @enderror  
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="quantity">Quantity</label>
                                                    <input type="number" {{ $quantityToggler != 0 ? 'readonly' : '' }} wire:model="quantity" class="form-control form-control-lg @error('quantity') is-invalid @enderror" id="quantity" required> 
                                                    @if($range != 0)
                                                        <small class="form-text text-muted">Min: {{ $min_order }} - Max: {{ $max_order }}</small>
                                                    @endif
                                                    @error('quantity') <span class="invalid-feedback">{{ $message }}</span> @enderror  
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="charge">
                                                        Total Charge 
                                                        <div wire:loading wire:target="quantity, service" class="spinner-border spinner-border-sm text-primary ml-1" role="status"></div>
                                                    </label>
                                                    <div class="form-control-wrap">
                                                        <div class="form-icon form-icon-left"><em class="icon ni ni-sign-usd"></em></div>
                                                        <input type="text" wire:model="charge" class="form-control form-control-lg" id="charge" readonly> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="preview-hr">
                                        
                                        <button type="submit" wire:loading.attr="disabled" class="btn btn-lg btn-block btn-primary">
                                            <span wire:loading.remove wire:target="store">Submit Order</span>
                                            <span wire:loading wire:target="store">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                Processing...
                                            </span>
                                        </button>
                                    </div>

                                    {{-- Description Side Panel --}}
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group {{ (isset($description) && $description != '') ? '' : 'd-none' }}">
                                            <label class="form-label">Service Details</label>
                                            <div class="card card-bordered bg-light">
                                                <div class="card-inner">
                                                    {!! $description !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal for Account Status (kept the same logic) --}}
    @include('livewire.orders.partials.status-modal') 
</div>