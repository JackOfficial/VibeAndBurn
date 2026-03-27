@extends('admin.layouts.app')

{{-- Optional: Ensure Livewire styles are loaded if not in your main layout --}}
@push('styles')
    <style>
      .avatar-initial {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 14px;
    text-transform: uppercase;
}
    </style>
@endpush

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
                        <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            {{-- 
                This single line replaces all your old table and 
                pagination logic with the real-time Livewire component 
            --}}
            @livewire('admin.user-management')
        </div>
    </section>
</div>

@endsection