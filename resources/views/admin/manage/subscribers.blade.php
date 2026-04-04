@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    {{-- Content Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold"><i class="fas fa-envelope-open-text mr-2 text-primary"></i>Subscribers</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                        <li class="breadcrumb-item active">Subscribers</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- Main content --}}
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    {{-- Session Alerts --}}
                    @if (Session::has('deleteSubscriberSuccess'))
                        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-check-circle mr-2"></i> {{ Session::get('deleteSubscriberSuccess') }}
                        </div>
                    @elseif(Session::has('deleteSubscriberFail'))
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-exclamation-triangle mr-2"></i> {{ Session::get('deleteSubscriberFail') }}
                        </div>
                    @endif

                    <div class="card shadow-sm border-0" style="border-radius: 12px;">
                        <div class="card-header bg-white py-3">
                            <h3 class="card-title font-weight-bold">
                                <span class="badge badge-primary px-3 py-2 mr-2" style="border-radius: 8px;">
                                    {{ number_format($subscribersCounter) }}
                                </span> 
                                Total Registered Emails
                            </h3>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table id="example1" class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-uppercase text-muted small">
                                        <tr>
                                            <th class="pl-4" style="width: 80px;">#</th>
                                            <th>Email Address</th>
                                            <th>Subscription Date</th>
                                            <th class="text-right pr-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($subscribers as $subscriber)
                                        <tr>
                                            <td class="pl-4 font-weight-bold text-muted">
                                                {{ $loop->iteration + ($subscribers->currentPage() - 1) * $subscribers->perPage() }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-soft-info p-2 rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                        <i class="fas fa-user text-info small"></i>
                                                    </div>
                                                    <span class="text-dark font-weight-600">{{ $subscriber->email }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted small">
                                                    <i class="far fa-calendar-alt mr-1"></i> 
                                                    {{ $subscriber->created_at ? $subscriber->created_at->format('M d, Y • h:i A') : 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="text-right pr-4">
                                                <form action="{{ route('admin.subscription.destroy', $subscriber->id) }}" method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger confirm-delete" title="Remove Subscriber">
                                                        <i class="fa fa-trash-alt"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-50">
                                                <p class="text-muted">No active subscribers found.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Pagination Footer --}}
                        @if($subscribers->hasPages())
                        <div class="card-footer bg-white border-top py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted">
                                    Showing {{ $subscribers->firstItem() }} to {{ $subscribers->lastItem() }} of {{ $subscribers->total() }}
                                </span>
                                <div>{{ $subscribers->links() }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection

@push('scripts')
{{-- JavaScript for Delete Confirmation --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.confirm-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This email will be removed from your list permanently.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.closest('.delete-form').submit();
                }
            });
        });
    });
</script>
@endpush