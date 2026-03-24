@extends('admin.layouts.app')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Social Media</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin">Home</a></li>
              <li class="breadcrumb-item active">Edit Social Media</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <!-- general form elements -->

            @if (Session::has('updateSocialmediaSuccess'))
            <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-check"></i></strong> {{ Session::get('updateSocialmediaSuccess') }} </div>
          @elseif(Session::has('updateSocialmediaFail'))
          <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('updateSocialmediaFail') }} </div> 
            @endif

           <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title"><i class="fa fa-edit"></i> Edit Social Media</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="POST" action="{{ route('socialmedia.update', $socialmedia->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">

                        <div class="form-group">
                            <label for="socialmedia">Social Media</label>
                            <input type="text" class="form-control @error('socialmedia') is-invalid @enderror" id="socialmedia" value="{{ $socialmedia->socialmedia }}" name="socialmedia" placeholder="Enter a Social Media Platform" required>
                            @error('socialmedia')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                               </span>
                        @enderror  
                        </div>
                  </div>

                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-warning"><span><i class="fa fa-edit"></i> Edit</span></button>
                </div>
              </form>
            </div>
            <!-- /.card -->

          </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection