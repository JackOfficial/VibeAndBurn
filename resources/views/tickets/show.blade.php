@extends('user dashboard.dashboard')
<style>
    .nk-msg-custom-container {
        height: 80vh; /* Takes up 80% of the screen height */
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid #dbdfea;
        border-radius: 8px;
        overflow: hidden;
    }

    .nk-msg-reply-scroll {
        flex-grow: 1; /* Fills all available middle space */
        overflow-y: auto;
        padding: 2rem;
        background-color: #f5f6fa;
    }

    .bubble-msg {
        max-width: 85%;
        padding: 1rem;
        font-size: 15px;
        line-height: 1.5;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .reply-form-fixed {
        flex-shrink: 0; /* Prevents the form from being squeezed */
        background: #fff;
        border-top: 1px solid #dbdfea;
        padding: 1.5rem;
    }
</style>

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
    function scrollToBottom() {
        const chatContainer = document.getElementById('chat-container');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    }

    // Run on load
    window.onload = scrollToBottom;

    // Run when Livewire updates (message sent)
    window.addEventListener('contentChanged', event => {
        scrollToBottom();
    });
</script>
@endsection