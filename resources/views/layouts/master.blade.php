<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Turbo Tune</title>
        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico')}}" />
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ asset('dashboard/css/bootstrap.min.css')}}">
        <!-- Typography CSS -->
        <link rel="stylesheet" href="{{ asset('dashboard/css/typography.css')}}">
        <!-- Style CSS -->
        <link rel="stylesheet" href="{{ asset('dashboard/css/style.css')}}">
        <!-- Responsive CSS -->
        <link rel="stylesheet" href="{{ asset('dashboard/css/responsive.css')}}">
        <!-- Full calendar -->
        <link href='{{ asset('dashboard/fullcalendar/core/main.css')}}' rel='stylesheet' />
        <link href='{{ asset('dashboard/fullcalendar/daygrid/main.css')}}' rel='stylesheet' />
        <link href='{{ asset('dashboard/fullcalendar/timegrid/main.css')}}' rel='stylesheet' />
        <link href='{{ asset('dashboard/fullcalendar/list/main.css')}}' rel='stylesheet' />

        <link rel="stylesheet" href="{{ asset('dashboard/css/flatpickr.min.css')}}">

     </head>
<body >
    <div class="wrapper">
        <x-dashboard.sidebar/>
        <x-dashboard.navbar/>
        {{-- <main class="main-content"> --}}
            @yield('content')
            <x-dashboard.footer/>
        {{-- </main> --}}

    </div>
@livewireScripts
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
      <!-- lottie JavaScript -->
      <script src="{{ asset('dashboard/js/lottie.js')}}"></script>
      <!-- am core JavaScript -->
      <script src="{{ asset('dashboard/js/core.js')}}"></script>
      <!-- am charts JavaScript -->
      <script src="{{ asset('dashboard/js/charts.js')}}"></script>
      <!-- am animated JavaScript -->
      <script src="{{ asset('dashboard/js/animated.js')}}"></script>
      <!-- am kelly JavaScript -->
      <script src="{{ asset('dashboard/js/kelly.js')}}"></script>
      <!-- am maps JavaScript -->
      <script src="{{ asset('dashboard/js/maps.js')}}"></script>
      <!-- am worldLow JavaScript -->
      <script src="{{ asset('dashboard/js/worldLow.js')}}"></script>
      <!-- Raphael-min JavaScript -->
      <script src="{{ asset('dashboard/js/raphael-min.js')}}"></script>
      <!-- Morris JavaScript -->
      <script src="{{ asset('dashboard/js/morris.js')}}"></script>
      <!-- Morris min JavaScript -->
      <script src="{{ asset('dashboard/js/morris.min.js')}}"></script>
      <!-- Flatpicker Js -->
      <script src="{{ asset('dashboard/js/flatpickr.js')}}"></script>
      <!-- Style Customizer -->
      <script src="{{ asset('dashboard/js/style-customizer.js')}}"></script>
      <!-- Chart Custom JavaScript -->
      <script src="{{ asset('dashboard/js/chart-custom.js')}}"></script>
      <!-- Custom JavaScript -->
      <script src="{{ asset('dashboard/js/custom.js')}}"></script>
   </body>
</html>
