@extends('user dashboard.dashboard')
@section('title', 'Vibe&burn - Ticket')
@section('content')
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="components-preview wide-md mx-auto">
                                   <!-- .nk-block-head -->
                               
                                  <livewire:support-component :tickedId="$tickedId" />
                                </div><!-- .components-preview -->
                            </div>
                        </div>
                   @endsection