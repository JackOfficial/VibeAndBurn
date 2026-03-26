@extends('user dashboard.dashboard')
@section('title', 'Vibe&burn - My Tickets')

@section('content')
<div class="nk-content-inner">
    <div class="nk-content-body">
        <div class="components-preview wide-md mx-auto">

            <div class="nk-block">
                {{-- This component lists all tickets; it doesn't need an ID passed to it --}}
                @livewire('user.user-ticket-component')
            </div>
        </div>
    </div>
</div>
@endsection