@extends('admin.layouts.app')
@section('content')

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Terms and Conditions</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin">Home</a></li>
              <li class="breadcrumb-item active">Terms and Conditions</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <div class="row">
          <div class="col-12">

            @if (Session::has('termsSubscriberSuccess'))
            <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-check"></i></strong> {{ Session::get('termsSubscriberSuccess') }} </div>
          @elseif(Session::has('updateTermsFail'))
          <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('updateTermsFail') }} </div> 
            @endif
            
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><i class="fa fa-edit"></i> Edit Terms</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="POST" action="{{ route('terms.update', $terms->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">

                        <div class="form-group">
                            <label for="summernote">Write Terms and Conditions</label>
                            <textarea name="terms" rows="4" class="form-control @error('terms') is-invalid @enderror" id="summernote" placeholder="Write terms and conditions" required>{{ $terms->terms}}</textarea>
                            @error('terms')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                               </span>
                        @enderror     
                            
                        </div>

                  </div>

                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary"><span><i class="fa fa-edit"></i> Edit</span></button>
                </div>
              </form>
            </div>
            
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection