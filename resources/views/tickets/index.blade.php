@extends('user dashboard.dashboard')
@section('title', 'Vibe&burn - View Ticket #' . $ticketId)

@section('content')
<div class="nk-content-inner">
    <div class="nk-content-body">
        <div class="components-preview wide-md mx-auto">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Ticket Support</h3>
                        <div class="nk-block-des text-soft">
                            <p>View conversation for Ticket #{{ $ticketId }}</p>
                        </div>
                    </div><div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-outline-light btn-white">
                                <em class="icon ni ni-arrow-left"></em><span>Back to List</span>
                            </a>
                        </div>
                    </div></div></div><div class="nk-block">
                @livewire('user.ticket-detail-component', ['id' => $ticketId])
            </div></div></div>
</div>
@endsection