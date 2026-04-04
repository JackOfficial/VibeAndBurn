@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Edit Category</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.category.index') }}">Categories</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-8 col-sm-12">
                    
                    {{-- Alpine-Powered Alerts --}}
                    @if (Session::has('updateCategorySuccess'))
                        <div x-data="{ show: true }" 
                             x-show="show" 
                             x-init="setTimeout(() => show = false, 5000)"
                             class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                            <strong><i class="fas fa-check-circle mr-1"></i></strong> {{ Session::get('updateCategorySuccess') }}
                            <button type="button" class="close" @click="show = false">&times;</button>
                        </div>
                    @elseif(Session::has('updateCategoryFail'))
                        <div x-data="{ show: true }" 
                             x-show="show" 
                             class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                            <strong><i class="fas fa-exclamation-triangle mr-1"></i></strong> {{ Session::get('updateCategoryFail') }}
                            <button type="button" class="close" @click="show = false">&times;</button>
                        </div>
                    @endif

                    <div class="card shadow-sm border-0" style="border-radius: 12px;">
                        <div class="card-header bg-primary py-3" style="border-radius: 12px 12px 0 0;">
                            <h3 class="card-title font-weight-bold text-white">
                                <i class="fa fa-edit mr-1"></i> Update Category Information
                            </h3>
                        </div>

                        {{-- Removed the @foreach loop from here --}}
                        <form method="POST" action="{{ route('admin.category.update', $category->id) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="socialmedia">Social Media Platform</label>
                                    <select class="form-control @error('socialmedia') is-invalid @enderror" 
                                            name="socialmedia" 
                                            id="socialmedia" 
                                            required>
                                        @foreach ($socialmedia as $platform)
                                            <option value="{{ $platform->id }}" 
                                                {{ (old('socialmedia') ?? $category->social_media_id) == $platform->id ? 'selected' : '' }}>
                                                {{ ucfirst($platform->socialmedia) }}
                                            </option> 
                                        @endforeach
                                    </select>
                                    @error('socialmedia')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror 
                                </div>

                                <div class="form-group">
                                    <label for="category">Category Name</label>
                                    <input type="text" 
                                           class="form-control @error('category') is-invalid @enderror" 
                                           id="category" 
                                           name="category" 
                                           value="{{ old('category') ?? $category->category }}" 
                                           required>
                                    @error('category')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror  
                                </div>
                            </div>

                            <div class="card-footer bg-white py-3">
                                <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                                    <i class="fa fa-sync-alt mr-1"></i> Update Changes
                                </button>
                                <a href="{{ route('admin.category.index') }}" class="btn btn-light px-4 ml-2 border">Back to List</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection