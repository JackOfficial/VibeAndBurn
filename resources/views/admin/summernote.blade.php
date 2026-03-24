@extends('admin.layouts.app')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Product</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add Product</li>
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
            <!-- general form elements -->

           <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title"><i class="fa fa-plus"></i> Add Product</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                      <div class="form-group">
                    <label for="product">Category</label>
                    <select class="form-control" name="category" id="Product" required>
                  
                      <option value="options">My Options</option>
                      <option value="options">My Options</option>
                      <option value="options">My Options</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="photo">Photo</label>
                    <input type="file" class="form-control" id="photo" name="photo" placeholder="Upload Photo" required>
                  </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                     <div class="form-group">
                    <label for="product">Product</label>
                    <input type="text" class="form-control" id="product" name="product" placeholder="Enter Product Name ..." required>
                  </div>
                  <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" name="price" id="price" placeholder="Enter Price" required>
                  </div> 
                    </div>
                  </div>
                   
                   <div class="form-group">
                    <label for="description">Description</label>
                    <textarea rows="4" id="summernote" name="description" required>
                Drap & Drop Some Text Here ...
              </textarea>
                  </div>
                </div>

                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" name="submit" class="btn btn-warning">Submit</button>
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