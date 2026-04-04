<div class="row mt-2">
    <div class="col-12">
        {{-- Flash Message --}}
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 x-transition.out.opacity.duration.1000ms
                 class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center" style="border-radius: 10px;">
                <i class="fas fa-check-circle mr-2"></i> {{ session('message') }}
            </div>
        @endif

        <div class="card shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
            {{-- Header Section --}}
            <div class="card-header bg-white border-0 py-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="font-weight-bold mb-0 text-dark">Subscriber Directory</h4>
                        <p class="text-muted small mb-0">Manage your {{ number_format($subscribersCounter) }} email leads</p>
                    </div>
                    
                    {{-- Search Box --}}
                    <div class="col-md-6 mt-3 mt-md-0">
                        <div class="position-relative shadow-sm" style="border-radius: 10px;">
                            {{-- Search Icon (Hidden when loading) --}}
                            <div class="position-absolute d-flex align-items-center justify-content-center" 
                                 style="left: 15px; top: 0; bottom: 0; z-index: 5; pointer-events: none;">
                                <i class="fas fa-search text-muted"></i>
                            </div>

                            <input wire:model.live.debounce.300ms="search" 
                                   type="text" 
                                   class="form-control border-0 px-5" 
                                   placeholder="Search by email..."
                                   style="height: 48px; border-radius: 10px; font-size: 0.95rem; background-color: #f8f9fa;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-uppercase text-muted small font-weight-bold">
                            <tr>
                                <th class="pl-4 py-3" style="width: 80px;">#</th>
                                <th>Subscriber</th>
                                <th>Joined Date</th>
                                <th class="text-right pr-4">Action</th>
                            </tr>
                        </thead>
                        {{-- Table Body with Loading Overlay --}}
                        <tbody class="border-top-0" wire:loading.class="opacity-50" wire:target="search">
                            @forelse ($subscribers as $subscriber)
                                <tr wire:key="sub-{{ $subscriber->id }}">
                                    <td class="pl-4 text-muted small">
                                        {{ ($subscribers->currentPage() - 1) * $subscribers->perPage() + $loop->iteration }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center mr-3" 
                                                 style="width: 35px; height: 35px; font-size: 13px; font-weight: bold; background-color: #eef2ff;">
                                                {{ strtoupper(substr($subscriber->email, 0, 1)) }}
                                            </div>
                                            <span class="font-weight-600 text-dark">{{ $subscriber->email }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted small">
                                            <i class="far fa-calendar-alt mr-1"></i>
                                            {{ $subscriber->created_at->format('M d, Y') }}
                                        </span>
                                    </td>
                                    <td class="text-right pr-4">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger border-0"
                                                title="Delete Subscriber"
                                                @click="if(confirm('Permanent delete {{ $subscriber->email }}?')) { $wire.deleteSubscriber({{ $subscriber->id }}) }">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fas fa-envelope-open fa-3x text-light mb-3"></i>
                                            <h5 class="text-muted">No subscribers found</h5>
                                            @if($search)
                                                <p class="small text-muted">Adjust your search for "{{ $search }}"</p>
                                                <button wire:click="$set('search', '')" class="btn btn-sm btn-primary mt-2 shadow-sm">Clear Search</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <p class="text-muted small mb-3 mb-md-0">
                        Showing <b>{{ $subscribers->firstItem() }}</b> to <b>{{ $subscribers->lastItem() }}</b> of {{ $subscribers->total() }} entries
                    </p>
                    {{ $subscribers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>