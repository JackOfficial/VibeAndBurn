<div x-data="{ 
    showFeedback: true,
    init() {
        // Auto-hide alerts after 5 seconds to keep the UI clean
        $watch('showFeedback', value => {
            if(value) setTimeout(() => this.showFeedback = false, 5000)
        })
    }
}">
    <div class="p-2" x-show="showFeedback" x-transition:leave="transition ease-in duration-300">
        @if (session()->has('deleteWalletSuccess') || isset($feedback))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle mr-2"></i>
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
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas fa-wallet text-primary mr-2"></i> Wallet Management
                </h3>
                
                <div class="card-tools d-flex align-items-center">
                    <div class="input-group input-group-sm mr-3" style="width: 280px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0">
                                <i class="fas fa-search text-muted" wire:loading.remove wire:target="search"></i>
                                <i class="fas fa-spinner fa-spin text-primary" wire:loading wire:target="search"></i>
                            </span>
                        </div>
                        <input type="text" 
                               wire:model.debounce.400ms="search" 
                               class="form-control border-left-0 bg-light" 
                               placeholder="Search name or email...">
                    </div>

                    <div class="stats-badges d-none d-md-block">
                        <span class="badge badge-pill badge-info py-2 px-3 mr-2 shadow-sm">
                            <i class="fas fa-users mr-1"></i> {{ $walletsCounter }} Users
                        </span>
                        <span class="badge badge-pill badge-success py-2 px-3 shadow-sm">
                            <i class="fas fa-chart-line mr-1"></i> Total: ${{ number_format($walletsTotal, 2) }}
                        </span>
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
                        <tr wire:key="wallet-{{ $wallet->id }}" class="border-bottom">
                            <td class="align-middle pl-4 text-muted">
                                {{ ($wallets->currentPage() - 1) * $wallets->perPage() + $loop->iteration }}
                            </td>
                           <td class="align-middle">
    <div class="d-flex align-items-center">
        <div class="mr-3 shadow-sm rounded-circle d-flex align-items-center justify-content-center" 
             style="width: 40px; height: 40px; overflow: hidden; 
                    background-color: {{ $wallet->user->avatar ? 'transparent' : '#e7f1ff' }};">
            
            @if($wallet->user->avatar)
                <img src="{{ $wallet->user->avatar }}" 
                     alt="Avatar" 
                     style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <span class="font-weight-bold text-primary" style="font-size: 1.1rem;">
                    {{ strtoupper(substr($wallet->user->name ?? 'U', 0, 1)) }}
                </span>
            @endif
        </div>

        <div class="d-flex flex-column">
            <span class="font-weight-bold text-dark">{{ $wallet->user->name ?? 'Deleted User' }}</span>
            <small class="text-muted"><i class="fas fa-envelope fa-xs mr-1"></i>{{ $wallet->user->email ?? 'N/A' }}</small>
        </div>
    </div>
</td>
                            <td class="align-middle">
                                <span class="badge badge-light border py-2 px-3 font-weight-bold" style="font-size: 0.95rem;">
                                    <span class="text-success">$</span> {{ number_format($wallet->money, 2) }}
                                </span>
                            </td>
                            <td class="align-middle text-muted small">
                                <i class="far fa-calendar-alt mr-1"></i> {{ $wallet->created_at->format('M d, Y') }}
                            </td>
                            <td class="text-right align-middle pr-4">
                                <div class="btn-group shadow-sm">
                                    <button class="btn btn-white btn-sm border" 
                                            wire:click="edit({{ $wallet->id }})" 
                                            data-toggle="modal" 
                                            data-target="#editWallet"
                                            title="Adjust Funds">
                                        <i class="fa fa-pencil-alt text-primary"></i>
                                    </button>
                                    <button class="btn btn-white btn-sm border" title="Transaction History">
                                        <i class="fa fa-history text-muted"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-search-minus fa-3x mb-3 opacity-50"></i>
                                    <p>No wallets found matching your search criteria.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small">
                    Showing {{ $wallets->firstItem() }} to {{ $wallets->lastItem() }} of {{ $wallets->total() }} entries
                </span>
                <div>
                    {{ $wallets->links() }}
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="editWallet" tabindex="-1" role="dialog">
        </div>
</div>