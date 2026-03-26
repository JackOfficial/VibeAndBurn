@extends('user dashboard.dashboard')
<style>
    /* Force parents to allow full height if they are squashing the chat */
    .nk-content-body, .nk-block {
        display: flex !important;
        flex-direction: column !important;
        height: 100% !important;
    }

    .nk-msg-custom-container {
        /* This ensures the chat is at least 800px or 85% of the screen */
        height: calc(100vh - 150px); 
        min-height: 700px; 
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid #dbdfea;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .nk-msg-reply-scroll {
        flex: 1 1 auto; /* Forces this area to grow and take all space */
        overflow-y: auto;
        padding: 2.5rem 1.5rem;
        background-color: #f5f6fa;
        /* Smooth scrolling for better UX */
        scroll-behavior: smooth;
    }

    /* Message Bubbles */
    .bubble-msg {
        max-width: 80%;
        padding: 1rem 1.25rem;
        font-size: 15px;
        line-height: 1.6;
        position: relative;
        box-shadow: 0 2px 4px rgba(32, 45, 122, 0.05);
    }

    /* Admin Bubble (Left) */
    .admin-bubble {
        background: #ffffff;
        color: #364a63;
        border: 1px solid #e5e9f2;
        border-radius: 0 18px 18px 18px;
    }

    /* User Bubble (Right) */
    .user-bubble {
        background: #6576ff;
        color: #ffffff;
        border-radius: 18px 18px 0 18px;
    }

    .reply-form-fixed {
        flex: 0 0 auto; /* Form never shrinks or grows, stays fixed size */
        background: #fff;
        border-top: 1px solid #dbdfea;
        padding: 1.5rem 2rem;
        z-index: 10;
    }

    /* Custom Scrollbar for a "Big" modern feel */
    .nk-msg-reply-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .nk-msg-reply-scroll::-webkit-scrollbar-thumb {
        background-color: #dbdfea;
        border-radius: 10px;
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