@extends('user dashboard.dashboard')
@section('title', 'Vibe and burn | FAQs')
@section('content')
                  <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="content-page wide-md m-auto">
                                    <div class="nk-block-head nk-block-head-lg wide-sm mx-auto">
                                        <div class="nk-block-head-content text-center">
                                            <h2 class="nk-block-title fw-normal">Terms &amp; Policy</h2>
                                            <div class="nk-block-des">
                                                <p class="lead">The following terms and conditions, apply to all users.</p>
                                                <p class="text-soft ff-italic">Last Update: Dec 23, 2021</p>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    <div class="nk-block">
                                        <div class="card card-bordered">
                                            <div class="card-inner card-inner-xl">
                                                <div class="entry">
                                                    <h3>Terms and Conditions</h3>
                                                    {!! $terms->terms !!}
                                                    </div>
                                            </div><!-- .card-inner -->
                                        </div><!-- .card -->
                                    </div><!-- .nk-block -->
                                </div><!-- .content-page -->
                            </div>
                        </div>
                    </div>
                </div>
                            
                   @endsection