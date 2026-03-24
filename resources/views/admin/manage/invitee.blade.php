@extends('admin.layouts.app')
@section('content')

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ $linkOwner->name }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin">Home</a></li>
              <li class="breadcrumb-item active">Bonuses</li>
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

            @if (Session::has('deleteBonuseSuccess'))
            <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-check"></i></strong> {{ Session::get('deleteBonuseSuccess') }} </div>
          @elseif(Session::has('deleteBonuseFail'))
          <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('deleteBonuseFail') }} </div> 
            @endif

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ $referralsCount }} Entries &nbsp; <span class="bg-danger text-white p-1 px-2 rounded ml-2"> ${{ $referralSum }} Earned</span></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Invitee</th>
                    <th>Email</th>
                    <th>Amount</th>
                    <th>Created_at</th>
                  </tr>
                  </thead>
                  <tbody>
                 
                   @foreach ($referral as $sharedlink)
                   <tr> 
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sharedlink->name }}</td>
                    <td>{{ $sharedlink->email }}</td>
                    <td>{{ $sharedlink->amount}}</td>
                    <td>{{ $sharedlink->created_at }}</td>
                   </tr>   
                   @endforeach 
                  
              
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Invitee</th>
                    <th>Email</th>
                    <th>Amount</th>
                    <th>Created_at</th>
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

                          </div>

@endsection