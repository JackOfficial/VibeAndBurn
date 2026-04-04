@extends('admin.layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Advert</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.advert.index') }}">Adverts</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 offset-md-1">

                    {{-- Flash Messages --}}
                    @if (Session::has('advertSuccess'))
                        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-check-circle mr-2"></i> {{ Session::get('advertSuccess') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.advert.update', $ad->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="card card-info card-outline shadow-sm">
                            <div class="card-header bg-white">
                                <h3 class="card-title font-weight-bold">
                                    <i class="fas fa-edit mr-2 text-info"></i> Update Advert #{{ $ad->id }}
                                </h3>
                            </div>

                            <div class="card-body">
                                {{-- Current Image Preview --}}
                                @if($ad->photo)
                                <div class="form-group">
                                    <label class="d-block">Current Banner</label>
                                    <img src="{{ asset('storage/' . $ad->photo) }}" class="img-thumbnail mb-2" style="max-height: 150px;" alt="Current Advert">
                                    <p class="small text-muted">Upload a new photo only if you want to replace this one.</p>
                                </div>
                                @endif

                                {{-- Photo Upload --}}
                                <div class="form-group">
                                    <label for="customFile">Change Photo</label>
                                    <div class="custom-file">
                                        <input type="file" name="photo" class="custom-file-input @error('photo') is-invalid @enderror" id="customFile">
                                        <label class="custom-file-label" for="customFile">Choose new image...</label>
                                    </div>
                                    @error('photo')
                                        <span class="text-danger small"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                {{-- Status Dropdown --}}
                                <div class="form-group mt-3">
                                    <label for="status">Display Status</label>
                                    <select name="status" class="form-control select2 @error('status') is-invalid @enderror" style="width: 100%;">
                                        <option value="0" {{ $ad->status == 0 ? 'selected' : '' }}>Hidden (Draft)</option>
                                        <option value="1" {{ $ad->status == 1 ? 'selected' : '' }}>Active (Live on Site)</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                {{-- Summernote Editor --}}
                                <div class="form-group mt-4">
                                    <label for="summernote">Advert Content <span class="text-danger">*</span></label>
                                    <textarea id="summernote" name="advert" class="form-control @error('advert') is-invalid @enderror" required>{{ old('advert', $ad->advert) }}</textarea>
                                    @error('advert')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="card-footer bg-white d-flex justify-content-between">
                                <a href="{{ route('admin.advert.index') }}" class="btn btn-default">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                                <button type="submit" class="btn btn-info px-4 shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    $(function () {
        // Initialize Summernote
        $('#summernote').summernote({
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });

        // Update file input label
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    });
</script>
@endsection