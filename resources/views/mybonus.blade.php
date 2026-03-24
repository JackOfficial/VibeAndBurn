@extends('user dashboard.dashboard')
@section('title', 'Vibe and burn | Bonus')
@section('content')
                        <div class="nk-content-inner mb-5">
                            <div class="nk-content-body">
                                <div class="components-preview wide-md mx-auto">
                                   <!-- .nk-block-head -->
                                    <div class="nk-block nk-block-lg">
                                        <div class="nk-block-head">
                                            <div class="nk-block-head-content">
                                                <h4 class="nk-block-title">My Bonus</h4>
                                                <div class="nk-block-des">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if (Session::has('cleamBonusSuccess'))
            <div class="alert alert-success alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong><i class="fas fa-check"></i></strong> {{ Session::get('cleamBonusSuccess') }} </div>
          @elseif(Session::has('cleamBonusFail'))
          <div class="alert alert-danger alert-dismissible mb-2" style="margin: 5px 5px 0px 5px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
          <strong>FAILED:</strong> {{ Session::get('cleamBonusFail') }} </div> 
            @endif
            
                                   <div class="card p-3">
                                     My Bonus: ${{ $mybonus }} 
                                     <hr>
                                     <div class="{{ $mybonus == 0 ? 'd-none' : '' }}">
                                         <p>Click button below to claim your bonus:</p>
                                   <p>
                        <a href="{{ route('cleambonus') }}" class="btn btn-success">Claim</a>   
                                   </p>      
                                     </div>
                                     <p class="mt-2">Keep referring us to your friends and colleagues to earn more bonuses!</p>
                                    </div>
                                </div><!-- .components-preview -->
                            </div>
                        </div>
                        </div>
                   @endsection