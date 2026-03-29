<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12 col-12">
            
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.opacity>
                @if (Session::has('editOrderSuccess'))
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ Session::get('editOrderSuccess') }}
                    </div>
                @elseif(Session::has('editOrderFail'))
                    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ Session::get('editOrderFail') }}
                    </div>
                @endif
            </div>

            <div class="card card-outline card-primary shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-shopping-cart text-primary mr-2"></i>
                        Edit Order #{{ $orderId }} 
                        <span class="badge badge-soft-info ml-2">{{ strtoupper($status) }}</span>
                    </h5>
                </div>

                <form wire:submit.prevent="updateOrder">
                    <div class="card-body">
                        <div class="bg-light p-3 rounded mb-4 border">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="small font-weight-bold text-muted uppercase">Category</label>
                                    <p class="mb-2 text-dark">{{ $categories->find($category)->category ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="small font-weight-bold text-muted uppercase">Service ID</label>
                                    <p class="mb-2 text-dark">{{ $service }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="small font-weight-bold text-muted uppercase">Target Link</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control bg-white" value="{{ $link }}" readonly>
                                        <div class="input-group-append">
                                            <a href="{{ $link }}" target="_blank" class="btn btn-outline-secondary"><i class="fas fa-external-link-alt"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Start Count</label>
                                    <input type="number" wire:model="startCount" class="form-control @error('startCount') is-invalid @enderror" placeholder="0"> 
                                    @error('startCount') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Remains</label>
                                    <input type="number" wire:model="remains" class="form-control @error('remains') is-invalid @enderror" placeholder="0"> 
                                    @error('remains') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Quantity</label>
                                    <input type="number" wire:model="quantity" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Charge ($)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                        <input type="text" wire:model="charge" class="form-control @error('charge') is-invalid @enderror">
                                    </div>
                                    @error('charge') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>
                        </div>

                        @if($comment)
                        <div class="form-group mt-2">
                            <label class="font-weight-bold text-muted">Customer Comments</label>
                            <textarea rows="3" wire:model="comment" readonly class="form-control bg-light border-0" style="font-family: monospace; font-size: 0.9rem;"></textarea> 
                        </div>
                        @endif
                    </div>

                    <div class="card-footer bg-white border-top-0">
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary btn-lg btn-block shadow-sm">
                            <span wire:loading.remove wire:target="updateOrder"><i class="fa fa-save mr-2"></i> Update Order Details</span>
                            <span wire:loading wire:target="updateOrder"><i class="fas fa-circle-notch fa-spin mr-2"></i> Saving Changes...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title font-weight-bold mb-0">User Profile</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                       Avatar: {{ $avatar }}
    <div class="bg-soft-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2 shadow-sm" 
         style="width: 60px; height: 60px; overflow: hidden;">
        
        @if(isset($avatar) && $avatar != '')
            <img src="{{ $avatar }}" 
                 alt="{{ $username }}" 
                 style="width: 100%; height: 100%; object-fit: cover;">
        @else
            <span class="h4 text-primary font-weight-bold mb-0">
                {{ strtoupper(substr($username, 0, 1)) }}
            </span>
        @endif
        
    </div>
    <h6 class="font-weight-bold mb-0 text-dark">{{ $username }}</h6>
    <small class="text-muted">{{ $email }}</small>
</div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Status:</span>
                        <span class="badge badge-success">Active Customer</span>
                    </div>
                    @if($phone)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Phone:</span>
                        <span class="font-weight-bold">{{ $phone }}</span>
                    </div>
                    @endif
                </div>
            </div>

            @if(isset($description) && $description != '')
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title font-weight-bold mb-0">Service Instructions</h5>
                </div>
                <div class="card-body small text-muted">
                    {!! $description !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .badge-soft-info { background-color: #e1f5fe; color: #01579b; border: 1px solid #b3e5fc; }
    .bg-soft-primary { background-color: rgba(0, 123, 255, 0.1); }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
</style>