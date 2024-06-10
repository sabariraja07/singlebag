<!DOCTYPE html>
<html class="loading" lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <base href="{{ url('/') }}">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (current_shop_id() != 0)
        @php
            $shop = \App\Models\Shop::where('id', current_shop_id())->first();
        @endphp
        <title>{{ ucfirst($shop->name) ?? '' }} - @yield('title')</title>
        <link rel="icon" href="{{ asset('uploads/' . current_shop_id() . '/favicon.ico') }}">
        <link rel="apple-touch-icon" href="./images/ico/apple-icon-120.png">
        <link rel="shortcut icon" type="image/x-icon" href="./images/logo/sb-150x150.png">
    @else
        <title>{{ env('APP_NAME') }} - @yield('title')</title>
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('uploads/favicon.ico') }}">
        <link rel="apple-touch-icon" href="./images/ico/apple-icon-120.png">
        <link rel="shortcut icon" type="image/x-icon" href="./images/logo/sb-150x150.png">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">
    <script src="https://unpkg.com/phosphor-icons"></script>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/phosphor-icons@1.4.2/src/css/icons.css">
    <style>
        .table-responsive {
            min-height: 280px;
        }
    </style>
    {{-- include default styles --}}
    @include('partials/styles')

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static" data-open="click"
    data-menu="vertical-menu-modern" data-col="">

    @include('partials.agent_navbar')

    @include('partials.agent_sidebar')


    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        @yield('content')
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('partials.footer')

    {{-- include default scripts --}}
    @include('partials/scripts')

</body>
<!-- END: Body-->

</html>