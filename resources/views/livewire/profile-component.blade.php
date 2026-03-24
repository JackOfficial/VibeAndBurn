<div>
    
    @if (Session::has('updateProfileSuccess'))
<div class="alert alert-success alert-dismissible">
  <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> 
{{ Session::get('updateProfileSuccess') }} </div>
@elseif(Session::has('updateProfileFail'))
<div class="alert alert-danger alert-dismissible">
<a href="#" class="close" data-dismiss="alert" aria-label="close"></a> 
<strong>Oop!</strong> {{ Session::get('updateProfileFail') }} </div> 
@endif

   <div class="nk-block">
                                                    <div class="nk-data data-list">
                                                        <div class="data-item" data-toggle="modal" data-target="#editProfile">
                                                            <div class="data-col">
                                                                <span class="data-label">Full Name</span>
                                                                <span class="data-value">{{ Auth::user()->name }}</span>
                                                            </div>
                                                            <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                                        </div><!-- data-item -->
                                                        <div class="data-item">
                                                            <div class="data-col">
                                                                <span class="data-label">Email</span>
                                                                <span class="data-value">{{ Auth::user()->email }}</span>
                                                            </div>
                                                            <div class="data-col data-col-end"><span class="data-more disable"><em class="icon ni ni-lock-alt"></em></span></div>
                                                        </div><!-- data-item -->
                                                        <div class="data-item" data-toggle="modal" data-target="#editProfile">
                                                            <div class="data-col">
                                                                <span class="data-label">Phone Number</span>
                                                                <span class="data-value text-soft">{{ Auth::user()->phone }}</span>
                                                            </div>
                                                            <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                                        </div><!-- data-item -->
                                                    </div><!-- data-list -->
                                                </div><!-- .nk-block -->
                                                
                                                <div wire:ignore.self class="modal fade" role="dialog" id="editProfile">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-lg">
                    <h5 class="title">Update Profile</h5>
                    <div class="row gy-4 mt-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="full-name">Full Name</label>
                                        <input type="text" class="form-control form-control-lg" wire:model.defer="name" placeholder="Enter Full name">
                                         @error('name')
                            <span class="text-danger" role="alert">
                              <strong>{{ $message }}</strong>
                               </span>
                        @enderror  
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="phone-no">Phone Number</label>
                                        <input type="text" class="form-control form-control-lg" wire:model.defer="phone" placeholder="Phone Number">
                                         @error('phone')
                            <span class="text-danger" role="alert">
                              <strong>{{ $message }}</strong>
                               </span>
                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 my-2">
                                    <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                        <li>
                                            <a href="" disabled class="btn btn-primary btn-lg" role="button" wire:click.prevent="updateProfile">
                                                <div wire:loading wire:loading.target="updateProfile" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></div>
                                                  <span> Update Profile </span>
                                                </a>
                                        </li>
                                        <li>
                                            <a href="#" data-dismiss="modal" class="link link-light">Cancel</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                </div><!-- .modal-body -->
            </div><!-- .modal-content -->
        </div><!-- .modal-dialog -->
    </div><!-- .modal -->
</div>
