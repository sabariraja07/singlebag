<!doctype html>
<html class="no-js" lang="{{ App::getlocale() }}">

@php
Helper::autoload_site_data();
@endphp
<head>
    <base href="{{ url('/') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    {{-- generate seo info --}}
    {!! SEO::generate() !!}
    {!! JsonLdMulti::generate() !!}
    <style type="text/css">
        :root {
            --main-theme-color: {{ Cache::get(domain_info('shop_id') . 'theme_color', '#212060') }};
        }
    </style>
    <style>
        .txt-cart-empty {
            text-align: center !important;
            font-weight: 700;
            font-size: 18px;
            width: 100%;
        }

        @media screen and (max-width:391px) {
            .header-logo {
                width: 160px;
                padding-left: 5px;
            }
        }
        
        #carausel-10-columns .slick-track {
            margin-left: 0 !important;
        }

        .size-filter.list-filter a {
            border-radius: 30px;
            border: 1px solid #e8e8e8;
        }

        .product-extra-link2 a.active {
            background-color: #FDC040;
            color: #fff;
        }

        .product-extra-link2 a.active:hover {
            background: #fff none repeat scroll 0 0;
            color: #333;
        }

        /* .product-cart-wrap .product-img-action-wrap .product-img{
        height: 200px !important;
    } */

        .v-toast__text,
        .v-toast__icon {
            color: #fff;
        }

        [v-cloak] {
            display: none;
        }

        .logo img, .mobile-logo img{
            max-width: 150px !important;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" href="{{ get_shop_favicon(domain_info('shop_id')) }}">
    @stack('css')
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ get_shop_favicon(domain_info('shop_id')) }}">
    <link rel="stylesheet" href="{{ asset('frontend/singlebag/css/font-awesome.min.css') }}" />
    {{ load_header() }}
    <script>
        window.multibag = {
            baseUrl: '{{ url('/') }}',
            rtl: false,
            storeName: '{{ env('APP_NAME') }}',
            loggedIn: '{{ auth()->guard('customer')->check()? 1: 0 }}',
            csrfToken: '{{ csrf_token() }}',
            cart: {!! json_encode(get_cart()) !!},
            wishlist: {!! json_encode(wishlist(), true) !!},
            langs: {!! get_lang_trans() !!},
            currency: {!! json_encode(currency_info(), true) !!},
            theme_color: '{{ Cache::get(domain_info('shop_id') . 'theme_color', '#dc3545') }}'
        };
    </script>
    @include('layouts.partials.analytics')
    <link rel="stylesheet" href="{{ asset('frontend/multibag/css/vendor/vendor.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/multibag/css/plugins/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ mix('css/style.css', 'themes/multibag') }}">
    <link href="{{ asset('frontend/multibag/css/select2.min.css') }}" rel="stylesheet" />
    <style>
        .contact-infor {
            font-size: 15px;
            color: #253D4E;
            margin-bottom: 20px;
        }
        .contact-infor li:not(:last-child) {
            margin-bottom: 10px;
        }
        .contact-infor li img {
            margin-right: 8px;
            max-width: 16px;
        }
        .select2-container--default .select2-selection--single{
            font-size: 13px;
            color: #828495;
            height: 45px;
            background-color: #F6F6F6;
            border: none;
            padding: 2px 10px 2px 20px;
            width: 100%;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 45px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 45px;
            padding-right: 10px;
        }
        /*page loading*/
.preloader {
  background-color: #232363;
  width: 100%;
  height: 100%;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 999999;
  -webkit-transition: .6s;
  transition: .6s;
  margin: 0 auto;
}

.preloader img.jump {
  max-height: 100px;
}

.notification {
  text-decoration: none;
  position: relative;
  display: inline-block;
}

.notification .badge {
  position: absolute;
  top: -10px;
  right: -10px;
  padding: 0px;
  color: white;
  font-size: 12px;
  font-weight: 600;
  line-height: 20px;
  z-index: 2;
  width: 20px;
  height: 20px;
  text-align: center;
  color: #fff;
  border-radius: 50%;
  background-color: #ff4545;
}

.breadcrumb-pm{
    background-color: #f0f0f0;
    padding: 45px 0 45px;
}

    </style>

</head>

<body>
    <!-- Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="text-center">
                    <img src="{{ asset('frontend/multibag/images/page-loading.gif') }}" alt="" />
                </div>
            </div>
        </div>
    </div>
    <div id="multibag" v-cloak class="main-wrapper main-wrapper-3">
        @include('multibag::layouts.header')
        {{--  --}}
        @include('multibag::layouts.breadcrumb')
        @yield('content')
        @include('multibag::layouts.footer')
    </div>
    <script src="{{ asset('frontend/multibag/js/vendor/vendor.js') }}"></script>
    <script src="{{ asset('frontend/multibag/js/plugins/plugins.min.js') }}"></script>
    <script src="{{ asset('frontend/multibag/js/select2.min.js') }}"></script>
    <script src="{{ mix('js/app.js', 'themes/multibag') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('frontend/multibag/js/main.js') }}"></script>@stack('js')
    <!-- Template  JS -->
    @stack('js')
    {{ load_footer() }}

    {{ load_whatsapp() }}

</body>

</html>
