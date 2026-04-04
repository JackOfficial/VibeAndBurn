<div class="row">
    <div class="col-12">
        {{-- Flash Message --}}
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 class="alert alert-success border-0 shadow-sm mb-3">
                <i class="fas fa-check-circle mr-2"></i> {{ session('message') }}
            </div>
        @endif

        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white py-3">
                <h3 class="card-title font-weight-bold">
                    <span class="badge badge-primary px-3 py-2 mr-2">
                        {{ number_format($subscribersCounter) }}
                    </span> 
                    Subscribers
                </h3>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-uppercase text-muted small">
                            <tr>
                                <th class="pl-4">#</th>
                                <th>Email Address</th>
                                <th>Date</th>
                                <th class="text-right pr-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subscribers as $subscriber)
                                <tr wire:key="sub-{{ $subscriber->id }}">
                                    <td class="pl-4 text-muted">{{ $loop->iteration }}</td>
                                    <td class="font-weight-bold">{{ $subscriber->email }}</td>
                                    <td class="small text-muted">{{ $subscriber->created_at->format('M d, Y') }}</td>
                                    <td class="text-right pr-4">
                                        <button type="button" 
                                            class="btn btn-sm btn-outline-danger"
                                            @click="if(confirm('Delete this subscriber?')) { $wire.deleteSubscriber({{ $subscriber->id }}) }">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">No records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-top py-3">
                {{ $subscribers->links() }}
            </div>
        </div>
    </div>
</div>