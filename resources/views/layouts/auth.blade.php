<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ get_admin_favicon() }}">
    <link rel="icon" type="image/x-icon" href="{{ get_admin_favicon() }}">

    <!--Core CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/green.css') }}">
    <style>
       .invalid-feedback strong {
            color: #e62965;
            font-weight: 700;
        }
        .hero-body .auth-content {
            text-align: center;
        }
        .auth-logo{
            text-align: center;
            margin-bottom: 60px;
        }
        .auth-logo img.top-logo{
            height: 67px !important;
        }
    </style>
    @include('layouts.partials.analytics')
    @stack('css')
</head>

<body>
    {{-- <div class="pageloader"></div>
    <div class="infraloader is-active"></div> --}}
    @include('components.pageloader')
    <!-- Wrapper -->
    @yield('content')
    <!-- Back To Top Button -->
    <div id="backtotop"><a href="#"></a></div>
    <script src="{{ asset('asset/js/app.js') }}"></script>
    <script src="{{ asset('asset/js/core.js') }}"></script>
    @stack('js')
</body>

</html>