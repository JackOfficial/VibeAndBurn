<div class="nk-footer">
    <div class="container-fluid">
        <div class="mb-3">
            <h6 class="overline-title text-soft">Share & Earn</h6>
            <ul class="nav nav-sm">
                @php 
                    $shareUrl = route('admin.sharedlink.show', Auth::id());
                    $shareText = rawurlencode("Check out the best SMM panel: ");
                @endphp

                {{-- WhatsApp --}}
                <li class="nav-item">
                    <a class="nav-link text-success" href="https://api.whatsapp.com/send?text={{ $shareText . $shareUrl }}" target="_blank">
                        <em class="icon ni ni-whatsapp"></em>
                    </a>
                </li>

                {{-- Facebook --}}
                <li class="nav-item">
                    <a class="nav-link text-primary" href="https://www.facebook.com/sharer.php?u={{ $shareUrl }}" target="_blank">
                        <em class="icon ni ni-facebook-circle"></em>
                    </a>
                </li>
                {{-- Twitter/X --}}
                <li class="nav-item">
                    <a class="nav-link text-dark" href="https://twitter.com/share?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank">
                        <em class="icon ni ni-twitter"></em>
                    </a>
                </li>

                {{-- Email --}}
                <li class="nav-item">
                    <a class="nav-link text-danger" href="mailto:?subject=Social Media Growth&body={{ $shareText . $shareUrl }}">
                        <em class="icon ni ni-mail-fill"></em>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nk-footer-wrap">
            <div class="nk-footer-copyright"> 
                &copy; {{ date('Y') }} <a href="{{ url('/') }}" class="text-soft">Vibe & Burn</a>. All rights reserved.
            </div>
            
            <div class="nk-footer-links">
                <ul class="nav nav-sm">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/terms-police') }}">Terms</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/terms-police') }}">Privacy</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contactus.create') }}">Support</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>