@extends('admin.layouts.app')

@push('styles')
<style>
    .profile-user-img { width: 100px; height: 100px; object-fit: cover; }
    .avatar-initial-lg {
        width: 100px; height: 100px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 40px; font-weight: bold; margin: 0 auto;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User Profile: {{ $user->name }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ url('admin/users') }}" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                {{-- Left Column: User Summary --}}
                <div class="col-md-3">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                @if($user->avatar)
                                    <img class="profile-user-img img-fluid img-circle border-2" 
                                         src="{{ $user->avatar }}" alt="User profile picture">
                                @else
                                    <div class="avatar-initial-lg bg-primary shadow-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <h3 class="profile-username text-center mt-3">{{ $user->name }}</h3>
                            <p class="text-muted text-center">{{ $user->email }}</p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Account Status</b> 
                                    <span class="float-right badge {{ $user->status == 'active' ? 'badge-success' : 'badge-danger' }}">
                                        {{ strtoupper($user->status) }}
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>Joined</b> <span class="float-right text-sm text-muted">{{ $user->created_at->format('d M Y') }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>SMM Balance</b> <span class="float-right text-bold text-success">${{ number_format($user->balance, 2) }}</span>
                                </li>
                            </ul>

                            @if(auth()->id() !== $user->id)
                                <button onclick="confirm('Are you sure?') || event.stopImmediatePropagation()" 
                                        wire:click="deleteUser({{ $user->id }})" 
                                        class="btn btn-danger btn-block"><b>Delete Account</b></button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Column: Livewire Edit Component --}}
                <div class="col-md-9">
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="userTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="details-tab" data-toggle="pill" href="#details" role="tab">Account Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="orders-tab" data-toggle="pill" href="#orders" role="tab">Order History</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="details" role="tabpanel">
                                    {{-- CALLING THE LIVEWIRE EDIT COMPONENT --}}
                                    @livewire('admin.users.edit-component', ['user' => $user])
                                </div>
                                <div class="tab-pane fade" id="orders" role="tabpanel">
                                    {{-- You can add an Order History table here later --}}
                                    <p class="text-muted">Loading user order history...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection