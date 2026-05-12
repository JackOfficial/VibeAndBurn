<div class="container-fluid">
    <div class="row">
        <div class="col-lg-7 col-md-9 col-sm-12">
            
            {{-- Alert Messages --}}
            @if (session()->has('adminAddFundSuccess'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-check-circle mr-2"></i> {{ session('adminAddFundSuccess') }}
                </div>
            @elseif(session()->has('adminAddFundFail'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('adminAddFundFail') }}
                </div>
            @endif

            <div class="card card-warning card-outline shadow">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold text-dark">
                        <i class="fas fa-wallet mr-2 text-warning"></i> Deposit Funds to User Account
                    </h3>
                </div>

                {{-- Using wire:submit.prevent to bypass traditional form submission --}}
                <form wire:submit.prevent="store">
                    <div class="card-body">
                        
                        {{-- User Selection Search --}}
                        <div class="form-group position-relative">
                            <label for="user">Target User <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" 
                                       class="form-control @error('userId') is-invalid @enderror" 
                                       placeholder="Search user by name or email..." 
                                       wire:model.live.debounce.300ms="search"
                                       {{ $userId ? 'readonly' : '' }}>
                                @if($userId)
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-danger" type="button" wire:click="$set('userId', null)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            {{-- Search Results Dropdown --}}
                            @if(!empty($search) && !empty($users) && !$userId)
                                <div class="list-group position-absolute w-100 shadow-lg" style="z-index: 1050; max-height: 200px; overflow-y: auto;">
                                    @foreach($users as $user)
                                        <button type="button" 
                                                wire:click="selectUser({{ $user->id }}, '{{ $user->name }}')" 
                                                class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1 font-weight-bold">{{ $user->name }}</h6>
                                            </div>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                            
                            @error('userId')
                                <span class="text-danger small"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Currency & Amount --}}
                        <label for="money">Transaction Amount <span class="text-danger">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <select class="form-control bg-light font-weight-bold" 
                                        wire:model.live="currency"
                                        style="border-radius: 4px 0 0 4px; border-right: 0; min-width: 80px;">
                                    <option value="BIF">BIF</option>
                                    <option value="USD">USD</option>
                                    <option value="RWF">RWF</option>
                                </select>
                            </div>
                            <input type="number" 
                                   step="0.01" 
                                   wire:model.live="money"
                                   class="form-control @error('money') is-invalid @enderror" 
                                   placeholder="0.00" 
                                   required />
                            <div class="input-group-append">
                                <span class="input-group-text bg-white"><i class="fas fa-coins text-warning"></i></span>
                            </div>
                        </div>

                        {{-- Real-time Conversion Preview (Pure Livewire) --}}
                        @if($money > 0)
                            <div class="alert alert-info border-0 shadow-sm animate__animated animate__fadeIn">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calculator fa-2x mr-3"></i>
                                    <div>
                                        <span class="d-block">Final Wallet Addition: <strong>{{ $this->convertedAmount }} USD</strong></span>
                                        @if($currency === 'BIF')
                                            <small>(Current Rate: 1 USD = {{ $bifRate }} BIF)</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="callout callout-info mt-4">
                            <p class="text-muted small mb-0">
                                <i class="fas fa-info-circle mr-1"></i> 
                                This action will immediately update the user's balance in the <code>wallets</code> table and log the transaction.
                            </p>
                        </div>
                    </div>

                    <div class="card-footer bg-white">
                        <button type="submit" 
                                class="btn btn-warning btn-lg btn-block font-weight-bold shadow-sm"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <i class="fa fa-plus-circle mr-1"></i> Complete Deposit
                            </span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin mr-1"></i> Processing...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>