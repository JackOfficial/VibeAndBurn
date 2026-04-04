@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold"><i class="fas fa-edit text-warning mr-2"></i>Edit Service</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.service.index') }}">Services</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            
            {{-- Global Validation/Session Alerts --}}
            @if ($errors->any())
                <div class="alert alert-danger shadow-sm border-0 alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-exclamation-circle mr-2"></i> 
                    <strong>Whoops!</strong> Please fix the errors highlighted below.
                </div>
            @endif

            @if (Session::has('updateServiceSuccess') || Session::has('updateServiceFail'))
                <div class="alert {{ Session::has('updateServiceSuccess') ? 'alert-success' : 'alert-danger' }} alert-dismissible fade show shadow-sm border-0" role="alert">
                    <i class="fas {{ Session::has('updateServiceSuccess') ? 'fa-check-circle' : 'fa-exclamation-triangle' }} mr-2"></i>
                    {{ Session::get('updateServiceSuccess') ?? Session::get('updateServiceFail') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.service.update', $service->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0" style="border-radius: 12px;">
                            <div class="card-header bg-white py-3">
                                <h3 class="card-title font-weight-bold text-muted">Service Configuration</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Service Name <span class="text-danger">*</span></label>
                                    <input type="text" name="service" value="{{ old('service', $service->service) }}" class="form-control @error('service') is-invalid @enderror" required>
                                    @error('service') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Category <span class="text-danger">*</span></label>
                                            <select name="category" class="form-control select2 @error('category') is-invalid @enderror" required>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category', $service->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->socialmedia->socialmedia ?? 'Global' }} — {{ $category->category }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>API Source</label>
                                            <select name="source" class="form-control @error('source') is-invalid @enderror" required>
                                                @foreach($sources as $source)
                                                    <option value="{{ $source->id }}" {{ old('source', $service->source_id) == $source->id ? 'selected' : '' }}>
                                                        {{ $source->api_source }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('source') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea id="summernote" name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $service->description) }}</textarea>
                                    @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 mt-3" style="border-radius: 12px;">
                            <div class="card-header bg-white">
                                <h3 class="card-title font-weight-bold text-muted">Order Constraints</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Min Order</label>
                                            <input type="number" name="min_order" value="{{ old('min_order', $service->min_order) }}" class="form-control @error('min_order') is-invalid @enderror">
                                            @error('min_order') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Max Order</label>
                                            <input type="number" name="max_order" value="{{ old('max_order', $service->max_order) }}" class="form-control @error('max_order') is-invalid @enderror" required>
                                            @error('max_order') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Avg. Completion Time</label>
                                            <input type="text" name="average_completion_time" value="{{ old('average_completion_time', $service->Average_completion_time) }}" class="form-control @error('average_completion_time') is-invalid @enderror">
                                            @error('average_completion_time') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm border-0" style="border-radius: 12px;">
                            <div class="card-header bg-white">
                                <h3 class="card-title font-weight-bold text-muted">Pricing & Status</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Rate per 1000 ($)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="fas fa-dollar-sign text-success"></i></span>
                                        </div>
                                        <input type="text" name="rateper1000" value="{{ old('rateper1000', $service->rate_per_1000) }}" class="form-control font-weight-bold text-success @error('rateper1000') is-invalid @enderror" required>
                                    </div>
                                    @error('rateper1000') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label>API Service ID</label>
                                    <input type="text" name="serviceId" value="{{ old('serviceId', $service->serviceId) }}" class="form-control @error('serviceId') is-invalid @enderror" placeholder="Manual">
                                    @error('serviceId') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <hr>

                                <button type="submit" class="btn btn-warning btn-block btn-lg shadow-sm font-weight-bold text-dark">
                                    <i class="fas fa-save mr-2"></i> Update Service
                                </button>
                                <a href="{{ route('admin.service.index') }}" class="btn btn-light btn-block mt-2">Cancel</a>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 mt-3" style="border-radius: 12px;">
                            <div class="card-body py-3">
                                <small class="text-muted text-uppercase font-weight-bold d-block mb-3">Service Attributes</small>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group mb-2">
                                            <label class="small text-muted">Start</label>
                                            <input type="text" name="start" value="{{ old('start', $service->start) }}" class="form-control form-control-sm @error('start') is-invalid @enderror">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-2">
                                            <label class="small text-muted">Speed</label>
                                            <input type="text" name="speed" value="{{ old('speed', $service->speed) }}" class="form-control form-control-sm @error('speed') is-invalid @enderror">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-2">
                                            <label class="small text-muted">Quality</label>
                                            <input type="text" name="quality" value="{{ old('quality', $service->quality) }}" class="form-control form-control-sm @error('quality') is-invalid @enderror">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-2">
                                            <label class="small text-muted">Refill</label>
                                            <input type="text" name="refill" value="{{ old('refill', $service->refill) }}" class="form-control form-control-sm @error('refill') is-invalid @enderror">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

@endsection