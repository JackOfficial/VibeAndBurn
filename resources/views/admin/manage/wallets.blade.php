@extends('admin.layouts.app')
@section('content')

<script>

$(document).ready(function(){ 
    
    $("#currency").click(function(){

currency = $(this).val();
money = $('#money').val();

if (money != '') {

// set endpoint and your API key
endpoint = 'convert';
access_key = 'cbee96985754e4844413ba838ffb7e76';

// define from currency, to currency, and amount
from = currency;
to = 'USD';
amount = money;

// execute the conversion using the "convert" endpoint:
$.ajax({
    url: 'https://api.exchangeratesapi.io/v1/' + endpoint + '?access_key=' + access_key +'&from=' + from + '&to=' + to + '&amount=' + amount,
    dataType: 'jsonp',
    success: function(json) {
        // access the conversion result in json.result
        document.getElementById('amount').value = json.result;
    }
});
 }else{
    document.getElementById('amount').value = "";
 }
                
});    

$('#money').keyup(function(){

currency = $("#currency").val();
money = $(this).val();

if (money != '') {

// set endpoint and your API key
endpoint = 'convert';
access_key = 'cbee96985754e4844413ba838ffb7e76';

// define from currency, to currency, and amount
from = currency;
to = 'USD';
amount = money;

// execute the conversion using the "convert" endpoint:
$.ajax({
    url: 'https://api.exchangeratesapi.io/v1/' + endpoint + '?access_key=' + access_key +'&from=' + from + '&to=' + to + '&amount=' + amount,
    dataType: 'jsonp',
    success: function(json) {
        // access the conversion result in json.result
        document.getElementById('amount').value = json.result;
    }
});
 }else{
    document.getElementById('amount').value = "";
 }
                
});

$('#money').change(function(){

currency = $("#currency").val();
money = $(this).val();

if (money != '') {

// set endpoint and your API key
endpoint = 'convert';
access_key = 'cbee96985754e4844413ba838ffb7e76';

// define from currency, to currency, and amount
from = currency;
to = 'USD';
amount = money;

// execute the conversion using the "convert" endpoint:
$.ajax({
    url: 'https://api.exchangeratesapi.io/v1/' + endpoint + '?access_key=' + access_key +'&from=' + from + '&to=' + to + '&amount=' + amount,
    dataType: 'jsonp',
    success: function(json) {
        // access the conversion result in json.result
        document.getElementById('amount').value = json.result;
    }
});
 }else{
    document.getElementById('amount').value = "";
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
            <h1>Wallets</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin">Home</a></li>
              <li class="breadcrumb-item active">Wallets</li>
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

           <livewire:admin.wallet /> 
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