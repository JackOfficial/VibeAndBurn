<div class="nk-block">
    {{-- Header Section --}}
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">Support Tickets</h3>
                <div class="nk-block-des text-soft">
                    <p>You have total **{{ $tickets->total() }}** support requests.</p>
                </div>
            </div>
            <div class="nk-block-head-content">
                <button wire:click="toggleCreate" class="btn btn-primary">
                    <em class="icon ni {{ $isCreating ? 'ni-arrow-left' : 'ni-plus' }}"></em>
                    <span>{{ $isCreating ? 'Back to Tickets' : 'Open New Ticket' }}</span>
                </button>
            </div>
        </div>
    </div>

    @if(session()->has('success'))
        <div class="alert alert-pro alert-success alert-icon mb-4">
            <em class="icon ni ni-check-circle"></em> <strong>Success!</strong> {{ session('success') }}
        </div>
    @endif

    @if($isCreating)
        <div class="card card-bordered shadow-sm">
            <div class="card-inner">
                <form wire:submit.prevent="createTicket">
                    <div class="row g-gs">
                        {{-- Category Selection (First) --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="category">Select Ticket Category</label>
                                <select wire:model.live="category_id" class="form-control" id="category">
                                    <option value="">Choose a category...</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Dynamic Fields based on Category --}}
                        @if($category_id)
                            {{-- Common Field: Subject --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="subject">Subject</label>
                                    <div class="form-control-wrap">
                                        <input type="text" wire:model="subject" class="form-control" id="subject" placeholder="Summary of your issue">
                                    </div>
                                    @error('subject') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Dynamic: Order ID (Show for 'Order' categories) --}}
                            @php
                                $selectedCategoryName = optional($categories->find($category_id))->name;
                            @endphp

                            @if(Str::contains(strtolower($selectedCategoryName), ['order', 'refill', 'cancel']))
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="order_id">Order ID</label>
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-left"><em class="icon ni ni-cart"></em></div>
                                            <input type="text" wire:model="order_id" class="form-control" id="order_id" placeholder="Enter your Order ID">
                                        </div>
                                        @error('order_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif

                            {{-- Dynamic: Payment Method (Show for 'Payment' categories) --}}
                            @if(Str::contains(strtolower($selectedCategoryName), ['payment', 'deposit']))
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="payment_ref">Transaction ID / Proof</label>
                                        <div class="form-control-wrap">
                                            <input type="text" wire:model="payment_ref" class="form-control" id="payment_ref" placeholder="e.g., MTN MoMo Ref or Transaction Hash">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Message --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="message">Message Details</label>
                                    <div class="form-control-wrap">
                                        <textarea wire:model="message" class="form-control form-control-simple no-resize" id="message" rows="4" placeholder="Describe your issue in detail..."></textarea>
                                    </div>
                                    @error('message') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-lg btn-primary">
                                    <em class="icon ni ni-send"></em> <span>Submit Ticket</span>
                                </button>
                            </div>
                        @else
                            <div class="col-12 text-center py-4">
                                <p class="text-soft">Please select a category to continue.</p>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    @else
        {{-- Tickets Table Section (Same as before) --}}
        <div class="card card-bordered card-stretch">
            <div class="card-inner-group">
                <div class="card-inner p-0">
                    <div class="nk-tb-list nk-tb-ulist">
                        <div class="nk-tb-item nk-tb-head">
                            <div class="nk-tb-col"><span class="sub-text">ID</span></div>
                            <div class="nk-tb-col"><span class="sub-text">Subject</span></div>
                            <div class="nk-tb-col tb-col-md"><span class="sub-text">Category</span></div>
                            <div class="nk-tb-col tb-col-sm"><span class="sub-text">Status</span></div>
                            <div class="nk-tb-col tb-col-lg"><span class="sub-text">Last Update</span></div>
                            <div class="nk-tb-col text-right"><span class="sub-text">Action</span></div>
                        </div>

                        @forelse($tickets as $ticket)
                            <div class="nk-tb-item">
                                <div class="nk-tb-col">
                                    <span class="text-primary fw-bold">#{{ $ticket->id }}</span>
                                </div>
                                <div class="nk-tb-col">
                                    <span class="tb-lead">{{ Str::limit($ticket->subject, 35) }}</span>
                                </div>
                                <div class="nk-tb-col tb-col-md">
                                    <span class="sub-text">{{ $ticket->category->name ?? 'General' }}</span>
                                </div>
                                <div class="nk-tb-col tb-col-sm">
                                    @php
                                        $statusClass = match($ticket->status) {
                                            'answered' => 'badge-success',
                                            'pending' => 'badge-warning',
                                            'closed' => 'badge-secondary',
                                            default => 'badge-primary'
                                        };
                                    @endphp
                                    <span class="badge badge-dot {{ $statusClass }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </div>
                                <div class="nk-tb-col tb-col-lg">
                                    <span class="sub-text">{{ $ticket->updated_at->diffForHumans() }}</span>
                                </div>
                                <div class="nk-tb-col text-right">
                                    <a href="{{ route('user.tickets.view', $ticket->id) }}" class="btn btn-sm btn-dim btn-primary">
                                        View
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="nk-tb-item">
                                <div class="nk-tb-col text-center w-100 py-5 text-soft">
                                    No tickets found. Need help? Open a ticket above!
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-inner">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    @endif
</div>