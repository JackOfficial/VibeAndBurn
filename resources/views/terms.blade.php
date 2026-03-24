@extends('layouts.app')
@section('title', 'Vibe and burn | Terms')
@section('content')

       <div class="container my-5">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6 col-sm-12 col-12 mt-5">
                    <h1>Terms and Conditions</h1>
                  {!! $terms->terms !!}
                </div>
            </div>
            <!-- end row -->
        </div>
    <!-- end faq -->

@endsection