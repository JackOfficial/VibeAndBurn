@extends('user dashboard.dashboard')
@section('title', 'Vibe and burn | Orders')
@section('content')
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="components-preview wide-md mx-auto">
                                   <!-- .nk-block-head -->
                                  <livewire:orders-component />
                                </div><!-- .components-preview -->
                            </div>
                        </div>
                   @endsection