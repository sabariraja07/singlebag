<!DOCTYPE html>
<html class="no-js" lang="{{ App::getlocale() }}">

<head>
    <base href="{{ url('/') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- generate seo info --}}
    {!! SEO::generate() !!}
    {!! JsonLdMulti::generate() !!}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('css')
    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css?family=Questrial|Raleway:700,900" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ asset('frontend/electrobag/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('frontend/electrobag/css/bootstrap.extension.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('frontend/electrobag/css/style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('frontend/electrobag/css/swiper.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('frontend/electrobag/css/sumoselect.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('frontend/electrobag/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('frontend/electrobag/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="icon" type="image/x-icon" href="{{ get_shop_favicon(domain_info('shop_id')) }}" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ get_shop_favicon(domain_info('shop_id')) }}" />
    {{ load_header() }}
    <style type="text/css">
        :root {
            --main-theme-color: {{ Cache::get(domain_info('shop_id').'theme_color','#212060') }};   
        }
        .img-center{
            text-align: center;
            margin-top: 403px;
        }
    </style>
    @php
        Helper::autoload_site_data();
    @endphp
    <script>
        window.singlebag = {
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
</head>

<body>
    <!-- Preloader Start -->
    <div id="loader-wrapper">
        <div class="img-center">
            <img src="{{ asset('frontend/electrobag/img/new_loading.gif') }}" alt="" />
        </div>
    </div>
    <div id="content-block">
        <div id="electrobag" v-cloak>
            @include('electrobag::layouts.header')
            {{-- @include('electrobag::layouts.breadcrumb') --}}
            @yield('content')
        </div>
        @include('electrobag::layouts.footer')
    </div>

    <script src="{{ asset('frontend/electrobag/js/vendor/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('frontend/electrobag/js/vendor/swiper.jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/electrobag/js/vendor/global.js') }}"></script>
    <script src="{{ asset('frontend/electrobag/js/vendor/jquery.sumoselect.min.js') }}"></script>
    <script src="{{ asset('frontend/electrobag/js/vendor/jquery.classycountdown.js') }}"></script>
    <script src="{{ asset('frontend/electrobag/js/vendor/jquery.knob.js') }}"></script>
    <script src="{{ asset('frontend/electrobag/js/vendor/select2.min.js') }}"></script>
    <script src="{{ asset('frontend/electrobag/js/vendor/jquery.throttle.js') }}"></script>
    <script src="{{ mix('js/app.js', 'themes/electrobag') }}"></script>
    @stack('js')
    {{ load_footer() }}

    {{ load_whatsapp() }}
</body>
</html>
