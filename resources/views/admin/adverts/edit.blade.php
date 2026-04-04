@extends('admin.layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Advert</h1>
                </div>
                <div class="col-sm-6 text-right">
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

                    @if (Session::has('advertSuccess'))
                        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-check-circle mr-2"></i> {{ Session::get('advertSuccess') }}
                        </div>
                    @endif

                    {{-- Alpine Component Container --}}
                    <div x-data="imageViewer('{{ $ad->photo ? asset('storage/' . $ad->photo) : '' }}')" x-cloak>
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
                                    {{-- Live Preview Section --}}
                                    <div class="form-group">
                                        <label class="d-block">Banner Preview</label>
                                        
                                        <template x-if="imageUrl">
                                            <div class="mb-2">
                                                <img :src="imageUrl" class="img-thumbnail shadow-sm" style="max-height: 200px; display: block;" alt="Advert Preview">
                                                <span class="badge mt-2" :class="fileSelected ? 'badge-success' : 'badge-info'">
                                                    <i class="fas" :class="fileSelected ? 'fa-sync-alt' : 'fa-image'"></i>
                                                    <span x-text="fileSelected ? 'New Image Selected' : 'Current Live Banner'"></span>
                                                </span>
                                            </div>
                                        </template>

                                        <template x-if="!imageUrl">
                                            <div class="bg-light border rounded d-flex align-items-center justify-content-center mb-2" style="height: 150px; width: 250px; border-style: dashed !important;">
                                                <span class="text-muted">No Image Available</span>
                                            </div>
                                        </template>
                                    </div>

                                    {{-- Photo Upload --}}
                                    <div class="form-group">
                                        <label for="customFile">Change Image</label>
                                        <div class="custom-file">
                                            <input type="file" 
                                                   name="photo" 
                                                   class="custom-file-input @error('photo') is-invalid @enderror" 
                                                   id="customFile"
                                                   @change="fileChosen">
                                            <label class="custom-file-label" for="customFile" x-text="fileName || 'Choose new file...'">Choose new file...</label>
                                        </div>
                                        @error('photo')
                                            <span class="text-danger small font-weight-bold">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Status Dropdown --}}
                                    <div class="form-group mt-3">
                                        <label for="status">Display Status</label>
                                        <select name="status" class="form-control select2 @error('status') is-invalid @enderror">
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
                    </div>
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
    /** * GLOBAL SCOPE: Define this outside of jQuery $(function) 
     * so Alpine.js (which uses defer) can find it immediately.
     */
    function imageViewer(initialUrl) {
        return {
            imageUrl: initialUrl,
            fileName: '',
            fileSelected: false,
            
            fileChosen(event) {
                const files = event.target.files;
                if (!files.length) return;

                this.fileName = files[0].name;
                
                const reader = new FileReader();
                reader.onload = e => {
                    this.imageUrl = e.target.result;
                    this.fileSelected = true;
                };
                reader.readAsDataURL(files[0]);
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
@endsection