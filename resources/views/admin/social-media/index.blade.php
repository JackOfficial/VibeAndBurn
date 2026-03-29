@extends('admin.layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-dark">Social Media Networks</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.socialmedia.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                        <i class="fas fa-plus-circle mr-1"></i> Add New Network
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid" x-data="{ loading: false }">
            
            <div class="row">
                <div class="col-12">
                    @if (Session::has('deleteSocialmediaSuccess'))
                        <div x-data="{ show: true }" 
                             x-init="setTimeout(() => show = false, 5000)" 
                             x-show="show" 
                             x-transition.out.opacity.duration.1000ms
                             class="alert alert-success border-0 shadow-sm d-flex align-items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <div>{{ Session::get('deleteSocialmediaSuccess') }}</div>
                            <button type="button" class="close ml-auto" @click="show = false">&times;</button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card card-outline card-primary shadow-sm border-0">
                <div class="card-header bg-white">
                    <h3 class="card-title text-muted">
                        <i class="fas fa-list mr-2"></i>
                        Showing {{ $socialmediaCounter }} Platform{{ $socialmediaCounter > 1 ? 's' : '' }}
                    </h3>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light text-uppercase small font-weight-bold">
                                <tr>
                                    <th class="pl-4 border-0">#</th>
                                    <th class="border-0">Platform</th>
                                    <th class="border-0">Added On</th>
                                    <th class="text-right pr-4 border-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($socialmedia as $item)
                                    <tr class="align-middle">
                                        <td class="pl-4 text-muted">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-soft-primary rounded-circle mr-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                                    <i class="fas fa-hashtag text-primary small"></i>
                                                </div>
                                                <span class="font-weight-bold">{{ $item->socialmedia }}</span>
                                            </div>
                                        </td>
                                        <td class="text-muted">{{ $item->created_at->diffForHumans() }}</td>
                                        <td class="text-right pr-4">
                                            <div class="btn-group shadow-sm">
                                                <a href="{{ route('admin.socialmedia.edit', $item->id) }}" class="btn btn-white btn-sm text-success" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <form method="POST" action="{{ route('admin.socialmedia.destroy', $item->id) }}" 
                                                      @submit="if(!confirm('Are you sure?')) { event.preventDefault() } else { loading = true }">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-white btn-sm text-danger" :disabled="loading" title="Delete">
                                                        <i class="fas fa-trash" x-show="!loading"></i>
                                                        <i class="fas fa-spinner fa-spin" x-show="loading" x-cloak></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <p class="text-muted mb-0">No platforms registered yet.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .bg-soft-primary { background-color: rgba(0, 123, 255, 0.1); }
    .btn-white { background: #fff; border: 1px solid #dee2e6; }
    .btn-white:hover { background: #f8f9fa; }
    [x-cloak] { display: none !important; }
</style>
@endsection