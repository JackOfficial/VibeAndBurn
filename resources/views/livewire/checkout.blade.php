<div>
    <form wire:submit.prevent="placeOrder">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <h5 class="m-0"><i class="fas fa-plus-circle mr-2"></i> Add Funds</h5>
            </div>
            
            <div class="card-body">
                {{-- Feedback Messages --}}
                @if(session()->has('minimunAmount'))
                    <div class="alert alert-danger py-2 mb-3">
                        <small><strong>Error:</strong> {{ session()->get('minimunAmount') }}</small>
                    </div>
                @endif

                @if(session()->has('message'))
                    <div class="alert alert-success py-2 mb-3">
                        <small><strong>Success:</strong> {{ session()->get('message') }}</small>
                    </div>
                @endif

                @if(session()->has('notActive'))
                    <div class="alert alert-warning py-2 mb-3">
                        <div class="small">The service is temporarily in maintenance.</div>
                        <small class="text-danger font-weight-bold">{{ session()->get('notActive') }}</small>
                    </div>
                @endif

                <div class="row">
                    {{-- User Input Column --}}
                    <div class="col-lg-8 col-md-8 col-sm-12 col-12 border-right">
                        <div class="form-group">
                            <p class="mb-2 text-dark font-weight-bold">Enter amount (Not below $1)</p>
                            <input type="number" step="any" wire:model.live="money" 
                                   class="form-control form-control-lg @error('money') is-invalid @enderror" 
                                   id="money" placeholder="Enter Amount" required>
                     
                            @error('money')
                                <span class="text-danger small" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Rwanda Phone Logic Preservation --}}
                        <div class="form-group">
                            <label class="font-weight-bold small">Phone number</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">+250</span>
                                </div>
                                <input type="text" 
                                       maxlength="9" 
                                       class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                       placeholder="Ex: 7xxxxxxxx" wire:model.live="phone" required />
                            </div>
                            @error('phone')
                                <span class="text-danger small" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    {{-- Summary/Preview Column --}}
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12 bg-light rounded py-3">
                        <label class="text-muted small overline-title font-weight-bold">Summary</label>
                        <div class="form-group mt-2">
                            <div class="p-2 bg-white rounded border {{ $toggleSubmit == 1 && $money != '' ? 'border-success' : 'border-light' }}">
                                <span class="text-muted small d-block">Amount to Add:</span>
                                <strong class="text-dark font-weight-bold h5">
                                    <span wire:loading.remove wire:target="money">{{ $money ? number_format($money, 2) : '0.00' }}</span>
                                    <span wire:loading wire:target="money"><i class="fas fa-spinner fa-spin text-primary"></i></span>
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white border-top">
                <button wire:loading.attr="disabled" type="submit" 
                    {{ $toggleSubmit == 0 || empty($money) ? 'disabled' : '' }} 
                    class="btn btn-block btn-primary btn-lg font-weight-bold shadow-sm">
                    <span wire:loading.remove wire:target="placeOrder">Pay Now!</span>
                    <span wire:loading wire:target="placeOrder"><i class="fas fa-spinner fa-spin mr-1"></i> Processing Payment...</span>
                </button>
                <div class="text-center mt-2">
                    <small class="text-muted"><i class="fas fa-lock mr-1"></i> Secure payment processed via InTouch</small>
                </div>
            </div>
        </div>
    </form>
</div>