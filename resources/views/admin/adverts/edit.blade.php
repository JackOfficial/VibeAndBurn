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

                    {{-- Alpine.js Logic: x-data handles the preview state --}}
                    <div x-data="imageViewer('{{ $ad->photo ? asset('storage/' . $ad->photo) : '' }}')">
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
                                    {{-- Image Preview Area --}}
                                    <div class="form-group">
                                        <label class="d-block">Banner Preview</label>
                                        
                                        {{-- Show current/new image --}}
                                        <template x-if="imageUrl">
                                            <img :src="imageUrl" class="img-thumbnail mb-2" style="max-height: 200px; display: block;" alt="Advert Preview">
                                        </template>

                                        {{-- Show placeholder if no image exists --}}
                                        <template x-if="!imageUrl">
                                            <div class="bg-light border rounded d-flex align-items-center justify-content-center mb-2" style="height: 150px; width: 250px;">
                                                <span class="text-muted">No Image Selected</span>
                                            </div>
                                        </template>

                                        <p class="small text-muted" x-show="!fileSelected">Showing current banner. Upload a new one to change it.</p>
                                        <p class="small text-success font-weight-bold" x-show="fileSelected" x-cloak>
                                            <i class="fas fa-sync"></i> Previewing new file...
                                        </p>
                                    </div>

                                    {{-- Photo Upload --}}
                                    <div class="form-group">
                                        <label for="customFile">Upload New Photo</label>
                                        <div class="custom-file">
                                            {{-- @change triggers Alpine to update the preview --}}
                                            <input type="file" 
                                                   name="photo" 
                                                   class="custom-file-input @error('photo') is-invalid @enderror" 
                                                   id="customFile"
                                                   @change="fileChosen">
                                            <label class="custom-file-label" for="customFile" x-text="fileName || 'Choose new image...'">Choose new image...</label>
                                        </div>
                                        @error('photo')
                                            <span class="text-danger small"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    {{-- Status Dropdown --}}
                                    <div class="form-group mt-3">
                                        <label for="status">Display Status</label>
                                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                                            <option value="0" {{ $ad->status == 0 ? 'selected' : '' }}>Hidden (Draft)</option>
                                            <option value="1" {{ $ad->status == 1 ? 'selected' : '' }}>Active (Live on Site)</option>
                                        </select>
                                    </div>

                                    {{-- Summernote Editor --}}
                                    <div class="form-group mt-4">
                                        <label for="summernote">Advert Content <span class="text-danger">*</span></label>
                                        <textarea id="summernote" name="advert" class="form-control @error('advert') is-invalid @enderror" required>{{ old('advert', $ad->advert) }}</textarea>
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
                    </div> {{-- End x-data --}}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    // Alpine.js Component for Image Preview
    function imageViewer(initialUrl) {
        return {
            imageUrl: initialUrl,
            fileName: '',
            fileSelected: false,
            
            fileChosen(event) {
                this.fileToDataUrl(event, src => {
                    this.imageUrl = src;
                    this.fileSelected = true;
                });
                this.fileName = event.target.files[0].name;
            },

            fileToDataUrl(event, callback) {
                if (!event.target.files.length) return;

                let file = event.target.files[0],
                    reader = new FileReader();

                reader.readAsDataURL(file);
                reader.onload = e => callback(e.target.result);
            }
        }
    }

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
    });
</script>

{{-- Ensure x-cloak style exists to prevent flickering --}}
<style>
    [x-cloak] { display: none !important; }
</style>
@endsection