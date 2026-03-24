@extends('admin.layouts.app')
@section('content')

<script>
   $(document).ready(function(){ 
    var bonus = $('#bonus').val();
        $('#offer').keyup(function(){
            var offer = this.value;
            var res = bonus - offer;
            if(bonus >= offer){
             document.getElementById('remaining').value = res;   
            }
            else{
             alert('offer can not be much more than bonus');
            }
            
        });
   });
</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Offer Bonus</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin">Home</a></li>
              <li class="breadcrumb-item active">Offer Bonus</li>
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

            @if (Session::has('offerBonusSuccess'))
            <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-check"></i></strong> {{ Session::get('offerBonusSuccess') }} </div>
          @elseif(Session::has('offerBonusFail'))
          <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('offerBonusFail') }} </div> 
            @endif

           <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title"><i class="fa fa-plus"></i> Add Category</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @foreach($linkOwner as $linkOwner)
              <form method="POST" action="{{ route('sharedlink.update', $linkOwner->user_id) }}" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="card-body">

                        <div class="form-group">
                            <label for="bonus">Bonus</label>
                            <input type="text" class="form-control @error('bonus') is-invalid @enderror" id="bonus" name="bonus" value="{{ $linkOwner->bonus }}" readonly required>
                            @error('bonus')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                               </span>
                        @enderror  
                        </div>
                        
                        
                        <div class="row">
                           <div class="col-md-6">
                            <div class="form-group">
                            <label for="offer">Offer</label>
                            <input type="text" class="form-control @error('offer') is-invalid @enderror" id="offer" name="offer" placeholder="How much do you want to offer" required>
                            @error('offer')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                               </span>
                        @enderror  
                        </div>   
                           </div>
                           <div class="col-md-6">
                            <div class="form-group">
                            <label for="remaining">Remaining</label>
                            <input type="text" class="form-control @error('remaining') is-invalid @enderror" id="remaining" name="remaining" readonly required>
                            @error('remaining')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                               </span>
                        @enderror  
                        </div>   
                           </div>
                        </div>

                  </div>

                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" id="submitBtn" class="btn btn-warning"><span><i class="fa fa-plus"></i> Add</span></button>
                </div>
              </form>
              @endforeach
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