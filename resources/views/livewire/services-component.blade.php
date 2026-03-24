<div>
    <div class="nk-block">
        {{-- Filter Buttons Section --}}
        <div class="table-responsive mb-3">
            <div class="d-flex flex-nowrap pb-2">
                @php
                    $platforms = ['All', 'Instagram', 'Facebook', 'Youtube', 'TikTok', 'Twitter', 'Spotify', 'LinkedIn', 'Telegram', 'Pinterest', 'Snapchat'];
                @endphp
                
                @foreach($platforms as $platform)
                    <button wire:click="services('{{ $platform }}')" 
                            wire:key="filter-{{ $platform }}"
                            {{ ($filterServices == $platform) ? 'disabled' : '' }} 
                            class="btn {{ $filterServices == $platform ? 'btn-secondary' : 'btn-danger' }} btn-sm mr-1 shadow-sm">
                        {{ $platform }}
                    </button>
                @endforeach
            </div>  
        </div>

        <div class="card card-bordered shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th colspan="5" class="py-3">
                                <div class="d-flex align-items-center">
                                    <span class="h6 m-0 text-white">
                                        {{ $filterServices == "All" ? 'All Services' : $filterServices . ' Services' }}
                                        @if(count($services) > 0)
                                            <span class="badge badge-light ml-2">{{ count($services) }}</span>
                                        @endif
                                    </span>
                                    <div wire:loading wire:target="services, keyword" class="ml-3 d-none" wire:loading.class.remove="d-none">
                                        <div class="spinner-border spinner-border-sm text-white"></div>
                                    </div>
                                </div>
                            </th>  
                            <th colspan="2" class="py-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                                    </div>
                                    <input type="text" wire:model.live.debounce.300ms="keyword" 
                                           placeholder="Search services..." 
                                           class="form-control border-left-0" />
                                </div>
                            </th>
                        </tr>
                        <tr class="bg-light small text-muted text-uppercase font-weight-bold">
                            <th style="width: 80px">ID</th>
                            <th>Service Description</th>
                            <th>Rate / 1k</th>
                            <th>Min</th>
                            <th>Max</th>
                            <th>Avg. Time</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($services as $service)
                            <tr>
                                <td><span class="text-muted font-weight-bold">#{{ $service->id }}</span></td>
                                <td class="font-weight-bold text-dark">{{ $service->service }}</td>
                                {{-- CAST TO FLOAT ADDED BELOW --}}
                                <td><span class="text-success font-weight-bold">${{ number_format((float)$service->rate_per_1000, 2) }}</span></td>
                                <td><span class="badge badge-dim badge-primary px-2">{{ number_format((float)$service->min_order) }}</span></td>
                                <td><span class="badge badge-dim badge-danger px-2">{{ number_format((float)$service->max_order) }}</span></td>
                                <td><small class="text-muted"><i class="far fa-clock mr-1"></i>{{ $service->Average_completion_time }}</small></td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        <button class="btn btn-outline-primary btn-sm" 
                                                wire:click="viewService({{ $service->id }})" 
                                                data-toggle="modal" 
                                                data-target="#service_detail_model">
                                            Details
                                        </button>
                                        <button class="btn btn-primary btn-sm" wire:click="order({{ $service->id }})">
                                            Order
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5 text-center">
                                    <div class="text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                                        @if($keyword)
                                            No results found for "<strong>{{ $keyword }}</strong>"
                                        @else
                                            Currently no {{ $filterServices }} services available.
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Improved Modal --}}
    <div wire:ignore.self class="modal fade" id="service_detail_model" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle text-primary mr-2"></i>
                        <span wire:loading.remove wire:target="viewService">{{ $details->service ?? 'Service Details' }}</span>
                        <span wire:loading wire:target="viewService">Loading details...</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div wire:loading wire:target="viewService" class="text-center py-5">
                        <div class="spinner-border text-primary"></div>
                        <p class="text-muted mt-2">Fetching information...</p>
                    </div>

                    <div wire:loading.remove wire:target="viewService">
                        @if($details)
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Rate Per 1000</span>
                                <span class="font-weight-bold text-success">${{ number_format((float)($details->rate_per_1000 ?? 0), 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Min Order</span>
                                <span class="badge badge-primary px-3">{{ number_format((float)($details->min_order ?? 0)) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Max Order</span>
                                <span class="badge badge-danger px-3">{{ number_format((float)($details->max_order ?? 0)) }}</span>
                            </li>
                            @if($details->start)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Start Time</span>
                                <span class="text-muted">{{ $details->start }}</span>
                            </li>
                            @endif
                            @if($details->speed)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Speed</span>
                                <span class="text-muted">{{ $details->speed }}</span>
                            </li>
                            @endif
                        </ul>
                        @endif
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" wire:click="order({{ $details->id ?? '' }})">
                        <i class="fas fa-shopping-cart mr-1"></i> Order Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>