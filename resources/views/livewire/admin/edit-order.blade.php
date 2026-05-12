<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.opacity>
                @if (Session::has('editOrderSuccess'))
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ Session::get('editOrderSuccess') }}
                    </div>
                @elseif(Session::has('editOrderFail'))
                    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ Session::get('editOrderFail') }}
                    </div>
                @endif
            </div>

            <div class="card card-outline card-primary shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-shopping-cart text-primary mr-2"></i>
                        Order #{{ $orderID }} </h5>
                    <span class="badge badge-soft-info p-2 px-3">{{ strtoupper($status) }}</span>
                </div>

                <form wire:submit.prevent="updateOrder">
                    <div class="card-body">
                      <div class="bg-light p-4 rounded mb-4 border-left-primary">
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="small font-weight-bold text-muted uppercase d-block">Category</label>
            <span class="text-dark font-weight-600">
                {{ $categories->where('id', $category)->first()->category ?? 'N/A' }}
            </span>
        </div>

        <div class="col-md-8 mb-3">
            <label class="small font-weight-bold text-muted uppercase d-block">Service Name</label>
            <span class="text-primary font-weight-bold">
                {{ $services->where('id', $service)->first()->service ?? 'Unknown Service' }}
            </span>
        </div>

        <div class="col-md-6 mb-3">
            <label class="small font-weight-bold text-muted uppercase d-block">API Provider ID</label>
            <div class="input-group input-group-sm mt-1">
                <input type="text" wire:model="orderId" class="form-control bg-white shadow-none" placeholder="External ID">
                <div class="input-group-append">
                    <span class="input-group-text badge-secondary border-0 text-white">ID: {{ $service }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3" x-data="{ copyLink() { navigator.clipboard.writeText('{{ $link }}'); } }">
            <label class="small font-weight-bold text-muted uppercase d-block">Target Link</label>
            <div class="input-group input-group-sm mt-1">
                <input type="text" class="form-control bg-white shadow-none" value="{{ $link }}" readonly>
                <div class="input-group-append">
                    <button type="button" @click="copyLink(); $el.innerHTML = '<i class=\'fas fa-check\'></i>'; setTimeout(() => $el.innerHTML = '<i class=\'fas fa-copy\'></i>', 2000)" class="btn btn-outline-primary" title="Copy Link">
                        <i class="fas fa-copy"></i>
                    </button>
                    <a href="{{ $link }}" target="_blank" class="btn btn-primary shadow-none">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Start Count</label>
                                    <input type="number" wire:model="startCount" class="form-control shadow-none @error('startCount') is-invalid @enderror"> 
                                    @error('startCount') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Remains</label>
                                    <input type="number" wire:model="remains" class="form-control shadow-none @error('remains') is-invalid @enderror"> 
                                    @error('remains') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Quantity</label>
                                    <input type="number" wire:model="quantity" class="form-control bg-light shadow-none" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Charge ($)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0">$</span></div>
                                        <input type="number" step="any" wire:model="charge" class="form-control shadow-none border-left-0 @error('charge') is-invalid @enderror">
                                    </div>
                                    @error('charge') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">Status</label>
                                    <select wire:model="status" class="form-control" required>
                                        <option value="0">Pending</option>
                                        <option value="1">Completed</option>
                                        <option value="2">Canceled</option>
                                        <option value="3">Processing</option>
                                        <option value="4">In Progress</option>
                                        <option value="5" selected>Partial</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if($comment)
                        <div class="form-group mt-3">
                            <label class="font-weight-bold text-muted small uppercase">Customer Customization/Comments</label>
                            <div class="p-3 bg-light rounded border text-monospace small" style="white-space: pre-wrap;">{{ $comment }}</div>
                        </div>
                        @endif
                    </div>

                    <div class="card-footer bg-white border-top-0 pt-0">
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary btn-block py-3 font-weight-bold shadow-sm">
                            <span wire:loading.remove wire:target="updateOrder"><i class="fa fa-save mr-2"></i> Save Changes</span>
                            <span wire:loading wire:target="updateOrder"><i class="fas fa-circle-notch fa-spin mr-2"></i> Syncing Data...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="font-weight-bold mb-0">Buyer Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-soft-primary rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px;">
                            @if($avatar)
                                <img src="{{ $avatar }}" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span class="h5 text-primary font-weight-bold mb-0">{{ strtoupper(substr($username, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div>
                            <h6 class="font-weight-bold mb-0 text-dark">{{ $username }}</h6>
                            <small class="text-muted">{{ $email }}</small>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Phone:</span>
                        <span class="font-weight-bold small">{{ $phone }}</span>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4 bg-light border-left-danger">
                <div class="card-body">
                    <h6 class="font-weight-bold text-danger mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Manual Refund
                    </h6>
                    <p class="small text-muted mb-3">Return <strong>${{ $charge }}</strong> to {{ $username }}.</p>
                    <button type="button" 
                            wire:click="manualRefund" 
                            wire:confirm="This will immediately refund ${{ $charge }}. Proceed?"
                            class="btn btn-danger btn-sm btn-block shadow-sm py-2">
                        <i class="fas fa-undo-alt mr-2"></i> Process Full Refund
                    </button>
                </div>
            </div>

            @if($description)
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="font-weight-bold mb-0">Original Service Details</h6>
                </div>
                <div class="card-body small text-muted overflow-auto" style="max-height: 300px;">
                    {!! $description !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .badge-soft-info { background-color: #e1f5fe; color: #01579b; border: 1px solid #b3e5fc; }
    .bg-soft-primary { background-color: rgba(0, 123, 255, 0.1); }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
    .border-left-primary { border-left: 4px solid #007bff; }
    .border-left-danger { border-left: 4px solid #dc3545; }
    .font-weight-600 { font-weight: 600; }
</style>