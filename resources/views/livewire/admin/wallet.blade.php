<div>
    @if (Session::has('deleteWalletSuccess'))
            <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-check"></i></strong> {{ Session::get('deleteWalletSuccess') }} </div>
          @elseif(Session::has('deleteWalletFail'))
          <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('deleteWalletFail') }} </div> 
            @endif
            
            @if (isset($feedback))
             <div class="alert alert-success alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
            {{ $feedback }} </div>
            @endif

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ $walletsCounter }} Entries &nbsp; <span class="bg-danger text-white p-1 px-2 rounded ml-2">${{ $walletsTotal }}</span></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Money</th>
                    <th>Created_at</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                 
                   @foreach ($wallets as $wallet)
                   <tr> 
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $wallet->name }}</td>
                    <td>{{ $wallet->email }}</td>
                    <td>${{ $wallet->money}}</td>
                    <td>{{ $wallet->created_at }}</td>
                    <td>
                        <a class="btn btn-success btn-sm" wire:click.prevent="edit({{ $wallet->id }})" data-toggle="modal" data-target="#editWallet"><i class="fa fa-edit"></i> Edit</a>
                            <a class="btn btn-primary btn-sm" href="#"><i class="fa fa-download"></i> Download</a>
                          
                        </td>
                  </tr>   
                   @endforeach 
                  
              
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Money</th>
                    <th>Created_at</th>
                    <th>Actions</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

<!-- The Modal -->
<div wire:ignore.self class="modal fade" id="editWallet">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Fund</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
          <div wire:loading>Loading...</div>
          <div wire:loading.remove>
               <div>Name: {{ $thisWallet->name ?? '' }}</div>
       <div>email: {{ $thisWallet->email ?? '' }}</div>
       <div>Money: ${{ $thisWallet->money ?? '' }}</div> 
       
              <div class="form-group mt-2">
       <div class="input-group">
        <div class="input-group-prepend">
            <select class="form-control" name="currency" wire:model.defer="currency" id="currency" required>
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
        <input type="float" name="money" wire:model.defer="money" class="form-control @error('money') is-invalid @enderror" placeholder="Enter Amount" required>
      </div>
        @error('money')
        <span class="text-danger">
        <strong>{{ $message }}</strong>
        </span>
        @enderror
        </div> 
     
          </div>
     
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
           <button type="button" class="btn btn-primary btn-sm" wire:click="increaseFund"><i class="fa fa-plus"></i> Increase Fund &nbsp; <div wire:loading wire:target="increaseFund" class="spinner-border spinner-border-sm"></div></button>
            <button type="button" class="btn btn-danger btn-sm" wire:click="decreaseFund"><i class="fa fa-minus"></i> Decrease Fund &nbsp; <div wire:loading wire:target="decreaseFund" class="spinner-border spinner-border-sm"></div></button>
      </div>

    </div>
  </div>
</div>
</div>
