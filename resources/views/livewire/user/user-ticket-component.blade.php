<div class="nk-block">
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">Support Tickets</h3>
                <div class="nk-block-des text-soft">
                    <p>You have total {{ $tickets->total() }} support requests.</p>
                </div>
            </div>
            <div class="nk-block-head-content">
                <div class="toggle-wrap nk-block-tools-toggle">
                    <button wire:click="toggleCreate" class="btn btn-primary">
                        <em class="icon ni {{ $isCreating ? 'ni-arrow-left' : 'ni-plus' }}"></em>
                        <span>{{ $isCreating ? 'Back to Tickets' : 'Open New Ticket' }}</span>
                    </button>
                </div>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="subject">Subject</label>
                                <div class="form-control-wrap">
                                    <input type="text" wire:model="subject" class="form-control" id="subject" placeholder="e.g., Order not delivered">
                                </div>
                                @error('subject') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
    <div class="form-group">
        <label for="category">Category</label>
        <select wire:model="category_id" class="form-control" id="category">
            <option value="">Select Category</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        @error('category_id') 
            <span class="text-danger small">{{ $message }}</span> 
        @enderror
    </div>
</div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="order_id">Order ID (Optional)</label>
                                <div class="form-control-wrap">
                                    <div class="form-icon form-icon-left"><em class="icon ni ni-cart"></em></div>
                                    <input type="text" wire:model="order_id" class="form-control" id="order_id" placeholder="Input Order ID">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="message">Message</label>
                                <div class="form-control-wrap">
                                    <textarea wire:model="message" class="form-control form-control-simple no-resize" id="message" rows="5" placeholder="Describe your issue in detail..."></textarea>
                                </div>
                                @error('message') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-lg btn-primary">
                                <em class="icon ni ni-send"></em> <span>Submit Ticket</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="card card-bordered card-stretch">
            <div class="card-inner-group">
                <div class="card-inner p-0">
                    <div class="nk-tb-list nk-tb-ulist">
                        {{-- Table Header --}}
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
                                        <span>View</span>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="nk-tb-item">
                                <div class="nk-tb-col text-center w-100 py-5 text-soft">
                                    <p>No tickets found. Need help? Open a ticket above!</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-inner">
                    <div class="nk-block-between-md g-3">
                        <div class="g">
                            {{ $tickets->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>