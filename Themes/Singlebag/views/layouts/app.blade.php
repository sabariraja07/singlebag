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
    <link rel="stylesheet" href="{{ asset('frontend/singlebag/css/font-awesome.min.css') }}" />
    <!-- <link rel="stylesheet" href="{{ asset('frontend/singlebag/css/plugins/toastr.min.css') }}" /> -->
    <link rel="stylesheet" href="{{ asset('frontend/singlebag/css/main.css') }}" />
    {{ load_header() }}
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
    <style>
        .txt-cart-empty {
            text-align: center !important;
            font-weight: 700;
            font-size: 18px;
            width: 100%;
        }

        .search-suggestions {
            position: absolute;
            top: 56px;
            left: 0px;
            background: #fff;
            border-bottom: 2px solid #0068e1;
            border-bottom: 2px solid var(--color-primary);
            border-radius: 0 0 2px 2px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, .15);
            z-index: 1050;
            overflow-y: scroll;
            max-width: 700px;
            width: 100%;
            margin: auto;
        }

        .search-suggestions .search-suggestions-inner {
            max-height: 425px;
            background: #fff;
            padding-bottom: 11px;
        }

        .search-suggestions .title {
            font-size: 13px;
            font-weight: 400;
            position: relative;
            padding: 6px 20px;
            color: #a6a6a6;
            background: #f9f9f9;
        }

        .search-suggestions .title:after {
            position: absolute;
            content: "";
            left: 0;
            top: 7px;
            height: 15px;
            width: 7px;
            background: #0068e1;
            background: var(--color-primary);
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        .search-suggestions .list-item {
            -webkit-transition: .15s ease-in-out;
            transition: .15s ease-in-out;
        }

        .search-suggestions .list-item.active {
            background: #f7f9fa !important;
        }

        .category-suggestion .title {
            margin-bottom: 14px;
        }

        .category-suggestion+.product-suggestion {
            margin-top: 11px;
        }

        .category-suggestion-list .single-item {
            font-size: 14px;
            line-height: 26px;
            display: block;
            max-width: 100%;
            padding: 0 20px;
            color: #6e6e6e;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            vertical-align: top;
        }

        .product-suggestion {
            padding-bottom: 3px;
        }

        .product-suggestion .title {
            margin-bottom: 14px;
        }

        .product-suggestion .more-results {
            font-size: 14px;
            display: block;
            margin: 14px 0 -14px;
            padding: 6px 0;
            color: #a6a6a6;
            background: #f9f9f9;
            text-align: center;
            border-top: 1px solid #ebebeb;
        }

        .product-suggestion .more-results:hover {
            color: #0068e1;
            color: var(--color-primary);
        }

        .product-suggestion-list .list-item {
            margin-bottom: 4px;
        }

        .product-suggestion-list .list-item:last-child {
            margin-bottom: 0;
        }

        .product-suggestion-list .single-item {
            position: relative;
            display: -webkit-box;
            display: flex;
            padding: 6px 20px;
        }

        .product-suggestion-list .product-image {
            height: 50px;
            width: 50px;
            min-width: 50px;
            border-radius: 2px;
            overflow: hidden;
        }

        .product-suggestion-list .product-image .image-placeholder {
            height: 35px;
            width: 35px;
        }

        .product-suggestion-list .product-item-info {
            min-width: 0;
            margin-left: 20px;
            -webkit-box-flex: 1;
            flex-grow: 1;
        }

        .product-suggestion-list .product-item-info-top {
            display: -webkit-box;
            display: flex;
            -webkit-box-pack: justify;
            justify-content: space-between;
        }

        .product-suggestion-list .product-name {
            font-size: 14px;
            display: inline-block;
            max-width: 100%;
            margin-bottom: 3px;
            color: #6e6e6e;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            vertical-align: top;
        }

        .product-suggestion-list .product-badge {
            position: relative;
            top: auto;
            right: auto;
            margin: -2px 0 -1px 15px;
        }

        .product-suggestion-list em {
            font-weight: 500;
            font-style: inherit;
            color: #0068e1;
            color: var(--color-primary);
        }

        @media screen and (max-width:991px) {
            .search-suggestions {
                top: 76px;
                left: 15px;
                right: 15px;
                display: none;
            }

            .search-suggestions .search-suggestions-inner {
                max-height: 359px;
            }

            .header-wrap-inner.sticky .search-suggestions {
                top: 71px;
            }

            .header-search-sm-form.active+.search-suggestions {
                display: block;
            }
        }

        @media screen and (max-width:391px) {
            .header-logo {
                width: 160px;
                padding-left: 5px;
            }
        }

        .header-search-wrap {
            position: relative;
            min-width: 0;
            -webkit-box-flex: 1;
            flex-grow: 1;
            padding: 0 40px;
        }

        .header-search {
            position: relative;
        }

        .header-search .header-search-lg {
            position: relative;
            display: -webkit-box;
            display: flex;
            border: 1px solid #e1e1e1;
            border-radius: 2px;
        }

        .header-search .header-search-lg .search-input {
            height: 58px;
            padding-left: 30px;
            border: none;
        }

        .header-search .header-search-lg .header-search-right {
            display: -webkit-box;
            display: flex;
            -webkit-box-align: center;
            align-items: center;
            margin-right: 4px;
        }

        .header-search .header-search-lg .btn-search {
            display: -webkit-box;
            display: flex;
            height: 50px;
            width: 50px;
            margin-left: 40px;
            padding: 0;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            border-radius: 0 2px 2px 0;
        }

        .header-search .header-search-lg .btn-search>i {
            font-size: 18px;
            line-height: 36px;
        }

        .header-search .nice-select {
            display: -webkit-box;
            display: flex;
            -webkit-box-align: center;
            align-items: center;
        }

        .header-search .nice-select .list {
            margin-top: 19px;
        }

        .header-search-sm-form {
            position: absolute;
            left: 15px;
            right: 15px;
            top: 0;
            height: 100%;
            background: #fff;
            opacity: 0;
            z-index: -1;
            -webkit-transform: scale(.96);
            transform: scale(.96);
            pointer-events: none;
            -webkit-transition: .15s ease-in-out;
            transition: .15s ease-in-out;
        }

        .header-search-sm-form.active {
            opacity: 1;
            -webkit-transform: scale(1);
            transform: scale(1);
            z-index: 1;
            pointer-events: auto;
        }

        .header-search-sm-form>form {
            position: relative;
            display: -webkit-box;
            display: flex;
            width: 100%;
            -webkit-box-align: center;
            align-items: center;
        }

        .header-search-sm-form>form>input {
            height: 60px;
            padding: 10px 50px;
            border: none;
        }

        .header-search-sm-form>form .btn-close,
        .header-search-sm-form>form .btn-search {
            position: absolute;
            top: 50%;
            display: -webkit-box;
            display: flex;
            height: 50px;
            width: 50px;
            padding: 15px;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            cursor: pointer;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
        }

        .header-search-sm-form>form .btn-close:hover>i,
        .header-search-sm-form>form .btn-search:hover>i {
            color: #0068e1;
            color: var(--color-primary);
        }

        .header-search-sm-form>form .btn-close>i,
        .header-search-sm-form>form .btn-search>i {
            font-size: 24px;
            color: #6e6e6e;
            -webkit-transition: .15s ease-in-out;
            transition: .15s ease-in-out;
        }

        .header-search-sm-form>form .btn-close {
            left: 0;
        }

        .header-search-sm-form>form .btn-search {
            right: 0;
        }

        @media screen and (min-width:992px) {

            .header-search-sm-form,
            .header-search .header-search-sm {
                display: none;
            }
        }

        @media screen and (max-width:991px) {
            .header-search-wrap {
                position: static;
                display: -webkit-box;
                display: flex;
                -webkit-box-flex: 0;
                flex-grow: 0;
                margin-left: auto;
                padding: 0 15px;
                -webkit-box-align: center;
                align-items: center;
            }

            .header-search .header-search-lg {
                display: none;
            }

            .header-search .header-search-sm:hover>i {
                color: #0068e1;
                color: var(--color-primary);
            }

            .header-search .header-search-sm>i {
                font-size: 34px;
                line-height: 36px;
                color: #191919;
                cursor: pointer;
                -webkit-transition: .15s ease-in-out;
                transition: .15s ease-in-out;
            }
        }

        @media screen and (max-width:391px) {
            .header-search-wrap {
                padding: 0 10px;
            }
        }

        .header-search .searched-keywords {
            display: -webkit-box;
            display: flex;
            width: 95%;
            margin: 14px auto -9px;
            -webkit-box-pack: center;
            justify-content: center;
        }

        .header-search .searched-keywords>label {
            font-size: 14px;
            line-height: 26px;
            margin-bottom: 0;
            color: #0068e1;
            color: var(--color-primary);
            white-space: nowrap;
        }

        .header-search .searched-keywords-list {
            height: 26px;
            overflow: hidden;
        }

        .header-search .searched-keywords-list li {
            font-size: 14px;
            line-height: 26px;
            position: relative;
            display: inline-block;
            padding: 0 15px;
        }

        .header-search .searched-keywords-list li:after {
            position: absolute;
            content: "";
            left: -1px;
            top: 4px;
            height: 16px;
            width: 1px;
            background: #a6a6a6;
        }

        .header-search .searched-keywords-list li:first-child:after {
            height: 0;
            width: 0;
        }

        .header-search .searched-keywords-list li a {
            color: #a6a6a6;
        }

        .header-search .searched-keywords-list li a:hover {
            color: #0068e1;
            color: var(--color-primary);
        }

        @media screen and (max-width:991px) {
            .header-search .searched-keywords {
                display: none;
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
    </style>
</head>

<body>
    <div id="singlebag" v-cloak>
        @include('singlebag::layouts.header')
        <main class="main">
            @include('singlebag::layouts.breadcrumb')
            @yield('content')
        </main>
        @include('singlebag::layouts.footer')
    </div>
    <!-- Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="text-center">
                    <img src="{{ asset('frontend/singlebag/imgs/theme/new_loading.gif') }}" alt="" />
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor JS-->
    <script src="{{ asset('frontend/singlebag/js/vendor/modernizr-3.6.0.min.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/vendor/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/vendor/jquery-migrate-3.3.0.min.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/plugins/slick.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/plugins/jquery.syotimer.min.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/plugins/waypoints.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/plugins/wow.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/plugins/jquery-ui.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/plugins/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/plugins/magnific-popup.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/plugins/select2.min.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/plugins/counterup.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/plugins/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/plugins/images-loaded.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/plugins/isotope.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/plugins/scrollup.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/plugins/jquery.vticker-min.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/plugins/jquery.theia.sticky.js') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/plugins/jquery.elevatezoom.js') }}"></script>
    <script src="{{ mix('js/app.js', 'themes/singlebag') }}"></script>
    @stack('js')
    <!-- Template  JS -->
    <script src="{{ asset('frontend/singlebag/js/main.js?v=3.2') }}"></script>
    <script src="{{ asset('frontend/singlebag/js/shops.js?v=3.2') }}"></script>
    {{ load_footer() }}

    {{ load_whatsapp() }}
</body>

</html>
