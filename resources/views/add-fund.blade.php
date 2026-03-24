@extends('user dashboard.dashboard')
@section('title', 'Vibe and burn | Add Funds')
@section('content')

<div class="nk-content-body">
   <div class="nk-block-head nk-block-head-sm">
       <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Add Funds</h3>
        </div>
           
           <!-- .nk-block-head-content -->
           <div class="nk-block-head-content">
               <div class="toggle-wrap nk-block-tools-toggle">
                   <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                   <div class="toggle-expand-content" data-content="pageMenu">
                       <ul class="nk-block-tools g-3">
                           <li>
                               <div class="drodown">
                                   <a href="#" class="dropdown-toggle btn btn-white btn-dim btn-outline-light" data-toggle="dropdown"><em class="d-none d-sm-inline icon ni ni-calender-date"></em><span><span class="d-none d-md-inline">Last</span> 30 Days</span><em class="dd-indc icon ni ni-chevron-right"></em></a>
                                   <div class="dropdown-menu dropdown-menu-right">
                                       <ul class="link-list-opt no-bdr">
                                           <li><a href="#"><span>Last 30 Days</span></a></li>
                                           <li><a href="#"><span>Last 6 Months</span></a></li>
                                           <li><a href="#"><span>Last 1 Years</span></a></li>
                                       </ul>
                                   </div>
                               </div>
                           </li>
                           <li class="nk-block-tools-opt"><a href="#" class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span></a></li>
                       </ul>
                   </div>
               </div>
           </div><!-- .nk-block-head-content -->
       </div><!-- .nk-block-between -->
   </div><!-- .nk-block-head -->
   <div class="nk-block">
       <div class="row g-gs">
           <div class="col-xxl-6">
            <!-- .row -->
 <div class="alert alert-pro alert-info">
                    <div class="alert-text">
                        <p>Now, When you add $100 or more, you get bonus of 5%! &#128578;</p>
                     </div>
                </div>
                
            @if (Session::has('addFundSuccess'))
<div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px; margin-top: 5px">
  <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> 
<strong><i class="fas fa-check"></i></strong> {{ Session::get('addFundSuccess') }} </div>
@elseif(Session::has('addFundFail'))
<div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px; margin-top: 5px">
<a href="#" class="close" data-dismiss="alert" aria-label="close"></a> 
<strong>FAILED:</strong> {{ Session::get('addFundFail') }} </div> 
@endif

 @if(Session::has('unsupportedCurrency'))
<div class="alert alert-danger alert-dismissible mb-2">
<a href="#" class="close" data-dismiss="alert" aria-label="close"></a> 
<strong>Oop!</strong> {{ Session::get('unsupportedCurrency') }} </div> 
@endif

@foreach ($errors->all() as $error)
    <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px; margin-top: 5px">
        <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> 
        <strong>FAILED:</strong> {{ $error }} </div>
@endforeach

  <livewire:fund.add-funds />
  
           </div><!-- .col -->
        <!-- .col -->
       </div><!-- .row -->
   </div><!-- .nk-block -->
</div>

<!-- The Modal -->
<div class="modal" id="why">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Tips & Hints</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
       We have noticed that some of our clients, fail to wait for the system to respond while loading due to the slow internet connection or other bunch of factors. And such practice might affect your payment process.
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<div class="modal" id="advert">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Did you know?</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
       Now, from $100, you get a bonus of 5%
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
                       
               @endsection