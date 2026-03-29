<div>
    @if (Session::has('deleteWalletSuccess'))
        <div class="alert alert-success alert-dismissible fade show" style="margin: 10px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong><i class="fas fa-check-circle"></i> Success!</strong> {{ Session::get('deleteWalletSuccess') }}
        </div>
    @elseif(Session::has('deleteWalletFail'))
        <div class="alert alert-danger alert-dismissible fade show" style="margin: 10px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong><i class="fas fa-exclamation-triangle"></i> Failed:</strong> {{ Session::get('deleteWalletFail') }}
        </div>
    @endif

    @if (isset($feedback))
        <div class="alert alert-info alert-dismissible fade show" style="margin: 10px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <i class="fas fa-info-circle"></i> {{ $feedback }}
        </div>
    @endif

    <div class="mb-3 ml-2">
        <button data-toggle="modal" data-target="#addWholesaler" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle"></i> Add New Wholesaler
        </button>
    </div>

    <div class="card card-outline card-primary shadow">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-plug mr-1"></i> 
                {{ $wholesalersCounter }} {{ Str::plural('Source', $wholesalersCounter) }}
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="example1" class="table table-hover table-striped mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 50px">#</th>
                            <th>Wholesaler Name</th>
                            <th class="text-center">Status</th>
                            <th>Date Created</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($wholesalers as $wholesaler)
                            <tr>
                                <td class="align-middle">{{ $loop->iteration }}</td>
                                <td class="align-middle font-weight-bold text-primary">
                                    {{ $wholesaler->wholesaler }}
                                </td>
                                <td class="align-middle text-center">
                                    @if($wholesaler->status)
                                        <span class="badge badge-success px-3 py-2">Active</span>
                                    @else
                                        <span class="badge badge-secondary px-3 py-2">Disabled</span>
                                    @endif
                                </td>
                                <td class="align-middle text-muted">
                                    {{ $wholesaler->created_at->format('M d, Y') }} 
                                    <small class="d-block">{{ $wholesaler->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="align-middle text-right">
                                    <div class="btn-group">
                                        <button class="btn btn-outline-success btn-sm" 
                                                wire:click.prevent="edit({{ $wholesaler->id }})" 
                                                data-toggle="modal" data-target="#editWallet"
                                                title="Edit Source">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" 
                                                wire:click="removeSource({{ $wholesaler->id }})"
                                                onclick="confirm('Are you sure you want to delete this source?') || event.stopImmediatePropagation()"
                                                title="Delete Source">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-muted">
                                    <i class="fas fa-folder-open fa-3x d-block mb-2"></i>
                                    No wholesalers available at the moment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="addWholesaler" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>Add New Wholesaler</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="wholesaler" class="font-weight-bold">Wholesaler Name</label>
                        <input type="text" wire:model.defer="wholesaler" 
                               class="form-control @error('wholesaler') is-invalid @enderror" 
                               id="wholesaler" placeholder="e.g. Peak SMM" required />
                        @error('wholesaler') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary border-0" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click="add">
                        <span wire:loading.remove wire:target="add">
                            <i class="fa fa-save mr-1"></i> Save Source
                        </span>
                        <span wire:loading wire:target="add">
                            <span class="spinner-border spinner-border-sm mr-1"></span> Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>