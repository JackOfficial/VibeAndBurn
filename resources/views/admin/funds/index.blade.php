@extends('admin.layouts.app')
@section('content')

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Funds</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin">Home</a></li>
              <li class="breadcrumb-item active">Funds</li>
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

            @if (Session::has('deleteFundSuccess'))
            <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-check"></i></strong> {{ Session::get('deleteFundSuccess') }} </div>
          @elseif(Session::has('deleteFundFail'))
          <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('deleteFundFail') }} </div> 
            @endif

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ $fundsCounter }} Entries &nbsp; <span class="bg-danger text-white p-1 px-2 rounded ml-2"> ${{ $fundsTotal }} Earned</span></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Created_at</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                 
                   @foreach ($funds as $fund)
                   <tr> 
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $fund->name }}</td>
                    <td>{{ $fund->email }}</td>
                    <td>{{ $fund->method}}</td>
                    <td>${{ $fund->amount}}</td>
                    <td>{{ $fund->created_at }}</td>
                    <td>
                            <a class="btn btn-success" href="#"><i class="fa fa-download"></i> Download</a>
                          
                        </td>
                  </tr>   
                   @endforeach 
                  
              
                  </tbody>
                  <tfoot>
                  <tr>
                      <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Created_at</th>
                    <th>Actions</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
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