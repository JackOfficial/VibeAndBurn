<div>
    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible"><button class="close" data-dismiss="alert">&times;</button>{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible"><button class="close" data-dismiss="alert">&times;</button>{{ session('error') }}</div>
    @endif

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Users ({{ $usersCounter }})</h3>
            <div class="card-tools">
                <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Search name or email...">
            </div>
        </div>
        
        <div class="card-body p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr wire:key="user-{{ $user->id }}">
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge badge-info">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="text-right">
                                @if(auth()->id() !== $user->id)
                                    <button wire:click="deleteUser({{ $user->id }})" 
                                            wire:confirm="Are you sure?" 
                                            class="btn btn-tool text-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    </div>
</div>