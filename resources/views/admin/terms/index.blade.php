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

            @if (Session::has('updateTermsSuccess'))
            <div class="alert alert-success alert-dismissible mb-2">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          {{ Session::get('updateTermsSuccess') }} 
          </div>
          @elseif (Session::has('deleteTermsSuccess'))
            <div class="alert alert-success alert-dismissible mb-2">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          {{ Session::get('deleteTermsSuccess') }} 
          </div>
          @elseif(Session::has('deleteTermsFail'))
          <div class="alert alert-danger alert-dismissible mb-2">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('deleteTermsFail') }} </div> 
            @endif
            
            <div>
                @if($terms->terms == null)
               <h5>There's no terms and conditions at the moment</h5>
               @else
               {!! $terms->terms !!}
               @endif
            </div>
            <hr>
             <div>
                 <form method="POST" action="{{ route('terms.destroy', $terms->id) }}">
                     @csrf
                     @method('DELETE')
                     <a href="{{ route('terms.edit', $terms->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                     <button type="submit" class="btn btn-danger btn-sm d-none"><i class="fa fa-times"></i> Delete</button>
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