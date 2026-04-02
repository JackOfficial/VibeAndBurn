<div x-data="{ 
    showFeedback: true,
    init() {
        $watch('showFeedback', value => {
            if(value) setTimeout(() => this.showFeedback = false, 5000)
        })
    }
}">
    <div class="p-2" x-show="showFeedback" x-transition:leave="transition ease-in duration-300">
        @if (session()->has('deleteWalletSuccess') || $feedback)
            <div class="alert {{ session()->has('deleteWalletSuccess') ? 'alert-success' : 'alert-danger' }} alert-dismissible fade show shadow-sm border-0" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas {{ session()->has('deleteWalletSuccess') ? 'fa-check-circle' : 'fa-exclamation-circle' }} mr-2"></i>
                    <span>{{ session('deleteWalletSuccess') ?? $feedback }}</span>
                </div>
                <button type="button" class="close" @click="showFeedback = false">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>

    <div class="card card-outline card-primary shadow-lg border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas fa-wallet text-primary mr-2"></i> Wallet Management
                </h3>
                
                <div class="stats-badges d-none d-md-block">
                    <span class="badge badge-pill badge-info py-2 px-3 mr-2 shadow-sm">
                        <i class="fas fa-users mr-1"></i> {{ $walletsCounter }} Users
                    </span>
                    <span class="badge badge-pill badge-success py-2 px-3 shadow-sm">
                        <i class="fas fa-chart-line mr-1"></i> Total: ${{ number_format($walletsTotal, 2) }}
                    </span>
                </div>
            </div>

            <hr class="my-2">

            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0">
                                <i class="fas fa-search text-muted" wire:loading.remove wire:target="search"></i>
                                <i class="fas fa-spinner fa-spin text-primary" wire:loading wire:target="search"></i>
                            </span>
                        </div>
                        <input type="text" wire:model.debounce.400ms="search" class="form-control border-left-0 bg-light" placeholder="Search name or email...">
                    </div>
                </div>

                <div class="col-md-7 mt-3 mt-md-0">
                    <div x-data="{ 
                        calcMoney: '', 
                        calcCurrency: 'RWF', 
                        res: '',
                        loading: false,
                        async convert() {
                            if (!this.calcMoney) { this.res = ''; return; }
                            this.loading = true;
                            try {
                                let response = await fetch(`/api/convert-currency?from=${this.calcCurrency}&to=USD&amount=${this.calcMoney}`);
                                let data = await response.json();
                                this.res = data.result ? parseFloat(data.result).toFixed(2) : '0.00';
                            } catch (e) { this.res = 'Error'; }
                            finally { this.loading = false; }
                        }
                    }" class="d-flex align-items-center justify-content-md-end">
                        <span class="small text-muted mr-2 font-weight-bold"><i class="fas fa-calculator mr-1"></i> Quick Rates:</span>
                        <div class="input-group input-group-sm" style="max-width: 300px;">
                            <input type="number" x-model="calcMoney" @keyup.debounce.500ms="convert()" class="form-control" placeholder="Amt">
                            <select x-model="calcCurrency" @change="convert()" class="form-control border-left-0 border-right-0" style="max-width: 80px;">
                                <option value="RWF">RWF</option>
                                <option value="EUR">EUR</option>
                                <option value="GBP">GBP</option>
                            </select>
                            <div class="input-group-append">
                                <span class="input-group-text bg-white font-weight-bold text-success">
                                    <i x-show="loading" class="fas fa-spinner fa-spin mr-1"></i>
                                    $<span x-text="res || '0.00'"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-borderless mb-0">
                    <thead class="bg-light text-uppercase small font-weight-bold">
                        <tr>
                            <th class="pl-4">#</th>
                            <th>User Details</th>
                            <th>Current Balance</th>
                            <th>Date Joined</th>
                            <th class="text-right pr-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($wallets as $wallet)
                        <tr wire:key="wallet-{{ $wallet->id }}" class="border-bottom" x-data="{ showHistory: false }">
                            <td class="align-middle pl-4 text-muted">
                                {{ ($wallets->currentPage() - 1) * $wallets->perPage() + $loop->iteration }}
                            </td>
                            
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3 shadow-sm rounded-circle d-flex align-items-center justify-content-center bg-light" 
                                         style="width: 42px; height: 42px; min-width: 42px; overflow: hidden; border: 1px solid #dee2e6;">
                                        @if($wallet->user && $wallet->user->avatar)
                                            <img src="{{ $wallet->user->avatar }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <span class="font-weight-bold text-primary">{{ strtoupper(substr($wallet->user->name ?? 'U', 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bold text-dark">{{ $wallet->user->name ?? 'Deleted User' }}</span>
                                        <small class="text-muted">{{ $wallet->user->email ?? 'N/A' }}</small>
                                        @if($wallet->funds_max_created_at)
                                            <span class="text-success mt-1" style="font-size: 0.72rem; font-weight: 600;">
                                                <i class="fas fa-clock mr-1"></i> Last: {{ \Carbon\Carbon::parse($wallet->funds_max_created_at)->diffForHumans() }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="align-middle">
                                <span class="badge badge-light border py-2 px-3 font-weight-bold">
                                    <span class="text-success">$</span> {{ number_format($wallet->money, 2) }}
                                </span>
                            </td>

                            <td class="align-middle text-muted small">
                                {{ $wallet->created_at->format('M d, Y') }}
                            </td>

                            <td class="text-right align-middle pr-4 position-relative">
                                <div class="btn-group shadow-sm">
                                    <button class="btn btn-white btn-sm border" wire:click="edit({{ $wallet->id }})" data-toggle="modal" data-target="#editWallet">
                                        <i class="fa fa-pencil-alt text-primary"></i>
                                    </button>
                                    <button class="btn btn-white btn-sm border" @click="showHistory = !showHistory">
                                        <i class="fa fa-history" :class="showHistory ? 'text-primary' : 'text-muted'"></i>
                                    </button>
                                </div>

                                <div x-show="showHistory" @click.away="showHistory = false" x-transition class="position-absolute bg-white shadow-lg border rounded p-3" style="right: 40px; top: 100%; z-index: 1050; min-width: 280px;">
                                    <h6 class="small font-weight-bold border-bottom pb-1">Recent Transactions</h6>
                                    @forelse($wallet->funds as $fund)
                                        <div class="d-flex justify-content-between align-items-center mb-2 border-bottom border-light pb-1">
                                            <div class="d-flex flex-column text-left">
                                                <span class="small font-weight-bold" style="font-size: 0.7rem;">{{ $fund->method }}</span>
                                                <span class="text-muted" style="font-size: 0.6rem;">{{ $fund->created_at->format('d M, H:i') }}</span>
                                            </div>
                                            <span class="small font-weight-bold {{ $fund->amount > 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $fund->amount > 0 ? '+' : '' }}${{ number_format($fund->amount, 2) }}
                                            </span>
                                        </div>
                                    @empty
                                        <p class="text-center text-muted small py-2">No transactions.</p>
                                    @endforelse
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">No wallets found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small">Showing {{ $wallets->firstItem() }} to {{ $wallets->lastItem() }} of {{ $wallets->total() }}</span>
                <div>{{ $wallets->links() }}</div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="editWallet" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Adjust Funds</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($thisWallet)
                        <div class="text-center mb-4">
                            <h6 class="text-muted mb-1">User: <strong>{{ $thisWallet->user->name ?? 'N/A' }}</strong></h6>
                            <h4 class="text-dark font-weight-bold">Current: ${{ number_format($thisWallet->money, 2) }}</h4>
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="small font-weight-bold">Select Currency</label>
                        <select wire:model="currency" class="form-control custom-select">
                            <option value="USD">USD - US Dollar</option>
                            <option value="RWF">RWF - Rwandan Franc</option>
                            <option value="EUR">EUR - Euro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold">Amount to Adjust</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-coins text-warning"></i></span>
                            </div>
                            <input type="number" wire:model="money" class="form-control" placeholder="0.00" step="0.01">
                        </div>
                        @error('money') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-danger px-4" wire:click="decreaseFund">
                        <i class="fas fa-minus-circle mr-1"></i> Deduct
                    </button>
                    <button type="button" class="btn btn-success px-4" wire:click="increaseFund">
                        <i class="fas fa-plus-circle mr-1"></i> Add Funds
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
    window.addEventListener('closeEditWalletModel', event => {
        $('#editWallet').modal('hide');
    });
</script>
</div>

