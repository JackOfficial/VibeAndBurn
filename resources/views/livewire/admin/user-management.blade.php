<div>
    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show m-2">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Users ({{ $usersCounter }})</h3>
            
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Search name or email...">
                    <div class="input-group-append">
                        <span class="input-group-text bg-white">
                            {{-- Loading Spinner for search --}}
                            <div wire:loading wire:target="search" class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            <i wire:loading.remove wire:target="search" class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                  <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px">#</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th class="text-right px-4">Actions</th>
                    </tr>
                </thead>
                <tbody wire:loading.class="text-muted">
                    @forelse ($users as $user)
                        <tr wire:key="user-{{ $user->id }}">
                            <td class="align-middle">{{ $user->id }}</td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    {{-- Avatar Logic --}}
                                    @if($user->avatar)
                                        <img src="{{ $user->avatar }}" class="img-circle border shadow-sm mr-2" style="width: 35px; height: 35px; object-fit: cover;">
                                    @else
                                        @php
                                            $initial = strtoupper(substr($user->name, 0, 1));
                                            $colors = ['#6f42c1', '#007bff', '#28a745', '#dc3545', '#fd7e14', '#17a2b8'];
                                            $bgColor = $colors[ord($initial) % count($colors)];
                                        @endphp
                                        <div class="avatar-initial mr-2 shadow-sm" style="background-color: {{ $bgColor }}">
                                            {{ $initial }}
                                        </div>
                                    @endif
                                    <span class="font-weight-bold"><a href="{{ route('users.show', $user->id) }}">{{ $user->name }}</a></span>
                                    @if($user->status === 'banned')
                                    <span class="badge badge-danger px-1" style="font-size: 10px;">BANNED</span>
                                    @endif
                                </div>
                            </td>
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="align-middle">
                                @foreach($user->roles as $role)
                                    @php
                                        $badgeClass = match($role->name) {
                                            'Super Admin' => 'badge-danger',
                                            'Admin' => 'badge-warning',
                                            default => 'badge-info',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-2 py-1">{{ $role->name }}</span>
                                @endforeach
                            </td>
                           <td class="text-right align-middle px-4">
    <div class="btn-group">
        {{-- Edit Button --}}
        <a href="{{ url('admin/users/'.$user->id.'/edit') }}" 
           class="btn btn-sm btn-default" 
           title="Edit User">
            <i class="fas fa-edit text-primary"></i>
        </a>

        @if(auth()->id() !== $user->id)
            {{-- Ban/Unban Button --}}
            <button wire:click="toggleBan({{ $user->id }})" 
                    class="btn btn-sm btn-default" 
                    title="{{ $user->status === 'banned' ? 'Unban User' : 'Ban User' }}">
                <i class="fas {{ $user->status === 'banned' ? 'fa-unlock text-success' : 'fa-user-slash text-warning' }}"></i>
            </button>

            {{-- Delete Button --}}
            <button wire:click="deleteUser({{ $user->id }})" 
                    wire:confirm="Are you sure you want to permanently delete {{ $user->name }}?" 
                    class="btn btn-sm btn-default"
                    title="Delete User">
                <i class="fas fa-trash-alt text-danger"></i>
            </button>
        @else
            <span class="badge badge-light border ml-2">You</span>
        @endif
    </div>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
        
        <div class="card-footer clearfix">
            <div class="float-right">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>