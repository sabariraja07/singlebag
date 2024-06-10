<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Store-Maintenance</title>
    {{-- generate seo info --}}
    {!! SEO::generate() !!}
    {!! JsonLdMulti::generate() !!}
    <link rel="icon" type="image/x-icon" href="{{ get_admin_favicon() }}">

    {{ Helper::autoload_main_site_data() }}

    @if (Cache::has('site_info'))
        @php
            $site_info = Cache::get('site_info');
            $main_color = $site_info->site_color;
        @endphp
    @else
        $main_color='#223a66';
    @endif
    <style type="text/css">
        :root {
            --main-theme-color: {{ $main_color }};
        }

        .navbar.navbar-landing.nav-light.is-faded {
            background-color: #0000001f !important;
        }

        .navbar-item img {
            max-height: 2.5rem !important;
        }

        .footer-light-medium .footer-body .small-footer-logo {
            height: 40px !important;
        }
    </style>

    <!--Core CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/app.css') }}">
    <link id="theme-sheet" rel="stylesheet" href="{{ asset('asset/css/green.css') }}">
    @stack('style')
</head>

<body class="is-theme-green">
    @include('components.pageloader')

    @yield('content')

    <!-- /Chat widget -->
    <script src="{{ asset('asset/js/app.js') }}"></script>
    <script src="{{ asset('asset/js/core.js') }}"></script>
    @stack('js')
</body>

</html>
