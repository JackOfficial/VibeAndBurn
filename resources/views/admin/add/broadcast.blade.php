
@extends('admin.layouts.app')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Broadcast</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin">Home</a></li>
              <li class="breadcrumb-item active">Broadcast</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">

            @if (Session::has('broadcastSuccess'))
            <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-check"></i></strong> {{ Session::get('broadcastSuccess') }} </div>
          @elseif(Session::has('broadcastFail'))
          <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('broadcastFail') }} </div> 
            @endif

              <form method="POST" action="{{route('broadcast.store')}}">
                @csrf
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">Broadcast New Message</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">


                <div class="form-group">
                  <input type="text" class="form-control @error('subject') is-invalid @enderror" value="{{old('subject')}}" name="subject" placeholder="Subject:" required>
                  @error('subject')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                     </span>
                              @enderror 
                </div>
                <div class="form-group">
                    <textarea id="summernote" name="message" value="{{old('message')}}" class="form-control @error('message') is-invalid @enderror" style="height: 300px" required></textarea>
                    @error('message')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                       </span>
                @enderror
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <div class="float-right">
                  <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> Send</button>
                </div>
              </div>
              <!-- /.card-footer -->
            </div>
        </form>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
  @endsection