<div>
    <form wire:submit.prevent="update">
        <div class="row">
            {{-- Name --}}
            <div class="col-md-6 form-group">
                <label>Full Name</label>
                <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            {{-- Email --}}
            <div class="col-md-6 form-group">
                <label>Email Address</label>
                <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror">
                @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            {{-- Phone --}}
            <div class="col-md-6 form-group">
                <label>Phone Number</label>
                <input type="text" wire:model="phone" class="form-control">
            </div>

            {{-- SMM Balance --}}
            <div class="col-md-6 form-group">
                <label>Wallet Balance ($)</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                    </div>
                    <input type="number" step="0.01" wire:model="balance" class="form-control @error('balance') is-invalid @enderror">
                </div>
                @error('balance') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            {{-- Account Status --}}
            <div class="col-md-6 form-group">
                <label>Account Status</label>
                <select wire:model="status" class="form-control">
                    <option value="active">Active</option>
                    <option value="banned">Banned</option>
                </select>
            </div>

            {{-- New Password --}}
            <div class="col-md-6 form-group">
                <label>Reset Password (leave blank to keep current)</label>
                <input type="password" wire:model="password" class="form-control" placeholder="******">
            </div>
        </div>

        <div class="card-footer bg-transparent pl-0">
            <button type="submit" class="btn btn-primary">
                <span wire:loading wire:target="update" class="spinner-border spinner-border-sm mr-1"></span>
                <i wire:loading.remove wire:target="update" class="fas fa-save mr-1"></i>
                Save Changes
            </button>
            
            @if (session()->has('success'))
                <span class="text-success ml-3 animated fadeIn">
                    <i class="fas fa-check"></i> {{ session('success') }}
                </span>
            @endif
        </div>
    </form>
</div>