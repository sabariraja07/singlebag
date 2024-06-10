
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title> {{ __('Account Suspended') }} - {{ env('APP_NAME') }}</title>
    <link rel="icon" type="image/png" href="assets/img/favicon.png" />

    <!--Core CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/green.css') }}">
    <style>
        .navbar-wrapper .navbar-brand img {
            height: 40px !important;
            max-height: 40px !important;
        }
    </style>
</head>

<body class="is-theme-core">
    {{-- <div class="pageloader"></div>
    <div class="infraloader is-active"></div> --}}
    @include('components.pageloader')
    <!-- Hero and nav -->
    <div class="hero is-relative is-fullheight">
        <!-- Navbar -->
        <nav class="navbar navbar-wrapper is-transparent is-static">
            <div class="container">
                <!-- Brand -->
                <div class="navbar-brand">
                    <a class="navbar-item" href="/">
                        <img src="{{ admin_logo_url() }}" height="40" alt="">
                    </a>

                    <!-- Responsive toggle -->
                    <div class="custom-burger" data-target="">
                        <a id="" class="responsive-btn" href="javascript:void(0);">
                            <span class="menu-toggle">
                              <span class="icon-box-toggle">
                                  <span class="rotate">
                                      <i class="icon-line-top"></i>
                                      <i class="icon-line-center"></i>
                                      <i class="icon-line-bottom"></i>
                                  </span>
                            </span>
                            </span>
                        </a>
                    </div>
                    <!-- /Responsive toggle -->
                </div>

                <!-- Navbar menu -->
                <div class="navbar-menu">
                    <!-- Navbar Start -->

                    <!-- Navbar end -->
                    <div class="navbar-end">
                        <!-- Signup button -->
                        <div class="navbar-item">
                            <a href="{{ route('seller.plan.index') }}" class="button button-cta btn-outlined is-bold btn-align secondary-btn rounded raised">
                                {{ __('Pricing') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="hero-body">
            <div class="container">
                <div class="columns is-vcentered">
                    <!-- Error Wrapper -->
                    <div class="column error-wrap error-centered has-text-centered">
                        <img src="{{ asset('assets/img/auth/error-8-green.svg') }}"  alt="" />
                        <div class="error-caption">
                            <h2>{{ __('Subscription Expired.') }}</h2>
                            <p>
                            {{ __('Sorry, your current plan is expired, please renew your plan.') }}
                            </p>
                            <div class="button-wrap">
                                <a href="{{ route('seller.plan-renew') }}" class="
                      button button-cta
                      btn-outlined
                      is-bold
                      btn-align
                      primary-btn
                      rounded
                      raised
                    ">
                    {{ __('Renew Now') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Back To Top Button -->
    <div id="backtotop"><a href="#"></a></div>
    <script src="{{ asset('asset/js/app.js') }}"></script>
    <script src="{{ asset('asset/js/core.js') }}"></script>
</body>

</html>