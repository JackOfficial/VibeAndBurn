@extends('layouts.app')
@section('title', 'Services | Vibe and Burn')

@section('styles')
<style>
    /* Global Section Overrides */
    body { background-color: #000; }
    .bg-light { background-color: #080808 !important; }
    
    /* Navigation Buttons */
    .service-nav { gap: 12px; }
    .nav-btn {
        border: 1px solid #222;
        background: #111;
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 700;
        transition: all 0.3s ease;
        color: #888;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
    }
    .nav-btn:hover { 
        background: #1a1a1a; 
        color: #fff;
        border-color: #444;
        transform: translateY(-2px); 
    }
    .nav-btn.active {
        background: #FF0000;
        color: white;
        border-color: #FF0000;
        box-shadow: 0 0 20px rgba(255, 0, 0, 0.4);
    }

    /* Search Input Styling */
    .search-input {
        background-color: #111 !important;
        border-radius: 50px;
        padding-left: 45px;
        height: 50px;
        border: 1px solid #222 !important;
        color: #fff !important;
        transition: all 0.3s;
    }
    .search-input:focus {
        border-color: #FF0000 !important;
        box-shadow: 0 0 15px rgba(255, 0, 0, 0.2) !important;
    }
    .search-icon {
        position: absolute;
        left: 20px;
        top: 17px;
        color: #FF0000;
        z-index: 5;
    }

    /* Table Styling */
    .table-card {
        background: #0d0d0d;
        border: 1px solid #1a1a1a;
        border-radius: 20px;
    }
    .table thead th { 
        border: none; 
        font-size: 0.75rem; 
        font-weight: 800;
        text-transform: uppercase; 
        letter-spacing: 1.5px;
        padding: 20px 15px;
    }
    .table td { 
        vertical-align: middle !important; 
        border-top: 1px solid #1a1a1a; 
        padding: 18px 10px;
        color: #ccc;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(255, 0, 0, 0.03);
    }

    /* Badges & Accents */
    .badge-vibe {
        background: #1a1a1a;
        border: 1px solid #333;
        color: #eee;
        padding: 5px 10px;
        border-radius: 6px;
    }
    .text-success { color: #00ff88 !important; } /* Neon success for rates */

    /* Pagination */
    .btn-pagination {
        background: #111;
        border: 1px solid #222;
        color: #fff;
        border-radius: 50px;
        transition: 0.3s;
    }
    .btn-pagination:hover:not(:disabled) {
        border-color: #FF0000;
        color: #FF0000;
    }
    .btn-pagination:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }
</style>
@endsection

@section('content')
<section class="section pb-5 mt-5 bg-light border-bottom border-dark">
    <div class="container mt-5">
        <div class="row justify-content-center mt-5">
            <div class="col-lg-8 text-center mt-5">
                <h6 class="text-uppercase letter-spacing-2 font-weight-bold" style="color: #FF0000;">Marketplace</h6>
                <h2 class="font-weight-bold text-white display-4">Premium <span style="text-shadow: 0 0 20px rgba(255,0,0,0.3);">SMM Services</span></h2>
                <p style="color: #888;">Dominate the algorithm. High-retention engagement for the world's biggest platforms.</p>
            </div>
        </div>
    </div>
</section>

<section class="section py-5 bg-black" 
         x-data="{ 
            tab: 'youtube', 
            search: '', 
            page: 1, 
            perPage: 15,
            resetPage() { this.page = 1 } 
         }">
    
    <div class="container-fluid px-lg-5">
        
        <div class="row align-items-center mb-5">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="d-flex flex-wrap service-nav">
                    @foreach(['youtube' => 'YouTube', 'tiktok' => 'TikTok', 'instagram' => 'Instagram', 'facebook' => 'Facebook'] as $key => $label)
                        <button @click="tab = '{{ $key }}'; resetPage()" :class="tab === '{{ $key }}' ? 'active' : ''" class="nav-btn">
                            <i class="fab fa-{{ $key === 'facebook' ? 'facebook-f' : $key }} mr-2"></i> {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="position-relative">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           class="form-control search-input" 
                           placeholder="Filter 12,000+ services..." 
                           x-model="search"
                           @input="resetPage()">
                </div>
            </div>
        </div>

        <div class="table-card overflow-hidden">
            <div class="table-responsive">
                @php
                    $categories = [
                        'youtube' => ['data' => $YouTube, 'color' => '#1a0000'], // Dark red tint
                        'tiktok' => ['data' => $TikTok, 'color' => '#000000'],
                        'instagram' => ['data' => $Instagram, 'color' => '#1a000a'],
                        'facebook' => ['data' => $Facebook, 'color' => '#00081a']
                    ];
                @endphp

                @foreach($categories as $key => $cat)
                <table class="table align-middle mb-0 text-white" x-show="tab === '{{ $key }}'" x-transition>
                    <thead>
                        <tr style="background-color: {{ $cat['color'] }};">
                            <th class="py-4 pl-4" style="width: 100px; color: #555;">ID</th>
                            <th class="py-4">Service Name</th>
                            <th class="py-4">Rate / 1k</th>
                            <th class="py-4 text-center">Limit (Min/Max)</th>
                            <th class="py-4 text-center">Delivery</th>
                            <th class="py-4 text-right pr-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cat['data'] as $index => $item)
                        <tr x-show="(search === '' || '{{ strtolower($item->service) }}'.includes(search.toLowerCase())) && 
                                    (search !== '' || ({{ $loop->index }} >= (page - 1) * perPage && {{ $loop->index }} < page * perPage))"
                            x-transition:enter="transition ease-out duration-200">
                            <td class="pl-4"><span style="color: #444; font-family: monospace;">#{{ $item->id }}</span></td>
                            <td><span class="font-weight-bold text-white">{{ $item->service }}</span></td>
                            <td><span class="text-success font-weight-bold">${{ number_format((float)$item->rate_per_1000, 2) }}</span></td>
                            <td class="text-center">
    <span class="badge-vibe">{{ number_format((float)$item->min_order) }}</span>
    <span class="mx-1 text-muted">→</span>
    <span class="badge-vibe">{{ number_format((float)$item->max_order) }}</span>
</td>
                            <td class="text-center small">
                                <i class="far fa-bolt text-warning mr-1"></i> {{ $item->Average_completion_time ?? 'Instant' }}
                            </td>
                            <td class="text-right pr-4">
                                <button class="btn btn-outline-danger btn-sm rounded-pill px-3" data-toggle="modal" data-target="#details" style="border-width: 2px; font-weight: 700;">
                                    VIEW
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endforeach
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-5 px-3" x-show="search === ''">
            <div class="text-muted small mb-3 mb-md-0">
                <span style="color: #444;">VIEWING PAGE</span> <span x-text="page" class="text-white font-weight-bold mx-2"></span>
                <select x-model="perPage" @change="resetPage()" class="custom-select custom-select-sm bg-dark border-secondary text-white w-auto ml-2" style="border-radius: 5px;">
                    <option value="10">10 Rows</option>
                    <option value="15">15 Rows</option>
                    <option value="25">25 Rows</option>
                </select>
            </div>
            
            <div class="d-flex align-items-center">
                <button class="btn btn-pagination px-4 py-2 mr-2" 
                        :disabled="page === 1" 
                        @click="page--; window.scrollTo({top: 400, behavior: 'smooth'})">
                    <i class="fas fa-arrow-left mr-2"></i> PREV
                </button>
                <button class="btn btn-pagination px-4 py-2" 
                        @click="page++; window.scrollTo({top: 400, behavior: 'smooth'})">
                    NEXT <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>
    </div>
</section>
@endsection