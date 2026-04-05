@extends('layouts.app')
@section('title', 'Vibe and Burn | Terms & Conditions')

@section('content')
<div class="terms-page-wrapper bg-light py-5 mt-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold text-dark">Terms and Conditions</h1>
                    <p class="lead text-muted">Please review our rules and guidelines for using the Vibe & Burn platform.</p>
                    <div class="d-inline-block bg-white px-3 py-1 rounded-pill shadow-sm border">
                        <small class="text-primary fw-bold">Effective Date: {{ date('F d, Y') }}</small>
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body p-4 p-md-5 bg-white" style="border-radius: 20px;">
                        
                        <div class="terms-html-content">
                            {!! $terms->terms !!}
                        </div>

                        <hr class="my-5 opacity-25">

                        <div class="bg-light rounded p-4 text-center">
                            <h5 class="fw-bold text-dark">Questions?</h5>
                            <p class="text-muted mb-3">If you have any questions regarding these terms, our support team is ready to help.</p>
                            <a href="{{ url('/tickets') }}" class="btn btn-primary px-4 fw-bold rounded-pill">
                                <i class="fas fa-headset me-2"></i> Contact Support
                            </a>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="text-muted small">
                        &copy; {{ date('Y') }} Vibe & Burn SMM. All rights reserved. 
                        <span class="mx-2">|</span> 
                        <a href="{{ url('/') }}" class="text-decoration-none text-primary">Back to Home</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* Professional Typography for Public Pages */
    .terms-page-wrapper {
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    .terms-html-content {
        color: #2d3748;
        line-height: 1.8;
    }

    .terms-html-content h2 {
        font-size: 1.75rem;
        font-weight: 800;
        margin-top: 3rem;
        margin-bottom: 1.25rem;
        color: #1a202c;
        display: flex;
        align-items: center;
    }

    /* Adds a subtle primary line next to headings for a "World Class" look */
    .terms-html-content h2::before {
        content: "";
        width: 4px;
        height: 24px;
        background-color: #0d6efd; /* Bootstrap Primary Blue */
        margin-right: 12px;
        border-radius: 4px;
    }

    .terms-html-content h3 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-top: 2rem;
        color: #2d3748;
    }

    .terms-html-content p {
        margin-bottom: 1.5rem;
        font-size: 1.05rem;
    }

    /* Styling lists to look professional */
    .terms-html-content ul {
        padding-left: 1.5rem;
        margin-bottom: 2rem;
    }

    .terms-html-content ul li {
        margin-bottom: 0.75rem;
        padding-left: 0.5rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2.2rem;
        }
        .card-body {
            padding: 2rem !important;
        }
    }
</style>
@endsection