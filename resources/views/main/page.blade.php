<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- generate seo info --}}
    {!! SEO::generate() !!}
    {!! JsonLdMulti::generate() !!}
    <link rel="icon" type="image/x-icon" href="{{ get_admin_favicon() }}">

    {{ Helper::autoload_main_site_data() }}

    @if(Cache::has('site_info'))
    @php
    $site_info=Cache::get('site_info');
    $main_color=$site_info->site_color;
    @endphp
    @else
    $main_color='#223a66';
    @endif
    <style type="text/css">
        :root {
            --main-theme-color: {{ $main_color}};
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
    @include('layouts.partials.analytics')
    @stack('style')
</head>

<body class="is-theme-green">
    {{-- <div class="pageloader"></div>
    <div class="infraloader is-active"></div> --}}
    @include('components.pageloader')
    <div class="hero is-app-grey">
        <nav class="navbar navbar-wrapper is-cloned">
            <div class="container">
                <div class="navbar-brand">
                    <a class="navbar-item" href="{{ url(env('APP_URL') ) }}}">
                        <img class="light-logo" src="{{ admin_logo_url() }}" alt="">
                        <img class="dark-logo switcher-logo-square is-hidden" src="{{ admin_logo_url() }}" alt="">
                    </a>

                    <!-- Sidebar Trigger -->
                    <!-- <a id="navigation-trigger" class="navbar-item hamburger-btn" href="javascript:void(0);">
                        <span class="menu-toggle">
                            <span class="icon-box-toggle">
                                <span class="rotate">
                                    <i class="icon-line-top"></i>
                                    <i class="icon-line-center"></i>
                                    <i class="icon-line-bottom"></i>
                                </span>
                        </span>
                        </span>
                    </a> -->

                    <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                    </a>
                </div>

                <div id="is-cloned" class="navbar-menu">
                    <div class="navbar-start">
                        <a href="{{ url('/#main-features') }}" class="navbar-item is-slide is-centered-tablet scroll-link">
                            Features
                        </a>

                        <a href="{{ url('/#pricing') }}" class="navbar-item is-slide is-centered-tablet scroll-link">
                            Pricing
                        </a>
                        <a href="https://community.singlebag.com" class="navbar-item is-slide is-centered-tablet scroll-link">
                            Community
                        </a>
                        <a href="https://community.singlebag.com/lp-courses" class="navbar-item is-slide is-centered-tablet scroll-link">
                            Academy
                        </a>
                    </div>

                    <div class="navbar-end">
                        <a href="{{ url(env('APP_URL') . '/verify-store') }}" class="navbar-item is-slide is-centered-tablet">
                            Login
                        </a>
                        <div class="navbar-item is-button is-centered-tablet">
                            <a href="{{ route('seller.signup') }}" class="button button-cta is-bold btn-align primary-btn raised is-rounded">Create Store</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <nav class="navbar navbar-wrapper is-transparent is-static">
            <div class="container">
                <div class="navbar-brand">
                    <a class="navbar-item" href="{{ url('/') }}">
                        <img class="light-logo" src="{{ admin_logo_url() }}" alt="">
                        <img class="dark-logo switcher-logo-square is-hidden" src="{{ admin_logo_url() }}" alt="">
                    </a>

                    <!-- Sidebar Trigger -->
                    <!-- <a id="navigation-trigger" class="navbar-item hamburger-btn" href="javascript:void(0);">
                        <span class="menu-toggle">
                            <span class="icon-box-toggle">
                                <span class="rotate">
                                    <i class="icon-line-top"></i>
                                    <i class="icon-line-center"></i>
                                    <i class="icon-line-bottom"></i>
                                </span>
                        </span>
                        </span>
                    </a> -->

                    <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                    </a>
                </div>

                <div id="is-static" class="navbar-menu">
                    <div class="navbar-start">
                        <a href="{{ url('/#main-features') }}" class="navbar-item is-slide is-centered-tablet scroll-link">
                            Features
                        </a>

                        <a href="{{ url('/#pricing') }}" class="navbar-item is-slide is-centered-tablet scroll-link">
                            Pricing
                        </a>
                        <a href="https://community.singlebag.com" class="navbar-item is-slide is-centered-tablet scroll-link">
                            Community
                        </a>
                        <a href="https://community.singlebag.com/lp-courses" class="navbar-item is-slide is-centered-tablet scroll-link">
                            Academy
                        </a>
                    </div>

                    <div class="navbar-end">
                        <a href="{{ url(env('APP_URL') . '/verify-store') }}" class="navbar-item is-slide is-centered-tablet">
                            Login
                        </a>
                        <div class="navbar-item is-button is-centered-tablet">
                            <a href="{{ route('seller.signup') }}" class="button button-cta is-bold btn-align primary-btn raised is-rounded">Create Store</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Hero image -->
        @yield('content-header')
    </div>

    @yield('content')

    <!-- Dark footer -->
    <footer class="footer-light-medium">
        <div class="container">
            <div class="footer-head">
                <div class="head-text">
                    <h3>Ready to get started?</h3>
                    <p>Create your online store for free now.</p>
                </div>
                <div class="head-action">
                    <div class="buttons">
                        <a href="{{ route('seller.signup') }}" class="button primary-btn raised action-button">Try it free</a>
                        <!-- <a class="button chat-button">Chat with us</a> -->
                    </div>
                </div>
            </div>
            <div class="columns footer-body">
                <!-- Column -->
                @if(Cache::has('site_info'))
                @php
                $site_info=Cache::get('site_info');
                @endphp
                <div class="column is-4">
                    <div class="pt-10 pb-10">
                        <img class="small-footer-logo" src="{{ admin_logo_url() }}" alt="{{ env('APP_NAME') }}">
                        <div class="footer-description">{{ $site_info->site_description ?? ""}}</div>
                    </div>
                    <div>
                        <div class="social-links">
                            @if(!empty($site_info->facebook))
                            <a href="{{ $site_info->facebook }}" target="_blank">
                                <span class="icon"><i class="fa fa-facebook"></i></span>
                            </a>
                            @endif
                            @if(!empty($site_info->twitter))
                            <a href="{{ $site_info->twitter }}" target="_blank">
                                <span class="icon"><i class="fa fa-twitter"></i></span>
                            </a>
                            @endif
                            @if(!empty($site_info->linkedin))
                            <a href="{{ $site_info->linkedin }}" target="_blank">
                                <span class="icon"><i class="fa fa-linkedin"></i></span>
                            </a>
                            @endif
                            @if(!empty($site_info->instagram))
                            <a href="{{ $site_info->instagram }}" target="_blank">
                                <span class="icon"><i class="fa fa-instagram"></i></span>
                            </a>
                            @endif
                            @if(!empty($site_info->youtube))
                            <a href="{{ $site_info->youtube }}" target="_blank">
                                <span class="icon"><i class="fa fa-youtube-play"></i></span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                <!-- Column -->
                <div class="column is-6 is-offset-2">
                    <div class="columns">
                        <!-- Column -->
                        <div class="column">
                           {{ welcome_footer_menu('footer_left','footer-column','column-item','','top',false) }}
                        </div>
                        <!-- Column -->
                        <div class="column">
                        {{ welcome_footer_menu('footer_center','','','','top',false) }}
                        </div>
                        <!-- Column -->
                        <div class="column">
                        {{ welcome_footer_menu('footer_right','','','','top',false) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-copyright has-text-centered">
                <p>
                    &copy; {{ date('Y', strtotime("-1 years")) }}-{{ date("Y") }} | <a href="{{ url('/') }}">{{ env('APP_NAME') }}</a> | All
                    Rights Reserved.
                </p>
            </div>
        </div>
    </footer>
    <!-- /Dark footer -->
  
    <!-- Back To Top Button -->
    <div id="backtotop"><a href="#"></a></div>
    <!-- /Chat widget -->
    <script src="{{ asset('asset/js/app.js') }}"></script>
    <script src="{{ asset('asset/js/core.js') }}"></script>
    @stack('js')

    @if(Cache::has('marketing_tool'))
    @php
    $tools=Cache::get('marketing_tool');
    $tools=json_encode($tools);
    $tools=json_decode($tools ?? '');
    @endphp
    @isset($tools->google_status)
    @if($tools->google_status == 'on')
    {!! google_analytics($tools->ga_measurement_id) !!}
    @endif
    @endisset

    @endif
</body>

</html>