@extends('layouts.app')
@section('content')
  <!-- Hero Section -->
  <div class="hero">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-5">
          <div class="intro-excerpt">
            <h1>Our Service at SmartDotComElectronics</h1>
            <p class="mb-4">"From smart home items to the latest smartphones and accessories, we have something for everyone.</p>
            <p><a href="" class="btn btn-secondary me-2">Shop Now</a><a href="#" class="btn btn-white-outline">Explore</a></p>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="hero-img-wrap">
            <img src="{{ asset('front/images/Product 2.jpeg') }}" id="img-fluid1">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Product Section -->
  <div class="why-choose-section">
    <div class="container">
      <div id="product-list" class="row"></div>
    </div>
  </div>

  <!-- Footer -->

  @endsection