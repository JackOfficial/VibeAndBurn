<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            {{-- Alert Messages --}}
            @if (Session::has('deleteFundSuccess'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong><i class="fas fa-check"></i> Success!</strong> {{ Session::get('deleteFundSuccess') }}
                </div>
            @endif

            <div class="card shadow-sm">
               <div class="card-header border-0 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
    {{-- Title stays at the start --}}
    <h3 class="card-title mb-3 mb-md-0">
        <i class="fas fa-history mr-1"></i> 
        {{ number_format($fundsCounter) }} Transactions
    </h3>

    {{-- Tools container pushed to the end on medium+ screens --}}
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-end ml-md-auto">
        
        {{-- Search Input --}}
        <div class="input-group input-group-sm mb-2 mb-sm-0 mr-sm-3" style="min-width: 250px; width: 100%;">
            <input type="text" 
                   wire:model.debounce.500ms="search" 
                   class="form-control border-secondary" 
                   placeholder="Search user, email, or method..."
                   style="border-radius: 20px 0 0 20px;">
            <div class="input-group-append">
                <span class="input-group-text bg-white border-secondary" style="border-radius: 0 20px 20px 0;">
                    <i class="fas fa-search text-muted" wire:loading.remove wire:target="search"></i>
                    <i class="fas fa-spinner fa-spin text-primary" wire:loading wire:target="search"></i>
                </span>
            </div>
        </div>

        {{-- Badge --}}
        <span class="badge badge-success p-2 text-center" style="height: fit-content; white-space: nowrap;">
            Total Earned: ${{ number_format($fundsTotal, 2) }}
        </span>
    </div>
</div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody wire:loading.class="text-muted" wire:target="search">
                            @forelse ($funds as $fund)
                            <tr wire:key="fund-{{ $fund->id }}"> 
                                <td>#{{ $fund->id }}</td>
                                <td><strong>{{ $fund->user->name ?? 'Unknown User' }}</strong></td>
                                <td><small class="text-muted">{{ $fund->user->email ?? 'N/A' }}</small></td>
                                
                                <td>
                                    <span class="badge badge-info shadow-sm">{{ strtoupper($fund->method) }}</span>
                                    @if($fund->Payedwith)
                                        <small class="d-block text-xs text-muted">via {{ $fund->Payedwith }}</small>
                                    @endif
                                </td>
                                <td><b class="text-success">${{ number_format($fund->amount, 2) }}</b></td>
                                
                                <td>{{ $fund->created_at->format('M d, Y H:i') }}</td>
                                
                                <td class="text-center">
                                    <a class="btn btn-sm btn-outline-primary shadow-sm" href="#">
                                        <i class="fa fa-download"></i> Receipt
                                    </a>
                                </td>
                            </tr>   
                            @empty
                            <tr>
                                <td colspan="7" class="text-center p-5">
                                    <i class="fas fa-search-minus fa-3x text-muted mb-3"></i>
                                    <p class="font-italic">No fund transactions found matching "{{ $search }}".</p>
                                    @if($search)
                                        <button wire:click="$set('search', '')" class="btn btn-sm btn-link text-primary">Clear Search</button>
                                    @endif
                                </td>
                            </tr>
                            @endforelse 
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                <div class="card-footer clearfix bg-white border-top">
                    <div class="float-left text-muted small mt-2">
                        Showing {{ $funds->firstItem() ?? 0 }} to {{ $funds->lastItem() ?? 0 }} of {{ $funds->total() }} results
                    </div>
                    <div class="float-right">
                        {!! $funds->links() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>