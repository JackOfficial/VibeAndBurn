<div>
    <div class="p-2">
        @if (session()->has('deleteWalletSuccess') || isset($feedback))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <strong><i class="fas fa-check-circle"></i> Success!</strong> 
                {{ session('deleteWalletSuccess') ?? $feedback }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session()->has('deleteWalletFail'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <strong><i class="fas fa-exclamation-triangle"></i> Error:</strong> 
                {{ session('deleteWalletFail') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>

    <div class="card card-outline card-primary shadow">
        <div class="card-header border-0">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-wallet mr-1"></i> Wallet Management
            </h3>
            <div class="card-tools">
                <span class="badge badge-info p-2 mr-2">{{ $walletsCounter }} Users</span>
                <span class="badge badge-success p-2">Total System Balance: ${{ number_format($walletsTotal, 2) }}</span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>User Details</th>
                            <th>Current Balance</th>
                            <th>Registered</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($wallets as $wallet)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">
                                <div class="d-flex flex-column">
                                    <span class="font-weight-bold">{{ $wallet->name }}</span>
                                    <small class="text-muted">{{ $wallet->email }}</small>
                                </div>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-pill badge-light border px-3 py-2" style="font-size: 0.9rem;">
                                    <strong>${{ number_format($wallet->money, 2) }}</strong>
                                </span>
                            </td>
                            <td class="align-middle text-muted">{{ $wallet->created_at->format('d M Y') }}</td>
                            <td class="text-right align-middle">
                                <button class="btn btn-outline-primary btn-sm" wire:click.prevent="edit({{ $wallet->id }})" data-toggle="modal" data-target="#editWallet">
                                    <i class="fa fa-edit"></i> Adjust Funds
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" title="History">
                                    <i class="fa fa-history"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="editWallet" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-coins text-warning mr-2"></i> Adjust User Funds</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div wire:loading class="text-center p-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Fetching wallet data...</p>
                    </div>

                    <div wire:loading.remove>
                        @if($thisWallet)
                        <div class="user-preview p-3 mb-3 bg-light rounded text-center">
                            <h6 class="mb-1 font-weight-bold">{{ $thisWallet->name }}</h6>
                            <p class="text-muted small mb-0">{{ $thisWallet->email }}</p>
                            <hr>
                            <small class="text-uppercase text-muted d-block mb-1">Current Balance</small>
                            <h4 class="text-primary font-weight-bold">${{ number_format($thisWallet->money, 2) }}</h4>
                        </div>
                        @endif

                        <div class="form-group">
                            <label for="currency" class="small font-weight-bold">Select Currency & Amount</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <select class="custom-select" style="border-top-right-radius: 0; border-bottom-right-radius: 0;" wire:model.defer="currency">
                                        <option value="USD">USD</option>
                                        <option value="RWF">RWF</option>
                                        <option value="KES">KES</option>
                                        <option value="BIF">BIF</option>
                                        </select>
                                </div>
                                <input type="number" step="0.01" wire:model.defer="money" class="form-control @error('money') is-invalid @enderror" placeholder="0.00">
                            </div>
                            @error('money') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light justify-content-between">
                    <button type="button" class="btn btn-outline-danger btn-sm px-4" wire:click="decreaseFund" wire:loading.attr="disabled">
                        <i class="fa fa-minus-circle mr-1"></i> Deduct
                    </button>
                    <button type="button" class="btn btn-primary btn-sm px-4" wire:click="increaseFund" wire:loading.attr="disabled">
                        <i class="fa fa-plus-circle mr-1"></i> Add Funds
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>