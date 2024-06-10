<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <base href="{{ url('/') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @php
  $shop = \App\Models\Shop::where('id',current_shop_id())->first();
  @endphp
   <title>{{ config('app.name', '') }}</title>
   <!-- Favicon -->
   <link rel="shortcut icon" type="image/x-icon" href="{{ get_admin_favicon() }}">
   <link rel="icon" type="image/x-icon" href="{{ get_admin_favicon() }}">
  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/frontend/css/bootstrap.min.css') }}">
  <!-- INCLUDE FONTS --> 
  <link rel="stylesheet" href="{{ asset('assets/css/fontawsome/all.min.css') }}">
  <link href="//fonts.googleapis.com/css?family=Nunito:400,600,700,800" rel="stylesheet">
  <!-- CSS Libraries -->
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/font/flaticon.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
  <style>
    .modal-backdrop{
      display: none !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
      color: #212529 !important ;
    }
    .select2.select2-container--default {
      margin-bottom: 20px;
      margin-top: 20px;
    }
    .select2-container--default .select2-selection--multiple {
      padding-right: 20px;
      padding-top: 1px;
    }
    .select2-container--default .select2-selection--multiple::after {
      position: absolute;
      right: 5px;
      top: 42%;
      font-family: 'Font Awesome 5 Free';
      font-weight: 900;
      -webkit-font-smoothing: antialiased;
      display: inline-block;
      font-style: normal;
      font-variant: normal;
      text-rendering: auto;
      line-height: 1;
      content: "\f078";
      font-size: 0.7rem;
    }
    .select2-container .select2-search--inline .select2-search__field{
      vertical-align: top !important;
    }
</style>
  @include('layouts.partials.analytics')
  @stack('style')
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      @include('layouts/partials/header')
      @include('layouts/partials/sidebar')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
         @yield('head')
         <div class="section-body">

          @yield('content')
         </div>
       </section>
     </div>
     @include('components.copyright')
  </div>
</div>
<!-- General JS Scripts -->
<script src="{{ asset('assets/js/jquery-3.5.1.min.js')}}"></script>
<script src="{{ asset('assets/js/popper.min.js')}}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('assets/js/jquery.nicescroll.min.js')}}"></script>
<script src="{{ asset('assets/js/moment.min.js')}}"></script>
<script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
@stack('js')
<script src="{{ asset('assets/js/stisla.js') }}"></script>
<!-- Template JS File -->
<script src="{{ asset('assets/js/scripts.js') }}"></script>
</body>
</html>
