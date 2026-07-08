@extends('admin.layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ isset($update) ? 'Edit Update' : 'Create New Update' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.updates.index') }}">Updates</a></li>
                        <li class="breadcrumb-item active">{{ isset($update) ? 'Edit' : 'Create' }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-7 col-md-9 col-sm-12 col-12">

                    {{-- Alert Notifications --}}
                    @if (Session::has('updateSuccess'))
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            <strong><i class="fas fa-check-circle mr-1"></i> Success!</strong> {{ Session::get('updateSuccess') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @elseif(Session::has('updateFail'))
                        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                            <strong><i class="fas fa-exclamation-triangle mr-1"></i> Failed:</strong> {{ Session::get('updateFail') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- Dynamic Card Wrapper --}}
                    <div class="card card-outline {{ isset($update) ? 'card-info' : 'card-primary' }} shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title text-bold">
                                <i class="fas {{ isset($update) ? 'fa-edit text-info' : 'fa-plus-circle text-primary' }} mr-1"></i> 
                                {{ isset($update) ? 'Modify System Announcement' : 'Publish an Announcement' }}
                            </h3>
                        </div>
                        
                        <!-- Dynamic Form Action Setup -->
                        <form method="POST" action="{{ isset($update) ? route('admin.updates.update', $update->id) : route('admin.updates.store') }}">
                            @csrf
                            @if(isset($update))
                                @method('PUT')
                            @endif

                            <div class="card-body">
                                <div class="form-group mb-0">
                                    <label for="vibeupdate" class="font-weight-bold">Message / Notification Text</label>
                                    <textarea 
                                        class="form-control @error('vibeupdate') is-invalid @enderror" 
                                        id="vibeupdate" 
                                        name="vibeupdate" 
                                        rows="5" 
                                        placeholder="Type your system announcement or dashboard update notice here..." 
                                        required>{{ old('vibeupdate', $update->vibeUpdate ?? '') }}</textarea>
                                    
                                    @error('vibeupdate')
                                        <span class="invalid-feedback d-block mt-2" role="alert">
                                            <strong><i class="fas fa-times-circle mr-1"></i> {{ $message }}</strong>
                                        </span>
                                    @enderror  
                                    <small class="form-text text-muted mt-2">
                                        Keep it brief and clean. This message will be displayed to panel users immediately upon posting.
                                    </small>
                                </div>
                            </div>

                            <div class="card-footer bg-light d-flex justify-content-between">
                                <a href="{{ route('admin.updates.index') }}" class="btn btn-default">
                                    <i class="fas fa-arrow-left mr-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn {{ isset($update) ? 'btn-info' : 'btn-primary' }} shadow-sm">
                                    <i class="fas {{ isset($update) ? 'fa-save' : 'fa-paper-plane' }} mr-1"></i> 
                                    {{ isset($update) ? 'Save Changes' : 'Post Announcement' }}
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
@endsection