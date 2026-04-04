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
            
            {{-- Alert Section --}}
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
                                                    <option value="{{ $category->id }}" {{ $service->category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->socialmedia }} — {{ $category->category }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>API Source</label>
                                            <select name="source" class="form-control @error('source') is-invalid @enderror" required>
                                                @foreach($sources as $source)
                                                    <option value="{{ $source->id }}" {{ $source->id == $service->source_id ? 'selected' : '' }}>
                                                        {{ $source->api_source }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea id="summernote" name="description" class="form-control @error('description') is-invalid @enderror">{{ $service->description }}</textarea>
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
                                            <input type="number" name="min_order" value="{{ $service->min_order }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Max Order</label>
                                            <input type="number" name="max_order" value="{{ $service->max_order }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Avg. Completion Time</label>
                                            <input type="text" name="average_completion_time" value="{{ $service->Average_completion_time }}" class="form-control">
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
                                        <input type="text" name="rateper1000" value="{{ $service->rate_per_1000 }}" class="form-control font-weight-bold text-success" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>API Service ID</label>
                                    <input type="text" name="serviceId" value="{{ $service->serviceId }}" class="form-control" placeholder="Manual">
                                </div>

                                <hr>

                                <button type="submit" class="btn btn-warning btn-block btn-lg shadow-sm font-weight-bold">
                                    <i class="fas fa-save mr-2"></i> Update Service
                                </button>
                                <a href="{{ route('admin.service.index') }}" class="btn btn-light btn-block mt-2">Cancel</a>
                            </div>
                        </div>

                        {{-- Technical Attributes Grid --}}
                        <div class="card shadow-sm border-0 mt-3" style="border-radius: 12px;">
                            <div class="card-body py-3">
                                <small class="text-muted text-uppercase font-weight-bold d-block mb-3">Service Attributes</small>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group mb-2">
                                            <label class="small text-muted">Start</label>
                                            <input type="text" name="start" value="{{ $service->start }}" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-2">
                                            <label class="small text-muted">Speed</label>
                                            <input type="text" name="speed" value="{{ $service->speed }}" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-2">
                                            <label class="small text-muted">Quality</label>
                                            <input type="text" name="quality" value="{{ $service->quality }}" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-2">
                                            <label class="small text-muted">Refill</label>
                                            <input type="text" name="refill" value="{{ $service->refill }}" class="form-control form-control-sm">
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

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        // Initialize Summernote
        $('#summernote').summernote({
            height: 200,
            placeholder: 'Edit service description...',
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });
    });
</script>
@endsection