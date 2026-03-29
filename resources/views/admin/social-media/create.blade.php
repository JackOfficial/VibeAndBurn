@extends('admin.layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-dark">Configure Network</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.socialmedia.index') }}">Social Media</a></li>
                        <li class="breadcrumb-item active">Add New</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content" x-data="{ submitting: false }">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    
                    @if (Session::has('addSocialmediaSuccess'))
                        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" 
                             x-transition.out.opacity.duration.1000ms
                             class="alert alert-success border-0 shadow-sm mb-3">
                            <i class="fas fa-check-circle mr-2"></i> {{ Session::get('addSocialmediaSuccess') }}
                        </div>
                    @elseif(Session::has('addsocialmediaFail'))
                        <div class="alert alert-danger border-0 shadow-sm mb-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i> {{ Session::get('addsocialmediaFail') }}
                        </div>
                    @endif

                    <div class="card card-outline card-warning shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h3 class="card-title font-weight-bold text-warning">
                                <i class="fas fa-plus-circle mr-2"></i> Add Social Media Platform
                            </h3>
                        </div>
                        
                        <form method="POST" action="{{ route('socialmedia.store') }}" enctype="multipart/form-data" @submit="submitting = true">
                            @csrf
                            <div class="card-body py-4">
                                <div class="form-group">
                                    <label for="socialmedia" class="text-muted small text-uppercase font-weight-bold">Platform Name</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0"><i class="fas fa-hashtag text-warning"></i></span>
                                        </div>
                                        <input type="text" 
                                               class="form-control border-left-0 @error('socialmedia') is-invalid @enderror" 
                                               id="socialmedia" 
                                               name="socialmedia" 
                                               placeholder="e.g., Facebook, Instagram, TikTok" 
                                               required 
                                               autofocus>
                                    </div>
                                    @error('socialmedia')
                                        <span class="text-danger small mt-1 d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <p class="text-muted x-small mt-2 italic">This name will be visible to users in the service categories.</p>
                                </div>
                            </div>

                            <div class="card-footer bg-light d-flex justify-content-between">
                                <a href="{{ route('admin.socialmedia.index') }}" class="btn btn-link text-muted">
                                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                                </a>
                                <button type="submit" class="btn btn-warning px-4 shadow-sm font-weight-bold" :disabled="submitting">
                                    <span x-show="!submitting"><i class="fa fa-save mr-1"></i> Save Platform</span>
                                    <span x-show="submitting" x-cloak><i class="fas fa-circle-notch fa-spin mr-1"></i> Saving...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    [x-cloak] { display: none !important; }
    .x-small { font-size: 0.8rem; }
</style>
@endsection