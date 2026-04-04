<div>
    <div class="row mb-3 align-items-center">
        <div class="col-md-4">
            <h3 class="card-title font-weight-bold text-dark">
                <i class="fas fa-list-ul mr-2 text-primary"></i>
                {{ number_format($servicesCounter) }} Total Services
            </h3>
        </div>
        <div class="col-md-4 text-center">
            <livewire:admin.refresh-price-component />
        </div>
        <div class="col-md-4">
            <div class="input-group input-group-sm shadow-sm" style="border-radius: 20px; overflow: hidden; border: 1px solid #e0e0e0;">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted"></i></span>
                </div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       class="form-control border-0 py-4" 
                       placeholder="Search database...">
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="alert alert-success shadow-sm border-0">
            {{ session('message') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-uppercase text-muted small">
                    <tr>
                        <th class="pl-4">#</th>
                        <th>ID / Status</th>
                        <th>Category</th>
                        <th>Details</th>
                        <th>Pricing</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                    <tr class="align-middle" wire:key="service-{{ $service->id }}">
                        <td class="pl-4 font-weight-bold text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-light border px-2 py-1 mr-2">#{{ $service->id }}</span>
                                <livewire:admin.mention-component :serviceID="$service->id" :status="$service->status" :key="'mention-'.$service->id" />
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-2 rounded-circle bg-soft-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    @php
                                        $icon = match($service->category->socialmedia_id ?? 0) {
                                            1 => 'fa-instagram', 2 => 'fa-facebook', 3 => 'fa-twitter',
                                            4 => 'fa-youtube', 5 => 'fa-tiktok', default => 'fa-users'
                                        };
                                    @endphp
                                    <i class="fab {{ $icon }} text-primary small"></i>
                                </div>
                                <span class="font-weight-bold text-dark">{{ $service->category->category ?? 'Uncategorized' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="font-weight-bold text-truncate" style="max-width: 250px;">{{ $service->service }}</div>
                            <div class="text-muted text-xs mt-1">
                                <span class="mr-2"><i class="far fa-clock mr-1"></i> {{ $service->start }}</span>
                                <span><i class="fas fa-bolt mr-1"></i> {{ $service->speed }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="text-dark font-weight-bold" style="font-size: 1.1rem;">
                                ${{ number_format($service->rate_per_1000, 4) }}
                            </div>
                        </td>
                        <td>
                            <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                <a class="btn btn-sm btn-white text-success border-right" href="{{ route('admin.service.edit', $service->id) }}"><i class="fa fa-edit"></i></a>
                                
                                <button wire:click="toggleStatus({{ $service->id }})" class="btn btn-sm btn-white {{ $service->status == 1 ? 'text-warning' : 'text-primary' }} border-right">
                                    <i class="fa {{ $service->status == 1 ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                </button>

                                <button onclick="confirm('Are you sure?') || event.stopImmediatePropagation()" 
                                        wire:click="deleteService({{ $service->id }})" 
                                        class="btn btn-sm btn-white text-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No services found for "{{ $this->search }}"</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-top py-3">
            {{ $services->links() }}
        </div>
    </div>
</div>