<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Turbo Tune</title>
     <!-- Favicon -->
     <link rel="shortcut icon" href="{{ asset('dashboard/images/favicon.ico')}}" />
     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="{{ asset('dashboard/css/bootstrap.min.css')}}">
     <!-- Typography CSS -->
     <link rel="stylesheet" href="{{ asset('dashboard/css/typography.css')}}">
     <!-- Style CSS -->
     <link rel="stylesheet" href="{{ asset('dashboard/css/style.css')}}">
     <!-- Responsive CSS -->
     <link rel="stylesheet" href="{{ asset('dashboard/css/responsive.css')}}">
   </head>
   <body>
      <!-- loader Start -->
      <div id="loading">
         <div id="loading-center">
         </div>
      </div>
      <!-- loader END -->
        <!-- Sign in Start -->
        <section class="sign-in-page">
          <div id="container-inside">
              <div class="cube"></div>
              <div class="cube"></div>
              <div class="cube"></div>
              <div class="cube"></div>
              <div class="cube"></div>
          </div>
            <div class="container p-0">
                <div class="row no-gutters height-self-center">
                  <div class="col-sm-12 align-self-center bg-primary rounded">
                    <div class="row m-0">
                      <div class="col-md-5 bg-white sign-in-page-data">
                          <div class="sign-in-from">
                              <h1 class="mb-0 text-center">Sign Up</h1>
                              <p class="text-center text-dark">Enter your email address and password to access admin panel.</p>
                              <x-dashboard.alert />
                              @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                              <form class="mt-4" method="POST" action="{{ route('dashboard.register.store') }}">
                                @csrf
                                  <div class="form-group">
                                      <label for="exampleInputEmail1">Frist Name</label>
                                      <input type="input" name="first_name" required class="form-control mb-0" id="exampleInputEmail1" placeholder="Your Frist Name">
                                  </div>
                                  <div class="form-group">
                                    <label for="exampleInputEmail1">Last Name</label>
                                    <input type="input" name="last_name" required class="form-control mb-0" id="exampleInputEmail1" placeholder="Your Last Name">
                                  </div>
                                  <div class="form-group">
                                      <label for="exampleInputEmail2">Email address</label>
                                      <input type="email" name="email" required class="form-control mb-0" id="exampleInputEmail2" placeholder="Enter Email">
                                  </div>
                                  <div class="form-group">
                                    <label for="exampleInputEmail1">Your Phone Number</label>
                                    <input type="number" name="phone" required class="form-control mb-0" id="exampleInputEmail1" placeholder="After +02">
                                </div>
                                  <div class="form-group">
                                      <label for="exampleInputPassword1">Password</label>
                                      <input type="password" name="password" required class="form-control mb-0" id="exampleInputPassword1" placeholder="Password">
                                  </div>
                                  <div class="d-inline-block w-100">
                                      <div class="custom-control custom-checkbox d-inline-block mt-2 pt-1">
                                          <input type="checkbox" required class="custom-control-input" id="customCheck1">
                                          <label class="custom-control-label" for="customCheck1">I accept <a href="#">Terms and Conditions</a></label>
                                      </div>
                                  </div>
                                  <div class="sign-info text-center">
                                      <button type="submit" class="btn btn-primary d-block w-100 mb-2">Sign Up</button>
                                      <span class="text-dark d-inline-block line-height-2">Already Have Account ? <a href="{{ route('dashboard.login.index') }}">Log In</a></span>
                                  </div>
                              </form>
                          </div>
                      </div>
                      <div class="col-md-7 text-center sign-in-page-image">
                        <div class="sign-in-detail text-white">
                          {{-- <a class="sign-in-logo mb-5" href="#"><img src="{{ asset('dashboard/images/logo-full.png')}}" class="img-fluid" alt="logo"></a> --}}
                            <div class="owl-carousel" data-autoplay="true" data-loop="true" data-nav="false" data-dots="true" data-items="1" data-items-laptop="1" data-items-tab="1" data-items-mobile="1" data-items-mobile-sm="1" data-margin="0">
                                <div class="item">
                                    <img src="{{ asset('dashboard/images/login/1.png')}}" class="img-fluid mb-4" alt="logo">
                                    <h4 class="mb-1 text-white">Find new friends</h4>
                                    <p>It is a long established fact that a reader will be distracted by the readable content.</p>
                                </div>
                                <div class="item">
                                    <img src="{{ asset('dashboard/images/login/1.png')}}" class="img-fluid mb-4" alt="logo">
                                    <h4 class="mb-1 text-white">Connect with the world</h4>
                                    <p>It is a long established fact that a reader will be distracted by the readable content.</p>
                                </div>
                                <div class="item">
                                    <img src="{{ asset('dashboard/images/login/1.png')}}" class="img-fluid mb-4" alt="logo">
                                    <h4 class="mb-1 text-white">Create new events</h4>
                                    <p>It is a long established fact that a reader will be distracted by the readable content.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                  </div>
                </div>
            </div>
        </section>
        <!-- Sign in END -->

      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="{{ asset('dashboard/js/jquery.min.js')}}"></script>
      <script src="{{ asset('dashboard/js/popper.min.js')}}"></script>
      <script src="{{ asset('dashboard/js/bootstrap.min.js')}}"></script>
      <!-- Appear JavaScript -->
      <script src="{{ asset('dashboard/js/jquery.appear.js')}}"></script>
      <!-- Countdown JavaScript -->
      <script src="{{ asset('dashboard/js/countdown.min.js')}}"></script>
      <!-- Counterup JavaScript -->
      <script src="{{ asset('dashboard/js/waypoints.min.js')}}"></script>
      <script src="{{ asset('dashboard/js/jquery.counterup.min.js')}}"></script>
      <!-- Wow JavaScript -->
      <script src="{{ asset('dashboard/js/wow.min.js')}}"></script>
      <!-- Apexcharts JavaScript -->
      <script src="{{ asset('dashboard/js/apexcharts.js')}}"></script>
      <!-- lottie JavaScript -->
      <script src="{{ asset('dashboard/js/lottie.js')}}"></script>
      <!-- Slick JavaScript -->
      <script src="{{ asset('dashboard/js/slick.min.js')}}"></script>
      <!-- Select2 JavaScript -->
      <script src="{{ asset('dashboard/js/select2.min.js')}}"></script>
      <!-- Owl Carousel JavaScript -->
      <script src="{{ asset('dashboard/js/owl.carousel.min.js')}}"></script>
      <!-- Magnific Popup JavaScript -->
      <script src="{{ asset('dashboard/js/jquery.magnific-popup.min.js')}}"></script>
      <!-- Smooth Scrollbar JavaScript -->
      <script src="{{ asset('dashboard/js/smooth-scrollbar.js')}}"></script>
      <!-- Style Customizer -->
      <script src="{{ asset('dashboard/js/style-customizer.js')}}"></script>
      <!-- Chart Custom JavaScript -->
      <script src="{{ asset('dashboard/js/chart-custom.js')}}"></script>
      <!-- Custom JavaScript -->
      <script src="{{ asset('dashboard/js/custom.js')}}"></script>
   </body>
</html>
