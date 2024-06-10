<!DOCTYPE html>
<html class="no-js" lang="{{ App::getlocale() }}">

@php
Helper::autoload_site_data();
@endphp

<head>
    <base href="{{ url('/') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- generate seo info --}}
    {!! SEO::generate() !!}
    {!! JsonLdMulti::generate() !!}
    <style type="text/css">
        :root {
            --main-theme-color: {{ Cache::get(domain_info('shop_id') . 'theme_color', '#212060') }};
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" href="{{ get_shop_favicon(domain_info('shop_id')) }}">
    @stack('css')
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/fashionbag/css/vendor/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/fashionbag/css/vendor/pe-icon-7-stroke.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/fashionbag/css/vendor.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('frontend/fashionbag/css/plugins.min.css') }}"> --}}
    
    <link rel="stylesheet" href="{{ asset('frontend/fashionbag/css/plugins/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/fashionbag/css/plugins/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/fashionbag/css/plugins/jquery-ui.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/fashionbag/css/plugins/lightgallery.min.css') }}" />
    <link href="{{ asset('frontend/fashionbag/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ mix('css/style.css', 'themes/fashionbag') }}"> 
    {{ load_header() }}
    <script>
        window.fashionbag = {
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
<style>
        .v-toast__text,
        .v-toast__icon {
            color: #fff;
        }

        [v-cloak] {
            display: none;
        }
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
        .mb-40{
            margin-bottom: 40px;
        }

        .header-logo img{
            max-width: 130px;
        }

        footer .logo img{
            max-width: 150px;
            margin-bottom: 30px;
        }

/* .btn-choose {
    border-color: #0DCAF0;
    background-color: #0DCAF0;
    color: #fff;
} */

.btn-outofstock {
    border-color: #ff4545;
    background-color: #ff4545;
    color: #fff;
}
.btn-outofstock:hover{ 
    color: #fff !important;
}
    </style>
</head>

<body>

    <!-- Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="text-center">
                    <img src="{{ asset('frontend/fashionbag/images/page-loading.gif') }}" alt="" />
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader End -->

    <div id="fashionbag" v-cloak>
        @include('fashionbag::layouts.header')
        @include('fashionbag::layouts.breadcrumb')
        @yield('content')
        @include('fashionbag::layouts.footer')
    </div>
    <!-- Scroll Top Start -->
    <a href="javascript::void(0)" class="scroll-top" id="scroll-top">
        <i class="arrow-top fa fa-long-arrow-up"></i>
        <i class="arrow-bottom fa fa-long-arrow-up"></i>
    </a>
    <!-- Scroll Top End -->
    <!-- Vendor JS-->
    <script src="{{ asset('frontend/fashionbag/js/vendor.min.js') }}"></script>
    {{-- <script src="{{ asset('frontend/fashionbag/js/plugins.min.js') }}"></script>  --}}
    <script src="{{ asset('frontend/fashionbag/js/plugins/countdown.min.js') }}"></script>
    <script src="{{ asset('frontend/fashionbag/js/plugins/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/fashionbag/js/plugins/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('frontend/fashionbag/js/plugins/jquery-ui.min.j') }}s"></script>
    <script src="{{ asset('frontend/fashionbag/js/plugins/lightgallery-all.min.js') }}"></script>
    <script src="{{ asset('frontend/fashionbag/js/plugins/thia-sticky-sidebar.min.js') }}"></script>
    <script src="{{ asset('frontend/fashionbag/js/select2.min.js') }}"></script>
    <script src="{{ mix('js/app.js', 'themes/fashionbag') }}"></script>
    <script src="{{ asset('frontend/fashionbag/js/main.js') }}"></script>
    @stack('js')
    <!-- Template  JS -->
    {{ load_footer() }}

    {{ load_whatsapp() }}
</body>

</html>
