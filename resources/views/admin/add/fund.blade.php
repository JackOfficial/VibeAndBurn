@extends('admin.layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Fund</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin">Home</a></li>
              <li class="breadcrumb-item active">Add Fund</li>
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

            @if (Session::has('adminAddFundSuccess'))
            <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-check"></i></strong> {{ Session::get('adminAddFundSuccess') }} </div>
          @elseif(Session::has('adminAddFundFail'))
          <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('adminAddFundFail') }} </div> 
            @endif

           <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title"><i class="fa fa-plus"></i> Add Fund</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="POST" action="{{ route('clientOrders.store') }}">
                @csrf
                <div class="card-body">
                        
                        <div class="form-group">
                  <label for="socialmedia">Select User</label>
                  <select class="form-control select2" name="user" @error('user') is-invalid @enderror id="user" required>
                     <option selected="">Select User</option>
                      @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ ucfirst($user->name) }} ({{ $user->email }})</option>
                     @endforeach
                  </select>
                  @error('user')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                     </span>
                              @enderror 
                </div>
                        
                               <div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-12">
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <select class="form-control form-control" name="currency">
                <option value="USD">USD</option>
                <option value="ARS">ARS</option>
                <option value="AUD">AUD</option>
                <option value="BIF">BIF</option>
                <option value="BGN">BGN</option>
                <option value="BRL">BRL</option>
                <option value="BWP">BWP</option>
                <option value="CAD">CAD</option>
                <option value="CFA">CFA</option>
                <option value="CHF">CHF</option>
                <option value="CNY">CNY</option>
                <option value="COP">COP</option>
                <option value="CRC">CRC</option>
                <option value="CZK">CZK</option>
                <option value="DKK">DKK</option>
                <option value="EUR">EUR</option>
                <option value="GBP">GBP</option>
                <option value="GHS">GHS</option>
                <option value="HKD">HKD</option>
                <option value="HUF">HUF</option>
                <option value="ILS">ILS</option>
                <option value="INR">INR</option>
                <option value="JPY">JPY</option>
                <option value="KES">KES</option>
                <option value="MAD">MAD</option>
                <option value="MOP">MOP</option>
                <option value="MUR">MUR</option>
                <option value="MWK">MWK</option>
                <option value="MXN">MXN</option>
                <option value="MYR">MYR</option>
                <option value="NGN">NGN</option>
                <option value="NOK">NOK</option>
                <option value="NZD">NZD</option>
                <option value="PEN">PEN</option>
                <option value="PHP">PHP</option>
                <option value="PLN">PLN</option>
                <option value="RUB">RUB</option>
                <option value="RWF">RWF</option>
                <option value="SAR">SAR</option>
                <option value="SEK">SEK</option>
                <option value="SGD">SGD</option>
                <option value="SLL">SLL</option>
                <option value="THB">THB</option>
                <option value="TRY">TRY</option>
                <option value="TWD">TWD</option>
                <option value="TZS">TZS</option>
                <option value="UGX">UGX</option>
                <option value="VEF">VEF</option>
                <option value="XAF">XAF</option>
                <option value="XOF">XOF</option>
                <option value="ZAR">ZAR</option>
                <option value="ZMK">ZMK</option>
                <option value="ZMW">ZMW</option>
                <option value="ZWD">ZWD</option>

              </select>
        </div>
        <input type="float" name="money" class="form-control form-control @error('money') is-invalid @enderror" placeholder="Enter Amount" />
      </div>
        @error('money')
        <span class="invalid-feedback" role="alert"> search
        <strong>{{ $message }}</strong>
        </span>
        @enderror

</div>
</div> 
                  </div>

                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-warning d-block form-control"><span><i class="fa fa-plus"></i> Add Fund</span></button>
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