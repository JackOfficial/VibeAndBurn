@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.tickets.list') }}">Support Tickets</a></li>
                    <li class="breadcrumb-item active">Ticket #{{ $ticketId }}</li>
                </ol>
            </nav>

            @livewire('user.ticket-detail-component', ['id' => $ticketId])
        </div>
    </div>
</div>

<script>
    window.addEventListener('contentChanged', event => {
        var objDiv = document.querySelector(".nk-reply");
        objDiv.scrollTop = objDiv.scrollHeight;
    });
</script>

@endsection