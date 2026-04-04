@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold"><i class="fas fa-plus-circle text-success mr-2"></i>Add New Service</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.service.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            @if ($errors->any())
    <div class="alert alert-danger shadow-sm border-0">
        <i class="fas fa-exclamation-circle mr-2"></i> 
        <strong>Whoops!</strong> There are some issues with your input. Please check the fields below.
    </div>
@endif

            <form action="{{ route('admin.service.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0" style="border-radius: 12px;">
                            <div class="card-header bg-white">
                                <h3 class="card-title font-weight-bold text-muted">Service Details</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Service Name <span class="text-danger">*</span></label>
                                    <input type="text" name="service" class="form-control @error('service') is-invalid @enderror" placeholder="e.g., Instagram Followers [Real & Active]" required>
                                    @error('service') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Category <span class="text-danger">*</span></label>
                                            <select name="category_id" class="form-control select2 @error('category_id') is-invalid @enderror" required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">
                                                        {{ $category->socialmedia->socialmedia ?? 'Global' }} — {{ $category->category }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>API Source</label>
                                            <select name="source_id" class="form-control">
                                                @foreach($sources as $source)
                                                    <option value="{{ $source->id }}">{{ $source->api_source }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" id="summernote" class="form-control" rows="4"></textarea>
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
                                            <input type="number" name="min_order" class="form-control" value="10" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Max Order</label>
                                            <input type="number" name="max_order" class="form-control" value="10000" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Average Time</label>
                                            <input type="text" name="Average_completion_time" class="form-control" placeholder="e.g., 2 Hours">
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
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        <input type="number" step="0.0001" name="rate_per_1000" class="form-control font-weight-bold text-success @error('rate_per_1000') is-invalid @enderror" placeholder="0.0000" required>
                                    </div>
                                    @error('rate_per_1000') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label>API Service ID</label>
                                    <input type="text" name="serviceId" class="form-control" placeholder="Leave blank for Manual">
                                </div>

                                <hr>

                                <div class="form-group">
                                    <label class="d-block">Service Status</label>
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" name="status" value="1" class="custom-control-input" id="statusSwitch" checked>
                                        <label class="custom-control-label" for="statusSwitch">Active / Visible</label>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-block btn-lg shadow mt-4">
                                    <i class="fas fa-save mr-2"></i> Create Service
                                </button>
                            </div>
                        </div>

                        {{-- Technical Attributes --}}
                        <div class="card shadow-sm border-0 mt-3" style="border-radius: 12px;">
                            <div class="card-body py-2">
                                <div class="form-group mb-2">
                                    <small class="text-muted text-uppercase font-weight-bold">Attributes</small>
                                    <div class="row mt-2">
                                        <div class="col-6"><input type="text" name="start" class="form-control form-control-sm mb-2" placeholder="Start Time"></div>
                                        <div class="col-6"><input type="text" name="speed" class="form-control form-control-sm mb-2" placeholder="Speed"></div>
                                        <div class="col-6"><input type="text" name="quality" class="form-control form-control-sm mb-2" placeholder="Quality"></div>
                                        <div class="col-6"><input type="text" name="refill" class="form-control form-control-sm mb-2" placeholder="Refill"></div>
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
        // Initialize Select2 for the category searchable dropdown
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        // Initialize Summernote for professional description editing
        $('#summernote').summernote({
            height: 150,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough']],
                ['para', ['ul', 'ol']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });
    });
</script>
@endsection