<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                {{-- Alert Notifications --}}
                @if (Session::has('updateSuccess'))
                    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                        <strong><i class="fas fa-check-circle mr-1"></i> Success!</strong> {{ Session::get('updateSuccess') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @elseif(Session::has('updateFail'))
                    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                        <strong><i class="fas fa-exclamation-triangle mr-1"></i> Failed:</strong> {{ Session::get('updateFail') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- Main Data Card --}}
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-bold mb-0">
                            <i class="fas fa-bullhorn mr-1 text-muted"></i> 
                            {{ count($myupdates) }} {{ (count($myupdates) <= 1) ? 'Update' : 'Updates' }}
                        </h3>
                        <div>
                            <a href="{{ route('admin.updates.create') }}" class="btn btn-primary btn-sm shadow-sm">
                                <i class="fa fa-plus-circle mr-1"></i> Add New Update
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0 align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 60px" class="text-center">#</th>
                                        <th>Update Message</th>
                                        <th style="width: 120px" class="text-center">Status</th>
                                        <th style="width: 180px">Created At</th>
                                        <th style="width: 180px" class="text-right pr-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($myupdates as $myupdate)
                                        <tr>
                                            <td class="text-center text-muted font-weight-bold">{{ $loop->iteration }}</td>
                                            <td class="text-wrap" style="max-width: 400px;">
                                                {{ $myupdate->vibeUpdate }}
                                            </td>
                                            <td class="text-center align-middle">
                                                @if($myupdate->status == 0)
                                                    <span class="badge badge-pill badge-soft-danger px-3 py-2">Inactive</span>
                                                @else
                                                    <span class="badge badge-pill badge-soft-success px-3 py-2">Active</span>
                                                @endif
                                            </td>
                                            <td class="text-muted text-sm align-middle">
                                                {{ $myupdate->created_at ? $myupdate->created_at->format('M d, Y H:i') : 'N/A' }}
                                            </td>
                                            <td class="align-middle pr-3">
                                                <div class="d-flex justify-content-end align-items-center gap-1">
                                                    <a href="{{ route('admin.updates.edit', $myupdate->id) }}" class="btn btn-outline-info btn-sm mr-1 shadow-sm">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.updates.destroy', $myupdate->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Are you sure you want to permanently delete this record?')" class="btn btn-outline-danger btn-sm shadow-sm">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <i class="fas fa-folder-open fa-2x mb-2 d-block text-gray-300"></i>
                                                NO UPDATES FOUND AT THIS TIME
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>