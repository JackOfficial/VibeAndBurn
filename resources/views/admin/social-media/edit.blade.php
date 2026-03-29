@extends('admin.layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-dark">Edit Network</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.socialmedia.index') }}">Social Media</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content" x-data="{ updating: false }">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    
                    @if (Session::has('updateSocialmediaSuccess'))
                        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" 
                             x-transition.out.opacity.duration.1000ms
                             class="alert alert-success border-0 shadow-sm mb-3">
                            <i class="fas fa-check-circle mr-2"></i> {{ Session::get('updateSocialmediaSuccess') }}
                        </div>
                    @elseif(Session::has('updateSocialmediaFail'))
                        <div class="alert alert-danger border-0 shadow-sm mb-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i> {{ Session::get('updateSocialmediaFail') }}
                        </div>
                    @endif

                    <div class="card card-outline card-warning shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h3 class="card-title font-weight-bold text-dark">
                                <i class="fas fa-edit mr-2 text-warning"></i> Modify Platform: <span class="text-warning">{{ $socialmedia->socialmedia }}</span>
                            </h3>
                        </div>
                        
                        <form method="POST" action="{{ route('admin.socialmedia.update', $socialmedia->id) }}" @submit="updating = true">
                            @csrf
                            @method('PUT')
                            <div class="card-body py-4">
                                <div class="form-group">
                                    <label for="socialmedia" class="text-muted small text-uppercase font-weight-bold">Platform Name</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0">
                                                <i class="fas fa-hashtag text-warning"></i>
                                            </span>
                                        </div>
                                        <input type="text" 
                                               class="form-control border-left-0 @error('socialmedia') is-invalid @enderror" 
                                               id="socialmedia" 
                                               name="socialmedia" 
                                               value="{{ old('socialmedia', $socialmedia->socialmedia) }}" 
                                               required 
                                               autofocus>
                                    </div>
                                    @error('socialmedia')
                                        <span class="text-danger small mt-1 d-block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mt-3 p-3 bg-light rounded border">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-info-circle mr-1"></i> 
                                        <strong>Note:</strong> Updating this name will immediately change how it appears to customers on the frontend ordering page.
                                    </small>
                                </div>
                            </div>

                            <div class="card-footer bg-white d-flex justify-content-between py-3">
                                <a href="{{ route('admin.socialmedia.index') }}" class="btn btn-default border">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-warning px-4 shadow-sm font-weight-bold" :disabled="updating">
                                    <span x-show="!updating"><i class="fas fa-sync-alt mr-1"></i> Update Platform</span>
                                    <span x-show="updating" x-cloak><i class="fas fa-circle-notch fa-spin mr-1"></i> Updating...</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="text-center mt-3">
                        <small class="text-muted">Registered on {{ $socialmedia->created_at->format('M d, Y \a\t H:i') }}</small>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<style>
    [x-cloak] { display: none !important; }
    .card-outline.card-warning { border-top: 3px solid #ffc107; }
</style>
@endsection