@extends('layouts.app')
@section('title', 'Vibe and Burn | FAQs')

@section('styles')
<style>
    /* Dark Theme Base */
    body { background-color: #050505; color: #fff; }
    
    .faq-section {
        background: linear-gradient(180deg, #0a0a0a 0%, #000000 100%);
        padding: 80px 0;
        min-height: 100vh;
    }

    /* Brand Typography */
    .text-vibe-red { color: #FF0000; text-shadow: 0 0 12px rgba(255, 0, 0, 0.4); }
    .font-weight-800 { font-weight: 800; }

    /* Search Input Styling */
    .search-group {
        position: relative;
        max-width: 600px;
        margin-bottom: 50px;
    }
    .search-faq {
        background: #111 !important;
        border: 1px solid #222 !important;
        color: #fff !important;
        border-radius: 12px;
        padding: 15px 20px 15px 50px;
        height: 60px;
        font-size: 1.1rem;
    }
    .search-faq:focus {
        border-color: #FF0000 !important;
        box-shadow: 0 0 20px rgba(255, 0, 0, 0.15);
    }
    .search-icon {
        position: absolute;
        left: 20px;
        top: 20px;
        color: #555;
    }

    /* FAQ Card Logic */
    .faq-card {
        background: #0d0d0d;
        border: 1px solid #1a1a1a;
        margin-bottom: 15px;
        border-radius: 12px !important;
        overflow: hidden;
        transition: 0.3s;
    }
    .faq-card:hover { border-color: #333; }
    
    .faq-list {
        padding: 25px;
        color: #fff !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-decoration: none !important;
        font-size: 1.1rem;
        font-weight: 600;
    }

    /* Active State (When open) */
    .faq-list:not(.collapsed) {
        background: rgba(255, 0, 0, 0.05);
        color: #FF0000 !important;
    }
    
    .faq-list i { 
        font-size: 1rem;
        transition: transform 0.3s ease;
        color: #FF0000;
    }
    .faq-list:not(.collapsed) i { transform: rotate(45deg); } /* Turns + into x */

    .card-body {
        background: transparent;
        color: #999;
        padding: 0 25px 25px 25px;
        line-height: 1.8;
    }

    /* Support Sidebar (Staying in its original column) */
    .support-card {
        background: #111;
        border: 2px solid #1a1a1a;
        border-radius: 20px;
        padding: 40px 30px;
        text-align: center;
        transition: 0.3s;
    }
    .support-card:hover { border-color: #FF0000; }

    .btn-vibe-red {
        background: #FF0000;
        color: #fff;
        border: none;
        padding: 15px 30px;
        border-radius: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        width: 100%;
        transition: 0.3s;
    }
    .btn-vibe-red:hover {
        background: #cc0000;
        box-shadow: 0 8px 25px rgba(255, 0, 0, 0.4);
        transform: translateY(-3px);
        color: #fff;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        background: rgba(0, 255, 0, 0.1);
        color: #00FF00;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 20px;
    }
    .dot { height: 8px; width: 8px; background: #00FF00; border-radius: 50%; margin-right: 8px; box-shadow: 0 0 10px #00FF00; }
</style>
@endsection

@section('content')
<section class="faq-section" x-data="{ search: '' }">
    <div class="container">
        <div class="row my-5">
            <div class="col-12 text-center text-lg-left">
                <h6 class="text-vibe-red text-uppercase font-weight-bold letter-spacing-3 mb-2">Knowledge Base</h6>
                <h1 class="text-white font-weight-800 display-4">Got Questions?</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="search-group">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control search-faq" placeholder="Search for answers..." x-model="search">
                </div>

                <div class="accordion" id="faqAccordion">
                    @php
                        $faqs = [
                            ['id' => '1', 'title' => 'What is Partial status?', 'body' => 'Partial Status means we refunded the remains of an order. For example, if you ordered 10,000 and we delivered 9,000, you will be refunded for the 1,000 undelivered units automatically.'],
                            ['id' => '2', 'title' => 'What is Drip Feed?', 'body' => 'Drip Feed helps you build social proof gradually. Instead of getting 1000 likes at once, you can set it to 100 likes every 30 minutes for organic growth.'],
                            ['id' => '3', 'title' => 'How do I use mass order?', 'body' => 'Format: Service_ID | Link | Quantity. Place each request on a new line. You can find Service IDs in our Marketplace section.'],
                            ['id' => '4', 'title' => 'Refill button is not working?', 'body' => 'Refill buttons send a signal to the provider. It is not instant. If the order is outside the refill period or already being processed, the button may not respond.'],
                            ['id' => '5', 'title' => 'Can I get a discount?', 'body' => 'We value our power users. If your monthly spend exceeds $500, please open a ticket for custom VIP pricing.'],
                        ];
                    @endphp

                    @foreach($faqs as $faq)
                    <div class="card faq-card" x-show="search === '' || '{{ strtolower($faq['title']) }}'.includes(search.toLowerCase())">
                        <div class="card-header p-0">
                            <a class="faq-list collapsed" href="javascript:void(0)" data-toggle="collapse" data-target="#collapse{{ $faq['id'] }}">
                                {{ $faq['title'] }}
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                        <div id="collapse{{ $faq['id'] }}" class="collapse" data-parent="#faqAccordion">
                            <div class="card-body">
                                {{ $faq['body'] }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-4 offset-lg-1 mt-5 mt-lg-0">
                <div class="support-card shadow-lg">
                    <div class="status-badge">
                        <span class="dot"></span> 24/7 SUPPORT ACTIVE
                    </div>
                    <h3 class="text-white font-weight-bold mb-3">Still have questions?</h3>
                    <p class="text-muted mb-4">Our agents are online to assist you with order issues, API connections, or custom requests.</p>
                    
                    <a href="{{ route('contactus.create') }}" class="btn btn-vibe-red shadow-lg">
                        <i class="fas fa-ticket-alt mr-2"></i> Create Support Ticket
                    </a>

                    <div class="mt-5">
                        <p class="text-white-50 small text-uppercase font-weight-bold mb-3">Priority Support</p>
                        <div class="d-flex justify-content-center">
                            <a href="#" class="text-white mx-3"><i class="fab fa-telegram fa-2x"></i></a>
                            <a href="#" class="text-white mx-3"><i class="fab fa-whatsapp fa-2x"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection