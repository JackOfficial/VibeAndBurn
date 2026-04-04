<div>
    {{-- Header Section: Stats, Refresh, and Search --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-4">
            <h3 class="card-title font-weight-bold text-dark mb-0">
                <i class="fas fa-list-ul mr-2 text-primary"></i>
                {{ number_format($servicesCounter) }} Total Services
            </h3>
        </div>
        
        <div class="col-md-4 text-center">
            <div class="d-inline-flex align-items-center">
                <button wire:click="refreshBtn" 
                        wire:loading.attr="disabled"
                        class="btn btn-sm btn-warning rounded-pill px-3 shadow-sm">
                    <i class="fas fa-sync" wire:loading.class="fa-spin" wire:target="refreshBtn"></i> 
                    <span class="ml-1">Refresh Prices</span>
                </button>
                <div wire:loading wire:target="refreshBtn" class="ml-2 small text-muted font-italic">
                    Syncing with API...
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="input-group input-group-sm shadow-sm" style="border-radius: 20px; overflow: hidden; border: 1px solid #e0e0e0;">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted"></i></span>
                </div>
                <input type="text" 
                       wire:model.live.debounce.400ms="search" 
                       class="form-control border-0 py-4" 
                       placeholder="Search services or IDs...">
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 4000)" 
             class="alert alert-success shadow-sm border-0 fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('message') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-uppercase text-muted small">
                    <tr>
                        <th class="pl-4" style="width: 60px;">#</th>
                        <th>ID / Status</th>
                        <th>Category</th>
                        <th>Details</th>
                        <th>Pricing (1k)</th>
                        <th class="text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                    <tr class="align-middle" wire:key="service-row-{{ $service->id }}">
                        <td class="pl-4 font-weight-bold text-muted">
                            {{ $loop->iteration + ($services->currentPage() - 1) * $services->perPage() }}
                        </td>
                        
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-light border px-2 py-1 mr-2">#{{ $service->id }}</span>
                                
                                {{-- Targeted Mention Toggle --}}
                                <button wire:click="toggleMention({{ $service->id }})" 
                                        wire:loading.attr="disabled"
                                        wire:target="toggleMention({{ $service->id }})"
                                        class="btn btn-xs shadow-sm {{ $service->status == 2 ? 'btn-danger' : 'btn-outline-secondary' }}"
                                        style="border-radius: 4px; min-width: 90px; font-size: 11px;">
                                    
                                    <span wire:loading.remove wire:target="toggleMention({{ $service->id }})">
                                        <i class="fas {{ $service->status == 2 ? 'fa-bullhorn' : 'fa-plus-circle' }} mr-1"></i>
                                        {{ $service->status == 2 ? 'Mentioned' : 'Mention' }}
                                    </span>

                                    <span wire:loading wire:target="toggleMention({{ $service->id }})">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </span>
                                </button>
                            </div>
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-2 rounded-circle bg-soft-primary d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                    @php
                                        $icon = match($service->category->socialmedia_id ?? 0) {
                                            1 => 'fa-instagram', 2 => 'fa-facebook', 3 => 'fa-twitter',
                                            4 => 'fa-youtube', 5 => 'fa-tiktok', default => 'fa-share-alt'
                                        };
                                    @endphp
                                    <i class="fab {{ $icon }} text-primary small"></i>
                                </div>
                                <span class="font-weight-bold text-dark text-sm">{{ $service->category->category ?? 'General' }}</span>
                            </div>
                        </td>

                        <td>
                            <div class="font-weight-bold text-dark text-truncate" style="max-width: 280px;" title="{{ $service->service }}">
                                {{ $service->service }}
                            </div>
                            <div class="text-muted text-xs mt-1">
                                <span class="mr-2"><i class="far fa-clock mr-1"></i> {{ $service->start ?? 'Instant' }}</span>
                                <span><i class="fas fa-bolt mr-1"></i> {{ $service->speed ?? 'Normal' }}</span>
                            </div>
                        </td>

                        <td>
                            <div class="text-primary font-weight-bold" style="font-size: 1rem;">
                                ${{ number_format($service->rate_per_1000, 4) }}
                            </div>
                        </td>

                        <td class="text-right pr-4">
                            <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                <a class="btn btn-sm btn-white text-success border-right" href="{{ route('admin.service.edit', $service->id) }}" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                
                                <button wire:click="toggleStatus({{ $service->id }})" 
                                        wire:loading.attr="disabled"
                                        class="btn btn-sm btn-white {{ $service->status == 1 ? 'text-warning' : 'text-primary' }} border-right"
                                        title="Toggle Visibility">
                                    <i class="fa {{ $service->status == 1 ? 'fa-eye-slash' : 'fa-eye' }}" wire:loading.remove wire:target="toggleStatus({{ $service->id }})"></i>
                                    <i class="fas fa-spinner fa-spin" wire:loading wire:target="toggleStatus({{ $service->id }})"></i>
                                </button>

                                <button onclick="confirm('Delete this service permanently?') || event.stopImmediatePropagation()" 
                                        wire:click="deleteService({{ $service->id }})" 
                                        wire:loading.attr="disabled"
                                        class="btn btn-sm btn-white text-danger"
                                        title="Delete">
                                    <i class="fa fa-trash" wire:loading.remove wire:target="deleteService({{ $service->id }})"></i>
                                    <i class="fas fa-spinner fa-spin" wire:loading wire:target="deleteService({{ $service->id }})"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-50 mb-3">
                            <p class="text-muted">No services matching "<strong>{{ $search }}</strong>" found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of {{ $services->total() }}
                </div>
                <div>
                    {{ $services->links() }}
                </div>
            </div>
        </div>
    </div>
</div>