@extends('layouts.app')
@section('content')
<div class="hero">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-5">
          <div class="intro-excerpt">
            <h1>
              Smart Electronics Eqauls..
              <span clsas="d-block">To Smart Life !</span>
            </h1>
            <p class="mb-4">
              Get the best deals in town with seemless prices on all your
              favorite gadgets and electronics.".
            </p>
            <p>
              <a href="./services.html" class="btn btn-secondary me-2">Order Now</a
              ><a href="./services.html" class="btn btn-white-outline">Explore</a>
            </p>
          </div>
        </div>
        <div class="col-lg-7">
          <!-- <div class="hero-img-wrap"> -->
            <img src="{{ asset('front/images/phone light.jpg') }}" id="IMAGES1" class="img-fluid" />
            <div class="division">
            <fieldset>
            <p style="margin-left: 11.5rem; font-size: 15px; margin-top: 5px; color: Blue;">EQUALS SMARTLIFE</p>
          </fieldset>
        </div>
          </div>  
        </div>
      </div>
    </div>
  </div>
  <!-- End Hero Section -->

  <!-- Start Product Section -->
  <div class="product-section">
    <div class="container">
      <div class="row">
        <!-- Start Column 1 -->
        <div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
          <h2 class="mb-4 section-title">
            Crafted with excellent Electronics.
          </h2>
          <p class="mb-4">
            From smart home items to the latest smartphones and accessories,
            we have something for everyone.
          </p>
          
          <p><a href="./services.html" class="btn">Explore</a></p>
        </div>
        <!-- End Column 1 -->
<!-- <p><b>Micro Phones Section</b></p> -->


        <!-- Start Column 2 -->
        <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
          <a class="product-item" href="#">
            <img
              src="{{ asset('front/images/Product 2.jpeg') }}"
              class="img-fluid product-thumbnail"
            />
            <h3 class="product-title">Wrist Round Haino Tecko smart watch</h3>
            <strong class="product-price">Fr 60,000 </strong>
            <span class="icon-cross">
              <img src="{{ asset('front/images/cross.svg') }}" class="img-fluid" />
            </span>
          </a>

          <!-- <script>
            const productTitleElement = document.getElementsByClassName('.product-title');
            const productTitle = productTitleElement.textContent.trim();
          </script> -->

          <a href="services.html">
            <button onclick="alert('Your being redirected')"  id="whatsapp-button">Order on Whatssap</button>
          </a>

        </div>

        <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
          <a class="product-item" href="#">
            <img
              src="{{ asset('front/images/Product 3.jpeg') }}"
              class="img-fluid product-thumbnail"
            />
            <h3 class="product-title">Double Ring Lights with stick</h3>
            <strong class="product-price">20 000 Frw</strong>

            <span class="icon-cross">
              <img src="{{ asset('front/images/cross.svg') }}" class="img-fluid" />
            </span>
          </a>
          <a href="services.html">
            <button onclick="alert('you are being redirected')"  id="whatsapp-button">Order on Whatssap</button>
          </a>
        </div>

        <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
          <a class="product-item" href="#">
            <img
              src="{{ asset('front/images/Product 4.jpeg') }}"
              class="img-fluid product-thumbnail"
            />
            <h3 class="product-title">Dual Omni Directional Lavalier Mic</h3>
            <strong class="product-price">35 000Frw</strong>

            <span class="icon-cross">
              <img src="{{ asset('front/images/cross.svg') }}" class="img-fluid" />
            </span>
          </a>
          <a href="./services.html">
            <button onclick="alert('You are being redirected')"  id="whatsapp-button">Order on Whatssap</button>
          </a>
          
      

        </div>

        <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
          <a class="product-item" href="#">
            <img
              src="{{ asset('front/images/Product 13.jpeg') }}"
              class="img-fluid product-thumbnail"
            />
            <h3 class="product-title">Haino Tecko Smart Watch</h3>
            <strong class="product-price">Fr 65 000</strong>

            <span class="icon-cross">
              <img src="{{ asset('front/images/cross.svg') }}" class="img-fluid" />
            </span>
          </a>
          <a href="./services.html">
            <button onclick="alert('You are being redirected')"  id="whatsapp-button">Order on Whatssap</button>
          </a>
        </div>

        <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
          <a class="product-item" href="#">
            <img
              src="{{ asset('front/images/Product 9.jpeg') }}"
              class="img-fluid product-thumbnail"
            />
            <h3 class="product-title">Haino tecko Smart Watch</h3>
            <strong class="product-price">Fr 60 000</strong>

            <span class="icon-cross">
              <img src="{{ asset('front/images/cross.svg') }}" class="img-fluid" />
            </span>
          </a>
          <a href="./services.html">
            <button onclick="alert('You are being redirected')"  id="whatsapp-button">Order on Whatssap</button>
          </a>
        </div>

        <!-- End Column 2 -->

        <!-- Start Column 3 -->
        <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
          <a class="product-item" href="#">
            <img
              src="{{ asset('front/images/Product 13.jpeg') }}"
              class="img-fluid product-thumbnail"/>
            <h3 class="product-title">Haino Tecko Smart Watch</h3>
            <strong class="product-price">Fr 60 000</strong>
            <span class="icon-cross">
            </span>
          </a>

          <a href="./services.html">
          <button onclick="alert('You are being redirected')"  id="whatsapp-button">Order on Whatssap</button>
        </a>

        </div>
        <!-- End Column 3 -->

        <!-- Start Column 4 -->
        <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
          <a class="product-item" href="#l">
            <img
              src="{{ asset('front/images/Product 2.jpeg') }}"
              class="img-fluid product-thumbnail"
            />
            <h3 class="product-title">Haino Tecko Smart Watch </h3>
            <strong class="product-price">Fr 44 500</strong>

            <span class="icon-cross">
              <img src="{{ asset('front/images/cross.svg') }}" class="img-fluid" />
            </span>
          </a>
          <a href="./services.html">
            <button onclick="alert('you are beign redirected')"  id="whatsapp-button">Order on Whatssap</button>
          </a>
        </div>
        <!-- End Column 4 -->
      </div>
    </div>
  </div>
  <!-- End Product Section -->
  <!-- Start Why Choose Us Section -->
   <div class="why-choose-section">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-6">
          <h2 class="section-title">Why Choose Us</h2>
          <!-- <p>
            "We offer only the highest-quality products from trusted brands,
            ensuring reliability and long-term performance, with special
            offers and discounts available regularly."
          </p> -->

          <div class="row my-5">
            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="{{ asset('front/images/truck.svg') }}" alt="Image" class="imf-fluid" />
                </div>
                <h3>Fast &amp; Free Shipping</h3>
                <p>
                  "We ensure your orders are delivered quickly, so you don’t
                  have to wait long for your favorite gadgets."
                </p>
              </div>
            </div>

            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="{{ asset('front/images/bag.svg') }}" alt="Image" class="imf-fluid" />
                </div>
                <h3>Easy to Shop</h3>
                <p>
                  "Discover unique and hard-to-find electronics that you won’t
                  see anywhere else, with our user-friendly online shopping
                  platform."
                </p>
              </div>
            </div>



            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img
                    src="{{ asset('front/images/support.svg') }}"
                    alt="Image"
                    class="imf-fluid"
                  />
                </div>
                <h3>24/7 Support</h3>
                <p>
                  "We believe in putting you first. Our non-stop support reflects our commitment to your satisfaction."
                </p>
              </div>
            </div>



            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img
                    src="{{ asset('front/images/return.svg') }}"
                    alt="Image"
                    class="imf-fluid"
                  />
                </div>
                <h3>Hassle Free Returns</h3>
                <p>
                  "No extra costs, no hidden fees—our return process is 100% free for all eligible items, within guarranted time frames."
                </p>
              </div>
            </div>


          </div>
        </div>

        <div class="col-lg-5">
          <div class="img-wrap">
            <img
              src="{{ asset('front/images/Product 31.mp4') }}"
              alt="Image" id="IMAGES13"
              class="img-fluid"
            />
          </div>
        </div>

      </div>
    </div>
  </div> 
  <!-- End Why Choose Us Section -->

  

  <!-- Start We Help Section -->
  <div class="we-help-section">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-7 mb-5 mb-lg-0">
          <div class="imgs-grid">
            <div class="grid grid-1">
              <img src="{{ asset('front/images/Product 11.jpeg') }}" alt="Untree.co" />
            </div>
            <div class="grid grid-2">
              <img src="{{ asset('front/images/el5.jpg') }}" alt="Untree.co" />
            </div>
            <div class="grid grid-3" name="Lights">
              <img src="{{ asset('front/images/el6.jpg') }}" alt="Untree.co" />
            </div>
          </div>
        </div>
        <div class="col-lg-5 ps-lg-5">
          <h2 class="section-title mb-4">
              Everything you need, all in one place. We’re here to simplify your life with quality and convenience.
          </h2>
          <p>
              We don’t just sell products—we deliver trust, durability, and performance with every item.
          </p>

       

          <p>
            <a href="services.html" class="btn">Shop Now</a>
        </div>
      </div>
    </div>
  </div>
  <!-- End We Help Section -->

  <!-- Start Popular Product -->
  <div class="popular-product">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
          <div class="product-item-sm d-flex">
            <div class="thumbnail">
              <img src="{{ asset('front/images/el7.jpeg') }}" alt="Image" class="img-fluid" />
            </div>
            <div class="pt-3">
              <h3>Wireless Lavalier Microphone</h3>
              <p>
                Wireless microphones transmit audio wirelessly via RF or Bluetooth, 
              </p>
              <p><a href="#"> <b>20 000Frw</b></a></p>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
          <div class="product-item-sm d-flex">
            <div class="thumbnail">
              <img src="{{ asset('front/images/Product 20.jpeg') }}" id="IMAGES11" alt="Image" class="img-fluid" />
            </div>
            <div class="pt-3">
              <h3>Video Making Kit </h3>
              <p>
                A professional Video Making Kit assisting in video shooting high quality resolution
              </p>
              <p><a href="#"><b>140 000 Frw</b></a></p>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
          <div class="product-item-sm d-flex">
            <div class="thumbnail">
              <img src="{{ asset('front/images/Product 15.jpeg') }}" alt="Image" id="IMAGES11" class="img-fluid" />
            </div>
            <div class="pt-3">
              <h3>Phone Lights</h3>
              <p>
                The Phone Light here to lighten your selfie videos and highlighting your images
              </p>
              <p><a href="#"><b>10 000 frw</b></a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Popular Product -->

  
  <!-- Start Blog Section -->
  <div class="blog-section">
    <div class="container">
      <div class="row mb-5">
        <div class="col-md-6">
          <h2 class="section-title">Recent Blogs</h2>
        </div>
        <div class="col-md-6 text-start text-md-end">
          <a href="services.html" class="more">View All Product</a>
        </div>
      </div>

      

      <div class="row">
        <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
          <div class="post-entry">
            <a href="#" class="post-thumbnail"
              ><img src="{{ asset('front/images/Product 38.jpeg') }}" alt="Image" id="IMAGES12" class="img-fluid"
            /></a>
            <div class="post-content-entry">
              <h3><a href="#">Best legacy of luxury watces displaying the quality <strong>Hanino Teck Smart Wacth</strong> </a></h3>
              <div class="meta">
                <span>by <a href="#"><b>60 000 Frw</b></a></span>
                <!-- <span>on <a href="#">Dec 19, 2021</a></span> -->
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
          <div class="post-entry">
            <a href="#" class="post-thumbnail"
              ><img src="{{ asset('front/images/phone light.jpg') }}" alt="Image" class="img-fluid"
            /></a>
            <div class="post-content-entry">
              <h3><a href="#">Phone stands with lights</a></h3>
              <div class="meta">
                <span>by <a href="#"><b>20 000 Frw </b></a></span>
                <!-- <span>on <a href="#">Dec 15, 2021</a></span> -->
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
          <div class="post-entry">
            <a href="#" class="post-thumbnail"
              ><img src="{{ asset('front/images/Product 36.jpeg') }}" alt="Image" id="IMAGES12" class="img-fluid"
            /></a>
            <div class="post-content-entry">
              <h3><a href="#">Wireless Lavalier Microphone <span>/..best Audio Quality</span></a></h3>
              <div class="meta">
                <span>by <a href="#"><b>40 000 Frw</b></a></span>
                <!-- <span>on <a href="#">Dec 12, 2021</a></span> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Blog Section -->
@endsection