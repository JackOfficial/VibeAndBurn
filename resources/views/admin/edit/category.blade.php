@extends('admin.layouts.app')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Category</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin">Home</a></li>
              <li class="breadcrumb-item active">Edit Category</li>
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

            @if (Session::has('updateCategorySuccess'))
            <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-check"></i></strong> {{ Session::get('updateCategorySuccess') }} </div>
          @elseif(Session::has('updateCategoryFail'))
          <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('updateCategoryFail') }} </div> 
            @endif

           <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title"><i class="fa fa-edit"></i> Edit Category</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @foreach ($category as $categoryID)
              <form method="POST" action="{{ route('category.update', $categoryID->id) }}" enctype="multipart/form-data">
                @endforeach
                @csrf
                @method('PUT')
                <div class="card-body">

                        <div class="form-group">
                            <label for="socialmedia">Social Media</label>
                            <select class="form-control" name="socialmedia" @error('socialmedia') is-invalid @enderror id="socialmedia" required>
                               @foreach ($socialmedia as $socialmedia)
                                <option value="{{ $socialmedia->id }}">{{ ucfirst($socialmedia->socialmedia) }}</option> 
                                @endforeach
                                  </select>
                                  @error('socialmedia')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                     </span>
                              @enderror 
                        </div>

                        <div class="form-group">
                            <label for="category">Category</label>
                            @foreach ($category as $category)
                            <input type="text" class="form-control @error('category') is-invalid @enderror" value="{{ $category->category }}" id="category" name="category" placeholder="Enter Category" required>
                            @error('category')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                               </span>
                        @enderror     
                            @endforeach
                            
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