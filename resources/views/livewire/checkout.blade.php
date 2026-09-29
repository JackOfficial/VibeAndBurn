<div>

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
                        <p class="mb-2 text-dark font-weight-bold">Select currency and enter amount (Not below $1)</p>
                        
                            <input type="number" name="money" wire:model="money" 
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
                                @if(isset($currency) && $currency == "RWF") minlength="9" maxlength="9" @endif 
                                value="{{ old('phone') }}" 
                                class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                placeholder="Ex: 7xxxxxxxx" wire:model="phone" name="phone" 
                                min="{{ (isset($currency) && $currency == "RWF") ? 9 : '' }}" 
                                max="{{ (isset($currency) && $currency == "RWF") ? 9 : '' }}" 
                                {{ (isset($currency) && $currency == "RWF") ? 'required' : '' }} />
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
                        <div class="input-group mb-3 {{ $toggleSubmit == 1 && $money != '' ? 'border border-success rounded' : '' }}">
                            <div class="input-group-prepend">
                                <span wire:loading.remove wire:target="money" class="input-group-text bg-white border-0">USD</span>
                                <span wire:loading wire:loading.delay wire:target="money" class="input-group-text bg-white border-0">
                                    <i class="fas fa-spinner fa-spin text-primary"></i>
                                </span>
                            </div>
                        </div>
                        @error('amount')
                            <span class="text-danger small" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer bg-white border-top">
            <button wire:loading.attr="disabled" type="submit" name="submit" 
                {{ $toggleSubmit == 0 || $amount == null ? 'disabled' : '' }} 
                class="btn btn-block btn-primary btn-lg font-weight-bold shadow-sm">
                <span wire:loading.remove>Pay Now!</span>
            </button>
            <div class="text-center mt-2">
                <small class="text-muted"><i class="fas fa-lock mr-1"></i> Secure payment processed via {{ $toggler }}</small>
            </div>
        </div>
    </div>
</div>