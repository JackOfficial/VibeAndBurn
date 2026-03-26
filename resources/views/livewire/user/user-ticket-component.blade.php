<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Support Tickets</h2>
        <button wire:click="toggleCreate" class="btn {{ $isCreating ? 'btn-secondary' : 'btn-primary' }}">
            {{ $isCreating ? 'Back to Tickets' : 'Open New Ticket' }}
        </button>
    </div>

    @if(session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($isCreating)
        <div class="card shadow-sm">
            <div class="card-body">
                <form wire:submit.prevent="createTicket">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Subject</label>
                            <input type="text" wire:model="subject" class="form-control" placeholder="e.g., Order not delivered">
                            @error('subject') <span class="text-danger small">{{ $message }}</span> @error
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Category</label>
                            <select wire:model="category_id" class="form-control">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-danger small">{{ $message }}</span> @error
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Order ID (Optional)</label>
                            <input type="text" wire:model="order_id" class="form-control" placeholder="If related to a specific order">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Message</label>
                            <textarea wire:model="message" class="form-control" rows="5" placeholder="Describe your issue in detail..."></textarea>
                            @error('message') <span class="text-danger small">{{ $message }}</span> @error
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Submit Ticket</button>
                </form>
            </div>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Subject</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Last Update</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td>#{{ $ticket->id }}</td>
                                <td><strong>{{ $ticket->subject }}</strong></td>
                                <td>{{ $ticket->category->name ?? 'General' }}</td>
                                <td>
                                    <span class="badge {{ $ticket->status == 'pending' ? 'bg-warning' : ($ticket->status == 'answered' ? 'bg-success' : 'bg-secondary') }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>
                                <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('user.tickets.view', $ticket->id) }}" class="btn btn-sm btn-outline-info">View Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No tickets found. Need help? Open a ticket above!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $tickets->links() }}
            </div>
        </div>
    @endif
</div>