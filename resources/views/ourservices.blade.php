@extends('user dashboard.dashboard')
@section('title', 'Vibe and burn | Add fund')
@section('content')

<div class="nk-content-body">
   <div class="nk-block-head nk-block-head-sm">
       <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Our Services</h3>
        </div>
           
           <!-- .nk-block-head-content -->
           <div class="nk-block-head-content d-none">
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
  <livewire:services-component />
</div>

 <!-- The Modal -->
 <div class="modal fade" id="details">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Modal Heading</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          Modal body..
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
                       
               @endsection