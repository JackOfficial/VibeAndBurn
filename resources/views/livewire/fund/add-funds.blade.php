<form method="POST" action="{{ $endpoint }}">
    @csrf
    {{-- Hidden Trigger for Ads Modal --}}
    <a class="d-none" href="#" data-toggle="modal" data-target="#advert">Ads</a>

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
                        
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                {{-- Selection Logic --}}
                                <select class="form-control form-control-lg border-right-0" 
                                        style="background-color: #f8f9fa; border-radius: 4px 0 0 4px;" 
                                        wire:model="currency" required>
                                    <option value="">Select Currency</option>
                                    <option value="USD">USD</option>
                                    <option value="Tether">Tether [TRC20] (USDT)</option>
                                    <option value="ARS">ARS</option>
                                    <option value="AUD">AUD</option>
                                    <option value="BIF">BIF (Automatic)</option>
                                    <option value="BIFManual">BIF (Manual)</option>
                                    <option value="BGN">BGN</option>
                                    <option value="BRL">BRL</option>
                                    <option value="BWP">BWP</option>
                                    <option value="CAD">CAD</option>
                                    <option value="CFA">CFA</option>
                                    <option value="CHF">CHF</option>
                                    <option value="CNY">CNY</option>
                                    <option value="COP">COP</option>
                                    <option value="CRC">CRC</option>
                                    <option value="CZK">CZK</option>
                                    <option value="DKK">DKK</option>
                                    <option value="GBP">GBP</option>
                                    <option value="GHS">GHS</option>
                                    <option value="HKD">HKD</option>
                                    <option value="HUF">HUF</option>
                                    <option value="ILS">ILS</option>
                                    <option value="INR">INR</option>
                                    <option value="JPY">JPY</option>
                                    <option value="KES">KES</option>
                                    <option value="MAD">MAD</option>
                                    <option value="MOP">MOP</option>
                                    <option value="MUR">MUR</option>
                                    <option value="MWK">MWK</option>
                                    <option value="MXN">MXN</option>
                                    <option value="MYR">MYR</option>
                                    <option value="NGN">NGN</option>
                                    <option value="NOK">NOK</option>
                                    <option value="NZD">NZD</option>
                                    <option value="PEN">PEN</option>
                                    <option value="PHP">PHP</option>
                                    <option value="PLN">PLN</option>
                                    <option value="RUB">RUB</option>
                                    <option value="RWF">RWF</option>
                                    <option value="RWFManual">RWF (Manual)</option>
                                    <option value="SAR">SAR</option>
                                    <option value="SEK">SEK</option>
                                    <option value="SGD">SGD</option>
                                    <option value="SLL">SLL</option>
                                    <option value="THB">THB</option>
                                    <option value="TRY">TRY</option>
                                    <option value="TWD">TWD</option>
                                    <option value="TZS">TZS</option>
                                    <option value="UGX">UGX</option>
                                    <option value="VEF">VEF</option>
                                    <option value="XAF">XAF</option>
                                    <option value="XOF">XOF</option>
                                    <option value="ZAR">ZAR</option>
                                    <option value="ZMK">ZMK</option>
                                    <option value="ZMW">ZMW</option>
                                    <option value="ZWD">ZWD</option>
                                </select>
                                {{-- Currency Logic Preservation --}}
                                <input type="hidden" name="currency" value="{{ $getCurrency ?? '' }}" required />
                            </div>
                            
                            {{-- Amount Logic Preservation --}}
                            <input type="number" name="money" wire:model="money" 
                                   class="form-control form-control-lg @error('money') is-invalid @enderror" 
                                   id="money" placeholder="Enter Amount" required>
                        </div>
                        @error('money')
                            <span class="text-danger small" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- Rwanda Phone Logic Preservation --}}
                    <div class="form-group {{ (isset($currency) && $currency == "RWF") ? '' : 'd-none' }}">
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
                            
                            {{-- Gateway Logic Preservation --}}
                            @if($toggler == "Flutterwave")
                                <input type="number" name="amount" min="1" value="{{ $amount }}" class="form-control form-control-lg bg-white border-0 @error('amount') is-invalid @enderror" readonly required>
                            @elseif($toggler == "AfriPay")
                                <input type="hidden" name="amount" value="{{ $money }}" />
                                <input type="number" class="form-control form-control-lg bg-white border-0" min="1" value="{{ $amount }}" readonly required />
                            @endif
                        </div>
                        @error('amount')
                            <span class="text-danger small" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <p class="text-center small text-muted mb-0">Conversion matches your selected currency.</p>
                </div>
            </div>

            {{-- Hidden Data Logic Preservation --}}
            <div class="row mt-3">
                <div class="col-md-6">
                    <input type="hidden" name="comment" value="Some comment" />
                    <input type="hidden" name="client_token" value="{{ Auth::id() }}" />
                    <input type="hidden" name="return_url" value="https://vibeandburn.com/afripay-callback" />
                    <input type="hidden" name="firstname" wire:model="name" />
                    <input type="hidden" name="lastname" wire:model="name" />
                    <input type="hidden" name="street" value="" />
                    <input type="hidden" name="app_id" value="1e9850a2ff2c5e7c3ae46c4ab68557ea" />
                </div>
                <div class="col-md-6">
                    <input type="hidden" name="city" value="" />
                    <input type="hidden" name="state" value="" />
                    <input type="hidden" name="zip_code" value="" />
                    <input type="hidden" name="country" value="" />
                    <input type="hidden" name="email" wire:model="email" />
                    <input type="hidden" name="app_secret" value="JDJ5JDEwJDFlbFhF" />
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
</form>