<div class="row">
    <div class="col-12">
        {{-- Auto-hiding Flash Message --}}
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 x-transition.out.opacity.duration.1000ms
                 class="alert alert-success border-0 shadow-sm mb-3 d-flex align-items-center">
                <i class="fas fa-check-circle mr-2"></i> {{ session('message') }}
            </div>
        @endif

        <div class="card shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
            {{-- Enhanced Header with Search --}}
            <div class="card-header bg-white border-0 py-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <h4 class="font-weight-bold mb-0 text-dark">
                            Subscriber Directory
                        </h4>
                        <p class="text-muted small mb-0">Total: {{ number_format($subscribersCounter) }} registered emails</p>
                    </div>
                    
                   <div class="mt-3 mt-md-0 position-relative" style="min-width: 300px;">
    {{-- Search Input Container --}}
    <div class="position-relative shadow-sm" style="border-radius: 10px;">
        
        {{-- Search Icon (Left) --}}
        <div class="position-absolute d-flex align-items-center justify-content-center" 
             style="left: 15px; top: 0; bottom: 0; z-index: 5; pointer-events: none;">
            <i class="fas fa-search text-muted"></i>
        </div>

        {{-- The Input Field --}}
        <input wire:model.live.debounce.300ms="search" 
               type="text" 
               class="form-control border-0 px-5" {{-- Left padding px-5 gives room for the icon --}}
               placeholder="Search by email..."
               style="height: 48px; border-radius: 10px; font-size: 0.95rem; background-color: #f8f9fa;">

        {{-- Livewire Loading Spinner (Right) --}}
        <div wire:loading wire:target="search" 
             class="position-absolute d-flex align-items-center" 
             style="right: 15px; top: 0; bottom: 0; z-index: 5;">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        
    </div>
</div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-uppercase text-muted small font-weight-bold">
                            <tr>
                                <th class="pl-4 py-3">#</th>
                                <th>Subscriber</th>
                                <th>Joined Date</th>
                                <th class="text-right pr-4">Management</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse ($subscribers as $subscriber)
                                <tr wire:key="sub-{{ $subscriber->id }}" class="transition-all">
                                    <td class="pl-4 text-muted small">
                                        {{ ($subscribers->currentPage() - 1) * $subscribers->perPage() + $loop->iteration }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3" style="width: 32px; height: 32px; font-size: 12px;">
                                                {{ strtoupper(substr($subscriber->email, 0, 1)) }}
                                            </div>
                                            <span class="font-weight-bold text-dark">{{ $subscriber->email }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-muted small">
                                            <i class="far fa-calendar-alt mr-1"></i>
                                            {{ $subscriber->created_at->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td class="text-right pr-4">
                                        <button type="button" 
                                                class="btn btn-sm btn-light text-danger shadow-sm border"
                                                @click="if(confirm('Are you sure you want to remove this subscriber?')) { $wire.deleteSubscriber({{ $subscriber->id }}) }">
                                            <i class="fa fa-trash-alt mr-1"></i> Remove
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fas fa-search-minus fa-3x text-light mb-3"></i>
                                            <h5 class="text-muted">No results matching "{{ $search }}"</h5>
                                            <button wire:click="$set('search', '')" class="btn btn-link text-primary p-0">Clear search</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination with custom styling --}}
            <div class="card-footer bg-white border-top-0 py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <p class="text-muted small mb-3 mb-md-0">
                        Showing {{ $subscribers->firstItem() ?? 0 }} to {{ $subscribers->lastItem() ?? 0 }} of {{ $subscribers->total() }} records
                    </p>
                    <div class="pagination-sm">
                        {{ $subscribers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>