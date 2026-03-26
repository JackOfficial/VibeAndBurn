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
                    <option value="suspended">Suspended</option>
                    <option value="banned">Banned</option>
                </select>
            </div>

            {{-- New Password --}}
            <div class="col-md-6 form-group">
                <label>Reset Password (leave blank to keep current)</label>
                <input type="password" wire:model="password" class="form-control" placeholder="******">
            </div>

            {{-- RBAC Section (Visible only to Super Admins) --}}
            @hasrole('Super Admin')
                <div class="col-md-12">
                    <hr>
                    <h5 class="text-primary mb-3"><i class="fas fa-shield-alt mr-1"></i> Access Control</h5>
                </div>

                {{-- Roles --}}
                <div class="col-md-12 form-group">
                    <label>Assign Roles</label>
                    <div class="d-flex flex-wrap border rounded p-3 bg-light">
                        @foreach($allRoles as $role)
                            <div class="custom-control custom-checkbox mr-4">
                                <input class="custom-control-input" type="checkbox" 
                                       id="role-{{ $role->id }}" 
                                       value="{{ $role->name }}" 
                                       wire:model="selectedRoles">
                                <label for="role-{{ $role->id }}" class="custom-control-label font-weight-normal text-capitalize">
                                    {{ $role->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Permissions --}}
                <div class="col-md-12 form-group">
                    <label>Direct Permissions</label>
                    <div class="row border rounded p-3 bg-light mx-0" style="max-height: 250px; overflow-y: auto;">
                        @foreach($allPermissions as $permission)
                            <div class="col-md-4 col-sm-6 mb-2">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" 
                                           id="perm-{{ $permission->id }}" 
                                           value="{{ $permission->name }}" 
                                           wire:model="selectedPermissions">
                                    <label class="custom-control-label font-weight-normal text-xs" for="perm-{{ $permission->id }}">
                                        {{ str_replace('_', ' ', $permission->name) }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <small class="text-muted"><i>Permissions toggled here are assigned directly to the user.</i></small>
                </div>
            @endhasrole
        </div>

        <div class="card-footer bg-transparent pl-0 mt-3">
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