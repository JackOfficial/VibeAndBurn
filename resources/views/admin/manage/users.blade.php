@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    
                    {{-- Alert Messages --}}
                    @if (session('deleteUserSuccess'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><i class="fas fa-check-circle"></i></strong> {{ session('deleteUserSuccess') }}
                        </div>
                    @elseif(session('deleteUserFail'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><i class="fas fa-exclamation-triangle"></i></strong> {{ session('deleteUserFail') }}
                        </div>
                    @endif

                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Total Users: <strong>{{ $usersCounter }}</strong></h3>
                        </div>
                        
                        <div class="card-body p-0"> {{-- p-0 makes the table flush with card edges --}}
                            <table class="table table-hover table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Roles</th> {{-- Added Roles Column --}}
                                        <th>Joined</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            {{-- Use $user->id or adjusted iteration for pagination --}}
                                            <td>{{ $user->id }}</td> 
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($user->avatar)
                                                        <img src="{{ $user->avatar }}" class="img-circle elevation-1 mr-2" style="width: 30px;">
                                                    @endif
                                                    {{ $user->name }}
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @foreach($user->roles as $role)
                                                    <span class="badge {{ $role->name == 'Super Admin' ? 'badge-danger' : ($role->name == 'Admin' ? 'badge-warning' : 'badge-info') }}">
                                                        {{ $role->name }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                                            <td class="text-right">
                                                {{-- Only allow deleting if it's NOT the current logged-in user --}}
                                                @if(auth()->id() !== $user->id)
                                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}" 
                                                          onsubmit="return confirm('Are you sure you want to delete this user? This cannot be undone.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-tool text-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="badge badge-light">Current User</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No users found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- Pagination Links --}}
                        <div class="card-footer clearfix">
                            <div class="float-right">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection