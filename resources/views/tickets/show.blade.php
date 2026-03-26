@extends('user dashboard.dashboard')
@push('styles')
<style>
    /* Force DashLite layout to allow full-height children */
    .nk-content, .nk-content-inner, .container-fluid, .nk-content-body {
        height: 100% !important;
        min-height: 100% !important;
        padding-bottom: 0 !important;
        display: flex !important;
        flex-direction: column !important;
    }

    .nk-msg-custom-container {
        /* Use a fixed height based on the viewport minus header/footer */
        height: calc(100vh - 180px) !important; 
        display: flex !important;
        flex-direction: column !important;
        background: #fff;
        border: 1px solid #dbdfea;
        border-radius: 12px;
        overflow: hidden;
    }

    .nk-msg-reply-scroll {
        flex-grow: 1 !important;
        overflow-y: auto !important;
        background-color: #f5f6fa;
        padding: 1.5rem;
    }

    .reply-form-fixed {
        flex-shrink: 0 !important;
        background: #fff;
        border-top: 1px solid #dbdfea;
        padding: 1rem 1.5rem;
    }

    .bubble-msg {
        max-width: 80%;
        padding: 0.8rem 1.2rem;
        border-radius: 15px;
        font-size: 15px;
        margin-bottom: 5px;
    }
    .admin-bubble { background: #fff; border: 1px solid #e5e9f2; color: #364a63; border-top-left-radius: 0; }
    .user-bubble { background: #6576ff; color: #fff; border-top-right-radius: 0; }
</style>
@endpush

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