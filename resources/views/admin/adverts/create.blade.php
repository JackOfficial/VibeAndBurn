@extends('admin.layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Add New Advert</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                        <li class="breadcrumb-item active">Add Advert</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    
                    {{-- Alpine.js Image Preview Component --}}
                    <div x-data="imageUpload()">
                        <form method="POST" action="{{ route('admin.advert.store') }}" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="card card-primary card-outline shadow-sm">
                                <div class="card-header bg-white">
                                    <h3 class="card-title font-weight-bold text-primary">
                                        <i class="fas fa-plus-circle mr-1"></i> New Advertisement
                                    </h3>
                                </div>

                                <div class="card-body">
                                    {{-- Image Preview Section --}}
                                    <div class="form-group text-center">
                                        <label class="d-block text-left">Advert Poster Preview</label>
                                        
                                        <template x-if="imageUrl">
                                            <div class="position-relative d-inline-block">
                                                <img :src="imageUrl" class="img-thumbnail shadow-sm mb-3" style="max-height: 250px; border: 2px solid #007bff;" alt="Preview">
                                                <button type="button" @click="clearImage" class="btn btn-danger btn-sm position-absolute" style="top: -10px; right: -10px; border-radius: 50%;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </template>

                                        <template x-if="!imageUrl">
                                            <div @click="$refs.fileInput.click()" class="border rounded d-flex flex-column align-items-center justify-content-center mb-3 bg-light" style="height: 200px; cursor: pointer; border-style: dashed !important;">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                                <p class="text-muted mb-0">Click to upload brand poster</p>
                                                <small class="text-secondary">(Black & Red theme recommended)</small>
                                            </div>
                                        </template>
                                    </div>

                                    {{-- Custom File Input --}}
                                    <div class="form-group">
                                        <div class="custom-file">
                                            <input type="file" 
                                                   name="photo" 
                                                   class="custom-file-input @error('photo') is-invalid @enderror" 
                                                   id="photoInput"
                                                   x-ref="fileInput"
                                                   @change="updatePreview">
                                            <label class="custom-file-label" for="photoInput" x-text="fileName || 'Choose image file...'">Choose image file...</label>
                                        </div>
                                        @error('photo')
                                            <span class="text-danger small"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    {{-- Summernote Content --}}
                                    <div class="form-group mt-4">
                                        <label for="summernote" class="font-weight-bold">Advert Text/Details</label>
                                        <textarea id="summernote" 
                                                  name="advert" 
                                                  class="form-control @error('advert') is-invalid @enderror" 
                                                  required>{{ old('advert') }}</textarea>
                                        @error('advert')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="card-footer bg-light">
                                    <div class="float-right">
                                        <a href="{{ route('admin.advert.index') }}" class="btn btn-default mr-2">Cancel</a>
                                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                            <i class="fas fa-paper-plane mr-1"></i> Post Advert
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div> {{-- End x-data --}}

                </div>
            </div>
        </div>
    </section>
</div>

<style>
    [x-cloak] { display: none !important; }
    .custom-file-label::after { content: "Browse"; }
</style>

@endsection

@section('scripts')
<script>
    // Alpine.js logic for file preview
    function imageUpload() {
        return {
            imageUrl: null,
            fileName: null,

            updatePreview(event) {
                const file = event.target.files[0];
                if (!file) return;

                this.fileName = file.name;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imageUrl = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            clearImage() {
                this.imageUrl = null;
                this.fileName = null;
                document.getElementById('photoInput').value = '';
            }
        }
    }

    $(function () {
        $('#summernote').summernote({
            height: 250,
            placeholder: 'Type your advertisement message here...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>
@endsection