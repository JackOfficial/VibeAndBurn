@extends('admin.layouts.app')
@section('content')

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Advertisement</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin">Home</a></li>
              <li class="breadcrumb-item active">Advertisement</li>
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

            @if (Session::has('advertisementSuccess'))
            <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-check"></i></strong> {{ Session::get('advertisementSuccess') }} </div>
          @elseif(Session::has('advertisementFail'))
          <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('advertisementFail') }} </div> 
            @endif
            
             <a href="{{ route('advert.create') }}" class="btn btn-primary btn-sm mb-2"><i class="fa fa-plus"></i> Add Advert</a>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ $adverts->count() }} Adverts</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Adverts</th>
                    <th>Status</th>
                    <th>Created_at</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                 
                   @foreach ($adverts as $ad)
                   <tr> 
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($ad->photo != NULL)
                        <img src="{{ asset('storage/' . $ad->photo) }}" style="width: 100px;" alt="Advert" />
                        @else
                        No Photo
                        @endif
                    </td>
                    <td>{!! $ad->advert !!}</td>
                    <td>
                        @if($ad->status == 1)
                        <span class="badge badge-success">Active</span>
                        @else
                        <span class="badge badge-light">Hidden</span>
                        @endif
                        </td>
                    <td>{{ $ad->created_at }}</td>
                    <td>
                        <form method="POST" action="{{ route('advert.destroy', $ad->id) }}">
                          @csrf
                            @method('DELETE')
                            <a class="btn btn-success btn-sm" href="{{ route('advert.edit', $ad->id) }}"><i class="fa fa-edit"></i> Edit</a>&nbsp;
                           <button type="submit" class="btn btn-danger btn-sm"><span><i class="fa fa-trash"></i></span> Delete</button>
                        </form>
                        </td>
                  </tr>   
                   @endforeach 
                  
              
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Adverts</th>
                    <th>Status</th>
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