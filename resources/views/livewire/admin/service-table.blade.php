<div>
    {{-- Header Section: Stats and Global Actions --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="card-title font-weight-bold text-dark mb-0">
                <i class="fas fa-list-ul mr-2 text-primary"></i>
                {{ number_format($servicesCounter) }} Total Services
            </h3>
        </div>
        <div class="col-md-6 text-right">
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

    {{-- Filter & Search Bar --}}
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                {{-- Search --}}
                <div class="col-md-3">
                    <div class="input-group input-group-sm border shadow-none" style="border-radius: 8px; overflow: hidden;">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" wire:model.debounce.400ms="search" class="form-control border-0" placeholder="Search name or ID...">
                    </div>
                </div>

                {{-- Category Filter --}}
                <div class="col-md-3">
                    <select wire:model="filterCategory" class="form-control form-control-sm border-0 bg-light shadow-none" style="border-radius: 8px;">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Provider/Source Filter --}}
                <div class="col-md-2">
                    <select wire:model="filterSource" class="form-control form-control-sm border-0 bg-light shadow-none" style="border-radius: 8px;">
                        <option value="">All Providers</option>
                        @foreach($sources as $src)
                            <option value="{{ $src->id }}">{{ $src->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Filter --}}
                <div class="col-md-2">
                    <select wire:model="filterStatus" class="form-control form-control-sm border-0 bg-light shadow-none" style="border-radius: 8px;">
                        <option value="">All Statuses</option>
                        <option value="1">Active</option>
                        <option value="2">Mentioned</option>
                        <option value="0">Disabled</option>
                    </select>
                </div>

                <div class="col-md-2 text-right">
                    <button wire:click="resetFilters" class="btn btn-sm btn-link text-muted small p-0">
                        <i class="fas fa-times-circle"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="alert alert-success shadow-sm border-0 mb-4">
            <i class="fas fa-check-circle mr-2"></i> {{ session('message') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-uppercase text-muted small">
                    <tr>
                        <th class="pl-4" style="width: 60px;">ID</th>
                        <th>Service Details</th>
                        <th>Min / Max</th>
                        <th>Refill</th>
                        <th>Avg. Time</th>
                        <th>Pricing (1k)</th>
                        <th class="text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                    <tr class="align-middle" wire:key="service-row-{{ $service->id }}">
                        <td class="pl-4 text-muted small">
                            #{{ $service->serviceId ?? $service->id }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center mb-1">
                                <div class="mr-2 rounded-circle bg-soft-primary d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                    @php
                                        $icon = match($service->category->socialmedia_id ?? 0) {
                                            1 => 'fa-instagram', 2 => 'fa-facebook', 3 => 'fa-twitter',
                                            4 => 'fa-youtube', 5 => 'fa-tiktok', default => 'fa-share-alt'
                                        };
                                    @endphp
                                    <i class="fab {{ $icon }} text-primary" style="font-size: 10px;"></i>
                                </div>
                                <span class="font-weight-bold text-dark" style="font-size: 0.9rem;">{{ $service->service }}</span>
                            </div>
                            <div class="text-xs text-muted">
                                <span class="badge badge-light border text-uppercase">{{ $service->category->category ?? 'General' }}</span>
                                <span class="ml-2"><i class="fas fa-plug mr-1"></i>{{ $service->source->name ?? 'Manual' }}</span>
                            </div>
                        </td>
                        <td class="small">
                            <div class="text-dark">Min: {{ number_format($service->min_order) }}</div>
                            <div class="text-muted">Max: {{ number_format($service->max_order) }}</div>
                        </td>
                        <td>
                            @if($service->refill)
                                <span class="badge badge-soft-success px-2 py-1"><i class="fas fa-redo-alt mr-1"></i>Yes</span>
                            @else
                                <span class="badge badge-soft-secondary px-2 py-1">No</span>
                            @endif
                        </td>
                        <td class="small text-muted">
                            <i class="far fa-clock mr-1"></i> {{ $service->Average_completion_time ?? 'Instant' }}
                        </td>
                        <td>
                            <div class="text-primary font-weight-bold" title="Your Selling Rate">
                                ${{ number_format($service->rate_per_1000, 4) }}
                            </div>
                            <div class="text-xs text-muted font-italic" title="Provider Cost">
                                Cost: ${{ number_format($service->price_per_1000, 4) }}
                            </div>
                        </td>
                        <td class="text-right pr-4">
                            <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                <button wire:click="toggleMention({{ $service->id }})" 
                                        class="btn btn-sm btn-white {{ $service->status == 2 ? 'text-danger' : 'text-secondary' }}" 
                                        title="Mention Service">
                                    <i class="fas fa-bullhorn"></i>
                                </button>
                                <a class="btn btn-sm btn-white text-success border-right" href="{{ route('admin.service.edit', $service->id) }}"><i class="fa fa-edit"></i></a>
                                <button wire:click="toggleStatus({{ $service->id }})" class="btn btn-sm btn-white {{ $service->status == 1 ? 'text-warning' : 'text-primary' }} border-right">
                                    <i class="fa {{ $service->status == 1 ? 'fa-eye-slash' : 'fa-eye' }}" wire:loading.remove wire:target="toggleStatus({{ $service->id }})"></i>
                                    <i class="fas fa-spinner fa-spin" wire:loading wire:target="toggleStatus({{ $service->id }})"></i>
                                </button>
                                <button onclick="confirm('Delete permanently?') || event.stopImmediatePropagation()" wire:click="deleteService({{ $service->id }})" class="btn btn-sm btn-white text-danger">
                                    <i class="fa fa-trash" wire:loading.remove wire:target="deleteService({{ $service->id }})"></i>
                                    <i class="fas fa-spinner fa-spin" wire:loading wire:target="deleteService({{ $service->id }})"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <p class="text-muted">No services found matching your filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of {{ $services->total() }}</div>
                <div>{{ $services->links() }}</div>
            </div>
        </div>
    </div>
</div>