@extends('user dashboard.dashboard')
@section('title', 'Vibe&burn - My Tickets')

@section('content')
<div class="nk-content-inner">
    <div class="nk-content-body">
        <div class="components-preview wide-md mx-auto">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Support Tickets</h3>
                    <div class="nk-block-des text-soft">
                        {{-- Use the $wallet variable passed from your controller --}}
                        <p>Manage your support requests. Current Balance: {{ number_format($wallet, 2) }} RWF</p>
                    </div>
                </div>
            </div>

            <div class="nk-block">
                {{-- This component lists all tickets; it doesn't need an ID passed to it --}}
                @livewire('user.user-ticket-component')
            </div>
        </div>
    </div>
</div>
@endsection