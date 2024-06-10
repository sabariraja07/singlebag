<!DOCTYPE html>
<html class="loading semi-dark-layout" lang="en" data-layout="semi-dark-layout" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <base href="{{ url('/') }}">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    @if(Route::currentRouteName() == 'seller.signup')
    <meta name="description" content="Build an online store with Singlebag by providing your details below, find new customers and start selling .">
    @elseif(Route::currentRouteName() == 'supplier.signup')
    <meta name="description" content="Build an online supplier store with Singlebag by providing your details below, find new customers and start selling .">
    @elseif(Route::currentRouteName() == 'reseller.signup')
    <meta name="description" content="Build an online reseller store with Singlebag by providing your details below, find new customers and start selling .">
    @else
    <meta name="description" content="">
    @endif
    <meta name="keywords" content="">
    <meta name="author" content="">
    <title>{{ env('APP_NAME') }} - @yield('title')</title>
    <link rel="apple-touch-icon" href="{{ get_admin_favicon() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ get_admin_favicon() }}">
    <link rel="icon" type="image/x-icon" href="{{ get_admin_favicon() }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    {{-- include default styles --}}
     @include('partials/styles')


</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static " data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- END: Content-->
    {{-- include default scripts --}}
    @include('partials/scripts')
    
    <!-- BEGIN: Page Vendor JS-->
    <script src="./vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="./vendors/js/forms/wizard/bs-stepper.min.js"></script>
    <script src="./vendors/js/forms/cleave/cleave.min.js"></script>
    <script src="./vendors/js/forms/cleave/addons/cleave-phone.us.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Page JS-->
    <script src="./admin/js/scripts/pages/auth-login-new.js"></script>
    <script src="./admin/js/scripts/pages/auth-register-new.js"></script>
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>

</body>
<!-- END: Body-->

</html>