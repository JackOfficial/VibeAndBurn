@extends('admin.layouts.app')

@section('content')
<div class="content-wrapper">
    {{-- Content Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Advertisements</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.advert.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus-circle"></i> Create New Advert
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Main content --}}
    <section class="content">
        <div class="container-fluid">
            
            {{-- Alerts --}}
            @if (Session::has('advertSuccess'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-check-circle mr-2"></i> {{ Session::get('advertSuccess') }}
                </div>
            @elseif(Session::has('advertFail'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-exclamation-triangle mr-2"></i> {{ Session::get('advertFail') }}
                </div>
            @endif

            <div class="card shadow-sm border-0" style="border-radius: 10px;">
                <div class="card-header bg-white py-3">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-ad mr-2 text-primary"></i> 
                        Total Adverts ({{ $adverts->count() }})
                    </h3>
                </div>

                <div class="card-body">
                    <table id="example1" class="table table-hover table-striped align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50px">#</th>
                                <th style="width: 120px">Preview</th>
                                <th>Content</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th class="text-right" style="width: 180px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($adverts as $ad)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($ad->photo)
                                            <img src="{{ asset('storage/' . $ad->photo) }}" 
                                                 class="img-thumbnail shadow-sm" 
                                                 style="max-width: 100px; height: auto;" 
                                                 alt="Advert">
                                        @else
                                            <div class="text-center p-2 bg-light rounded border">
                                                <i class="fas fa-image text-muted fa-2x"></i><br>
                                                <small class="text-muted">No Photo</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 300px;">
                                            {!! $ad->advert !!}
                                        </div>
                                    </td>
                                    <td>
                                        @if($ad->status == 1)
                                            <span class="badge badge-pill badge-success shadow-sm px-3">Active</span>
                                        @else
                                            <span class="badge badge-pill badge-secondary px-3">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted small">
                                            {{ $ad->created_at->format('M d, Y') }}<br>
                                            {{ $ad->created_at->format('H:i A') }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <a class="btn btn-outline-info btn-sm mr-2" 
                                               href="{{ route('admin.advert.edit', $ad->id) }}" 
                                               title="Edit">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            
                                            <form method="POST" 
                                                  action="{{ route('admin.advert.destroy', $ad->id) }}" 
                                                  onsubmit="return confirm('Are you sure you want to delete this advert? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No advertisements found in the database.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection