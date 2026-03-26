<div class="nk-block">
    {{-- Header Section --}}
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">Support Tickets</h3>
                <div class="nk-block-des text-soft">
                    <p>You have total <strong>{{ $tickets->total() }}</strong> support requests.</p>
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
                        {{-- Category Selection --}}
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

                        @if($category_id)
                            @php
                                $selectedCategoryName = optional($categories->find($category_id))->name;
                                $lowName = strtolower($selectedCategoryName);
                            @endphp

                            {{-- Subject Dropdown (Better UX) --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="subject">Subject / Issue Type</label>
                                    <div class="form-control-wrap">
                                        <select wire:model.live="subject" class="form-control" id="subject">
                                            <option value="">Choose an issue...</option>
                                            
                                            @if(Str::contains($lowName, ['order', 'refill', 'cancel']))
                                                <option value="Refill Not Working">Refill Not Working</option>
                                                <option value="Order Not Started">Order Not Started</option>
                                                <option value="Partial/Drop Issue">Partial/Drop Issue</option>
                                                <option value="Cancel Request">Cancel Request</option>
                                            @elseif(Str::contains($lowName, ['payment', 'deposit']))
                                                <option value="Payment Not Added">Payment Not Added</option>
                                                <option value="Double Charged">Double Charged</option>
                                                <option value="MTN MoMo Inquiry">MTN MoMo Inquiry</option>
                                            @else
                                                <option value="General Question">General Question</option>
                                                <option value="Account Issue">Account Issue</option>
                                            @endif
                                            
                                            <option value="Other">Other (Type manually)</option>
                                        </select>
                                    </div>
                                    @error('subject') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Manual Subject Input (Only shows if "Other" is picked) --}}
                            @if($subject === 'Other')
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="other_subject">Specify Subject</label>
                                        <div class="form-control-wrap">
                                            <input type="text" wire:model="other_subject" class="form-control" id="other_subject" placeholder="What is the issue about?">
                                        </div>
                                        @error('other_subject') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif

                            {{-- Order ID Field --}}
                            @if(Str::contains($lowName, ['order', 'refill', 'cancel']))
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

                            {{-- Message --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="message">Message (Optional)</label>
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
        {{-- Tickets Table Section --}}
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