@extends('user dashboard.dashboard')
@section('title', 'Vibe and burn | Pay in Rwanda')
@section('content')

  <div class="jumbotron bg-light table-responsive">
      <h4>
          @if(Session::has('chosenCurrency') && Session::get('tether'))
          <p>
      <strong>Send payment to the address below:</strong> 
      <div class="my-2"><b>Tether (TRC20): 
      <div id="copyText">TPxytPHyqpXvGf2b7UmipzK9TRoYWoQrah</div>
      <span type="button" onclick="CopyToClipboard('copyText')" id="res" data-toggle="tooltip" data-placement="top" title="Copy Text" class="ml-2"><i class="fas fa-copy"></i></span></div>
     <input type="text" value="TPxytPHyqpXvGf2b7UmipzK9TRoYWoQrah" id="myCode" class="d-none">
      <small>After transferring money, create a ticket to share with us your email of account and amount paid.</small>
    </p>
          @else
         <p>
      <strong>Send payment to the address below:</strong> 
      <div class="my-2"><b>Tether (TRC20): 
      <div id="copyText">TPxytPHyqpXvGf2b7UmipzK9TRoYWoQrah</div>
      <span type="button" onclick="CopyToClipboard('copyText')" id="res" data-toggle="tooltip" data-placement="top" title="Copy Text" class="ml-2"><i class="fas fa-copy"></i></span></div>
     <input type="text" value="TPxytPHyqpXvGf2b7UmipzK9TRoYWoQrah" id="myCode" class="d-none">
      <small>After transferring money, create a ticket to share with us your email of account and amount paid.</small>
    </p> 
          @endif
     <p>Thank you!</p>
      </h4>
       <div class="text-smaller">Need Support? <a href="contactus/create">Click here.</a></div>
  </div>

<script>
function CopyToClipboard(id)
{
var r = document.createRange();
r.selectNode(document.getElementById(id));
window.getSelection().removeAllRanges();
window.getSelection().addRange(r);
document.execCommand('copy');
alert("Copied!");
window.getSelection().removeAllRanges();
}

//     function copy(){
//         // Get the text field
//   var copyText = document.getElementById("myCode");

//   // Select the text field
//   copyText.select();
//   copyText.setSelectionRange(0, 99999); // For mobile devices

//   // Copy the text inside the text field
//   navigator.clipboard.writeText(copyText.value);
  
//   // Alert the copied text
//   document.getElementById("res").title.innerHTML = "Text Copied!";
//   alert("Text Copied!");
//     }
</script>
@endsection