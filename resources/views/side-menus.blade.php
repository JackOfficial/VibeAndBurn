<div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
                                                <div class="card-inner-group" data-simplebar>
                                                    <div class="card-inner">
                                                        <div class="">
                                                            @if(Auth::user()->avatar == null)
                                                            <div class="user-avatar bg-primary d-block mx-auto">
                                                                <span>AB</span>
                                                            </div>
                                                            @else
                                                            <img src="{{ Auth::user()->avatar }}" class="img rounded-circle d-block mx-auto" style="width: 100px; height: auto;" />
                                                            @endif
                                                            <div class="text-center my-2 text-lead">{{ Auth::user()->name }}</div>
                                                        </div><!-- .user-card -->
                                                    </div><!-- .card-inner -->
                                                    <div class="card-inner p-0">
                                                        <ul class="link-list-menu">
                                                            <li><a class="active" href="#"><em class="icon ni ni-user-fill-c"></em><span>Personal Infomation</span></a></li>
                                                            <li><a href="#"><em class="icon ni ni-lock-alt-fill"></em><span>Security Settings</span></a></li>
                                                            <li><a href="#"><em class="icon ni ni-shield-star-fill"></em><span>Password Change</span></a></li>
                                                        </ul>
                                                    </div><!-- .card-inner -->
                                                </div><!-- .card-inner-group -->
                                            </div><!-- card-aside -->