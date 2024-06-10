<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Become Partner | Join Singlebag Partner Program Now!</title>
    <meta name="description" content="Earn 25% revenue share with the Singlebag Partner Program | Offer Your Clients with Best-in-class Ecommerce Solution | 24/7 Lifetime Support | Access Singlebag Partner Toolkit | 100% Free to Join">
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

    <!--Core CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/app.css') }}">
    <link id="theme-sheet" rel="stylesheet" href="{{ asset('asset/css/green.css') }}">
    <style>
        .footer-light-medium .footer-body .small-footer-logo {
            height: 40px !important;
        }
        .navbar-item img {
            min-height: 2.5rem !important;
            max-height: 2.5rem !important;
        }
    </style>
</head>

<body class="is-theme-green">
    {{-- <div class="pageloader"></div>
    <div class="infraloader is-active"></div> --}}
    @include('components.pageloader')
    <!-- Fullheight Parallax Hero -->
    <div class="hero parallax is-cover is-relative is-fullheight"
        data-background="{{ asset('assets/img/partner/banner.png') }}" data-color="#545375" data-color-opacity="0.6">
        <!--Navbar-->
        <nav class="navbar navbar-wrapper navbar-fade navbar-light is-transparent">
            <div class="container">
                <!-- Brand -->
                <div class="navbar-brand">
                    <a class="navbar-item" href="{{ url('/') }}">
                        <img class="light-logo" src="{{ admin_logo_url() }}" alt="">
                        <img class="dark-logo switcher-logo" src="{{ admin_logo_url(true) }}" alt="">
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
                    <div class="navbar-start">
                        {{-- <a class="navbar-item is-slide scroll-link" href="{{ url('/') }}">
                            Home
                        </a> --}}
                        <a href="https://community.singlebag.com" class="navbar-item is-slide scroll-link">
                            Community
                        </a>
                        <a href="https://community.singlebag.com/lp-courses" class="navbar-item is-slide scroll-link">
                            Academy
                        </a>
                        <a href="{{ '/partner/faq' }}" class="navbar-item has-hover scroll-link">
                            FAQ
                         </a>
                    </div>
                    <!-- Navbar end -->
                    <div class="navbar-end">
                        <a href="{{ url(env('APP_URL') . '/partner/login') }}" class="navbar-item is-slide scroll-link">
                            Login
                        </a>
                        <!-- Signup button -->
                        <div class="navbar-item is-button">
                            <a id="#signup-btn" href="{{ url(env('APP_URL') . '/partner/register') }}"
                                class="button button-signup btn-outlined is-bold btn-align light-btn raised">
                                Become a Partner
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Hero caption -->
        <div id="main-hero" class="hero-body">
            <div class="container has-text-centered">
                <div class="columns is-vcentered">
                    <div class="column is-8 is-offset-2 has-text-centered">
                        <h1 class="parallax-hero-title is-smaller light-text">
                            Join the Singlebag Partner Program
                        </h1>
                        <h2 class="subtitle is-5 light-text pt-4">
                            Your expertise is our entrepreneurs’ growth. Help merchants to excel in e-commerce by
                            introducing Singlebag.
                        </h2>
                        <br />
                        <p class="has-text-centered is-centered buttons">
                            <a href="{{ url(env('APP_URL') . '/partner/register') }}" class="button button-cta btn-align primary-btn z-index-2 scroll-link">
                                Become a Partner
                            </a>
                            {{--
                            <a href="#" class="button button-cta btn-align z-index-2">
                                Learn More
                            </a>
                            --}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        #start .content-container p{
           display: none;
        }
        #start .flex-card:hover .icon-container,#start .flex-card:hover .content-container h3 {
           display: none;
        }

        #start .flex-card:hover p{
           display: block;
        }

        #start .flex-card{
           height: 100%;
        }
    </style>

    <!--Benefit Boxes-->
    <div id="start" class="section">
        <div class="container">
            <div class="columns is-multiline is-flex-portrait">
                <!-- Benefit box -->
                <div class="column is-one-fifth flex-portrait-4">
                    <div class="flex-card is-feature padding-20">
                        <!-- Icon -->
                        <div class="icon-container is-first is-icon-reveal">
                            <img src="{{ asset('assets/img/time-core.svg') }}" alt="" />
                        </div>
                        <!-- Content -->
                        <div class="content-container flex-wrapper flex-center has-text-centered pb-4">
                            <h3>Hassle-free Procedures</h3>
                            <p>
                            Our procedures are hassle-free and done in a minute to save your time and energy.</p>
                        </div>
                    </div>
                </div>
                <!-- Benefit box -->
                <div class="column is-one-fifth flex-portrait-4">
                    <div class="flex-card is-feature padding-20">
                        <!-- Icon -->
                        <div class="icon-container is-second is-icon-reveal">
                            <img src="{{ asset('assets/img/diamond-core.svg') }}" alt="" />
                        </div>
                        <!-- Content -->
                        <div class="content-container has-text-centered pb-4">
                            <h3>100% Revenue</h3>
                            <p>Partners have the opportunity of generating a good amount of revenue with their talents and innovative ideas.</p>
                        </div>
                    </div>
                </div>
                <!-- Benefit box -->
                <div class="column is-one-fifth flex-portrait-4">
                    <div class="flex-card is-feature padding-20">
                        <!-- Icon -->
                        <div class="icon-container is-third is-icon-reveal">
                            <img src="{{ asset('assets/img/learn-core.svg') }}" alt="" />
                        </div>
                        <!-- Content -->
                        <div class="content-container has-text-centered pb-4">
                            <h3>Relationships</h3>
                            <p>
                            Our staff establishes long-lasting relationships with partners to grow in the program you've decided to take part in.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Benefit box -->
                <div class="column is-one-fifth flex-portrait-4">
                    <div class="flex-card flip-card is-feature padding-20">
                        <!-- Icon -->
                        <div class="icon-container is-third is-icon-reveal">
                            <img src="{{ asset('assets/img/aquire-core.svg') }}" alt="" />
                        </div>
                        <!-- Content -->
                        <div class="content-container has-text-centered pb-4">
                            <h3>Secured Data</h3>
                            <p>
                            Our system stores your data intact to ensure that your data is safe and secured with our AI technology.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Benefit box -->
                <div class="column is-one-fifth flex-portrait-4">
                    <div class="flex-card is-feature padding-20">
                        <!-- Icon -->
                        <div class="icon-container is-third is-icon-reveal">
                            <img src="{{ asset('assets/img/study-core.svg') }}" alt="" />
                        </div>
                        <!-- Content -->
                        <div class="content-container has-text-centered pb-4">
                            <h3>AI & Technology</h3>
                            <p>
                            Our business model uses advanced technology and AI which helps you manage your time significantly & productively.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="solution" class="section is-app-grey">
        <div class="container">
            <div class="section-title-wrapper has-text-centered my-6">
                <div class="bg-number"></div>
                <h2 class="section-title-landing">Ordinary Merchants To Successful Singlebag Partners</h2>
                <h4>
                    Shoulder the responsibility of introducing fresh merchants to Singlebag and
                    offering solutions that help them initiate, trade, sell, and manage their businesses.
                </h4>
            </div>
        </div>
    </div>

    <!--How It Works-->
    <div id="solution" class="section">
        <div class="container">
            <div class="section-title-wrapper py-4">
                <div class="bg-number">1</div>
                <h2 class="title section-title has-text-centered dark-text text-bold">
                How Do We Function?
                </h2>
            </div>

            <!-- Feature -->
            <div class="columns is-vcentered">
                <div class="column is-6 is-offset-1 has-text-centered">
                    <div class="masked-image">
                        <img class="main-image" src="{{ asset('assets/img/partner/feature1.png') }}" alt="" />
                        <img class="image-mask" src="{{ asset('assets/img/shapes/mask-1.svg') }}" alt="" />
                        <!--Dots-->
                        <div class="dot dot-primary dot-1 levitate"></div>
                        <div class="dot dot-success dot-2 levitate delay-3"></div>
                        <div class="dot dot-info dot-3 levitate delay-2"></div>
                    </div>
                </div>

                <div class="column is-4">
                    <div class="icon-subtitle"><i class="im im-icon-Note"></i></div>
                    <h2 class="title quick-feature is-smaller text-bold no-margin">
                        Build online store
                        <span class="bg-number">1</span>
                    </h2>
                    <div class="title-divider"></div>
                    <span class="section-feature-description">
                        Earn 25% recurring revenue by building new Singlebag online stores for your clients. 
                        Tie up your clients with a trusted and customizable platform to run and flourish their businesses.
                    </span>
                    {{--
                    <div class="pt-10 pb-10">
                        <a href="#" class="button btn-align btn-more is-link color-primary">
                            Learn more about this <i class="sl sl-icon-arrow-right"></i>
                        </a>
                    </div>
                    --}}
                </div>
            </div>

            <!-- Feature -->
            <div class="columns is-vcentered">
                <div class="column is-6 has-text-centered is-hidden-tablet is-hidden-desktop">
                    <div class="masked-image">
                        <img class="main-image" src="{{ asset('assets/img/partner/feature2.png') }}" alt="" />
                        <img class="image-mask" src="{{ asset('assets/img/shapes/mask-2.svg') }}" alt="" />
                        <!--Dots-->
                        <div class="dot dot-primary dot-4 levitate"></div>
                        <div class="dot dot-success dot-5 levitate delay-3"></div>
                        <div class="dot dot-info dot-3 levitate delay-2"></div>
                    </div>
                </div>

                <div class="column is-4 is-offset-1">
                    <div class="icon-subtitle"><i class="im im-icon-Professor"></i></div>
                    <h2 class="title quick-feature is-smaller text-bold no-margin">
                        Singlebag Solutions
                        <span class="bg-number">2</span>
                    </h2>
                    <div class="title-divider"></div>
                    <span class="section-feature-description">
                        There are many ways to earn revenue as a Singlebag partner with development or 
                        marketing skills and so on.Market your clients store, products, or their service 
                        directly to the people.
                    </span>
                    {{--
                    <div class="pt-10 pb-10">
                        <a href="#" class="button btn-align btn-more is-link color-primary">
                            Learn more about this <i class="sl sl-icon-arrow-right"></i>
                        </a>
                    </div>
                    --}}
                </div>

                <div class="column is-6 is-offset-1 has-text-centered is-hidden-mobile">
                    <div class="masked-image">
                        <img class="main-image" src="{{ asset('assets/img/partner/feature2.png') }}" alt="" />
                        <img class="image-mask" src="{{ asset('assets/img/shapes/mask-2.svg') }}" alt="" />
                        <!--Dots-->
                        <div class="dot dot-primary dot-4 levitate"></div>
                        <div class="dot dot-success dot-5 levitate delay-3"></div>
                        <div class="dot dot-info dot-3 levitate delay-2"></div>
                    </div>
                </div>
            </div>

            <!-- Feature -->
            <div class="columns is-vcentered">
                <div class="column is-6 is-offset-1 has-text-centered">
                    <div class="masked-image">
                        <img class="main-image" src="{{ asset('assets/img/partner/feature3.png') }}" alt="" />
                        <img class="image-mask" src="{{ asset('assets/img/shapes/mask-3.svg') }}" alt="" />
                        <!--Dots-->
                        <div class="dot dot-primary dot-6 levitate"></div>
                        <div class="dot dot-success dot-5 levitate delay-3"></div>
                        <div class="dot dot-info dot-3 levitate delay-2"></div>
                    </div>
                </div>

                <div class="column is-4">
                    <div class="icon-subtitle"><i class="im im-icon-Yes"></i></div>
                    <h2 class="title quick-feature is-smaller text-bold no-margin">
                        Learn how to become a Partner
                        <span class="bg-number">3</span>
                    </h2>
                    <div class="title-divider"></div>
                    <span class="section-feature-description">
                        To understand Singlebag Partner Program better a comprehensive documentation, an academy with courses and guides,
                        a thriving partner community and partner groups are provided.Learn how to become a certified Singlebag partner at Singlebag Academy.
                    </span>
                    {{--
                    <div class="pt-10 pb-10">
                        <a href="#" class="button btn-align btn-more is-link color-primary">
                            Learn more about this <i class="sl sl-icon-arrow-right"></i>
                        </a>
                    </div>
                    --}}
                </div>
            </div>

            <!-- Feature -->
            <div class="columns is-vcentered">
                <div class="column is-6 has-text-centered is-hidden-tablet is-hidden-desktop">
                    <div class="masked-image">
                        <img class="main-image" src="{{ asset('assets/img/partner/feature4.png') }}" alt="" />
                        <img class="image-mask" src="{{ asset('assets/img/shapes/mask-2.svg') }}" alt="" />
                        <!--Dots-->
                        <div class="dot dot-primary dot-4 levitate"></div>
                        <div class="dot dot-success dot-5 levitate delay-3"></div>
                        <div class="dot dot-info dot-3 levitate delay-2"></div>
                    </div>
                </div>

                <div class="column is-4 is-offset-1">
                    <div class="icon-subtitle"><i class="im im-icon-Professor"></i></div>
                    <h2 class="title quick-feature is-smaller text-bold no-margin">
                    Partners sales toolkit
                        <span class="bg-number">4</span>
                    </h2>
                    <div class="title-divider"></div>
                    <span class="section-feature-description">
                        This guide will help you how to position Singlebag to your prospective clients, and show 
                        them why Singlebag's hosted ecommerce platform is appropriate for their business.
                    </span>
                    {{--
                    <div class="pt-10 pb-10">
                        <a href="#" class="button btn-align btn-more is-link color-primary">
                            Learn more about this <i class="sl sl-icon-arrow-right"></i>
                        </a>
                    </div>
                    --}}
                    <div class="inner-content">
                        <div class="head-action">
                            <div class="buttons" style="padding-top: 21px;">
                                <a href="/assets/pdf/partner_toolkit.pdf" class="button warning-btn raised action-button" target="_blank">Download sales toolkit</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="column is-6 is-offset-1 has-text-centered is-hidden-mobile">
                    <div class="masked-image">
                        <img class="main-image" src="{{ asset('assets/img/partner/feature4.png') }}" alt="" />
                        <img class="image-mask" src="{{ asset('assets/img/shapes/mask-2.svg') }}" alt="" />
                        <!--Dots-->
                        <div class="dot dot-primary dot-4 levitate"></div>
                        <div class="dot dot-success dot-5 levitate delay-3"></div>
                        <div class="dot dot-info dot-3 levitate delay-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Testimonials section -->
    <div class="section is-relative is-app-grey">
        <div class="container">
            <!-- Title -->
            <div class="section-title-wrapper my-6">
                <div class="bg-number">2</div>
                <h2 class="title section-title has-text-centered dark-text text-bold">
                From Our Partners...
                </h2>
                <!--Colored Shapes-->
                <svg class="blob-1" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#837FCB"
                        d="M37.1,-25.2C47.4,-16.7,54.5,-1.3,50,8.6C45.5,18.5,29.3,23,15.4,28.2C1.5,33.5,-10.2,39.5,-22.6,37.3C-35,35,-48,24.5,-51.2,11.4C-54.5,-1.7,-48,-17.4,-37.8,-25.9C-27.7,-34.3,-13.8,-35.6,-0.2,-35.4C13.4,-35.2,26.8,-33.7,37.1,-25.2Z"
                        transform="translate(100 100)" />
                </svg>

                <svg class="blob-2" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#837FCB"
                        d="M48,-10.8C56.2,9.7,52.8,38.8,37.8,49C22.8,59.2,-3.8,50.5,-24.2,35.2C-44.7,19.9,-59,-2,-53.9,-18.2C-48.9,-34.4,-24.4,-45,-2.3,-44.2C19.9,-43.5,39.7,-31.4,48,-10.8Z"
                        transform="translate(100 100)" />
                </svg>


                <svg class="blob-3" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#837FCB"
                        d="M45.3,-16.1C52.1,6.1,46.3,31,31.3,41.5C16.3,52,-8,48.2,-30,33.9C-52.1,19.6,-72,-5.1,-66.6,-25.4C-61.2,-45.7,-30.6,-61.5,-5.7,-59.7C19.3,-57.9,38.5,-38.3,45.3,-16.1Z"
                        transform="translate(100 100)" />
                </svg>


                <svg class="blob-4" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#837FCB"
                        d="M49.2,-14.2C58.8,13.6,58.2,46.5,43.2,56.7C28.2,66.9,-1.2,54.6,-18.6,38.8C-35.9,23.1,-41.1,4,-36,-17.5C-30.9,-39.1,-15.4,-63.1,2.2,-63.8C19.8,-64.5,39.6,-41.9,49.2,-14.2Z"
                        transform="translate(100 100)" />
                </svg>


                <svg class="blob-5" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#837FCB"
                        d="M54.1,-26.7C58.3,-4.9,41.5,14.6,23.8,25.8C6.1,37,-12.6,40,-27.3,30.9C-42.1,21.8,-52.9,0.5,-47.7,-22.7C-42.5,-46,-21.2,-71.2,1.9,-71.8C25,-72.4,50,-48.4,54.1,-26.7Z"
                        transform="translate(100 100)" />
                </svg>
            </div>

            <div class="content-wrapper">
                <div class="columns">
                    <div class="column is-hidden-mobile"></div>

                    <div class="column is-6">
                        <!-- Carousel wrapper -->
                        <div class="testimonials">
                            <!-- Testimonial item -->
                            @foreach($testimonials as $key => $row)
                            <div class="testimonial-item">
                                <div class="flex-card card-overflow raised mb-40">
                                    <div class="testimonial-avatar">
                                        <img src="{{ $row->preview ? asset($row->preview->content) : 'https://ui-avatars.com/api/?name=' .$row->name ?? '' .'&background=random&length=1&color=#fff'}}" alt=""/>
                                    </div>
                                    <div class="testimonial-name">
                                        <h3>{{ $row->name ?? '' }}</h3>
                                        <span>{{ $row->slug ?? '' }}</span>
                                    </div>
                                    <div class="testimonial-content">
                                        <p>{{ $row->excerpt->content ?? "" }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="column is-hidden-mobile"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services -->
    {{-- <div id="use-cases" class="section my-6">
        <div class="container">
            <!-- Title -->
            <div class="section-title-wrapper has-text-centered">
                <div class="bg-number">3</div> --}}
                {{-- <h2 class="section-title-landing">Collaborate to Rise!</h2> --}}
                {{-- <h4>No matter you’re established or just starting, Singlebag gives you the tools, resources and knowledge you ever need.</h4> --}}
            {{-- </div>

            <div class="content-wrapper tabbed-features">
                <!-- Navigation pills -->
                <div class="columns is-vcentered">
                    <div class="column">
                        <div class="navigation-tabs outlined-pills animated-tabs">
                            <!-- Pill content -->
                            <div id="procurement" class="navtab-content is-active">
                                <div class="columns is-vcentered">
                                    <div class="column is-4"> --}}
                                        {{-- <figure class="image">
                                            <img class="has-light-shadow" src="{{ asset('assets/img/partner/collabrate.png') }}" alt="" />
                                        </figure> --}}
                                    {{-- </div>
                                    <div class="column is-6">
                                        <div class="inner-content" style="margin-top:36px">
                                            <h2 class="feature-headline is-clean">
                                            <i class="sl sl-icon-check" style="margin-right: 5px;"></i>
                                            Connect Your Customers
                                            </h2>
                                            <p class="body-color">
                                            The Singlebag App Store along with its team provides a place to trade your products 
                                            or directly serve the people who want to join hands with you.
                                            </p>
                                            
                                        </div>
                                        <div class="inner-content">
                                            <h2 class="feature-headline is-clean">
                                            <i class="sl sl-icon-check" style="margin-right: 5px;"></i>
                                            Space to Learn & Execute
                                            </h2>
                                            <p class="body-color">                                              
                                                The Singlebag Partner Program offers various courses & guides, a successful partner 
                                                community and 24x7 partner supports for Learning and Execution.
                                            </p>
                                        </div>
                                        <div class="inner-content">
                                            <div class="head-action">
                                                <div class="buttons">
                                                    <a href="/assets/pdf/partner_toolkit.pdf" class="button warning-btn raised action-button" target="_blank" >Download Partner Toolkit</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Pill content -->
                            <div id="diversity" class="navtab-content">
                                <div class="columns is-vcentered">
                                    <div class="column is-6">
                                        <figure class="image">
                                            <img class="has-light-shadow" src="https://via.placeholder.com/1920x1280" alt="" />
                                        </figure>
                                    </div>
                                    <div class="column is-6">
                                        <div class="inner-content">
                                            <h2 class="feature-headline is-clean">
                                                Diversity Services
                                            </h2>
                                            <p class="body-color">
                                                Lorem ipsum dolor sit amet, vim quidam blandit voluptaria
                                                no, has eu lorem convenire incorrupte.
                                            </p>
                                            <div class="solid-list">
                                                <div class="solid-list-item">
                                                    <div class="list-bullet">
                                                        <i class="sl sl-icon-check"></i>
                                                    </div>
                                                    <div class="list-text body-color">
                                                        Supplier development – help diverse suppliers improve
                                                        and thrive within your organization
                                                    </div>
                                                </div>
                                                <div class="solid-list-item">
                                                    <div class="list-bullet">
                                                        <i class="sl sl-icon-check"></i>
                                                    </div>
                                                    <div class="list-text body-color">
                                                        Automated feedback and clear ROI on development
                                                        programs
                                                    </div>
                                                </div>
                                                <div class="solid-list-item">
                                                    <div class="list-bullet">
                                                        <i class="sl sl-icon-check"></i>
                                                    </div>
                                                    <div class="list-text body-color">
                                                        See how your suppliers perform on diversity (supplier
                                                        diversity, gender inequality, etc.)
                                                    </div>
                                                </div>
                                                <div class="solid-list-item">
                                                    <div class="list-bullet">
                                                        <i class="sl sl-icon-check"></i>
                                                    </div>
                                                    <div class="list-text body-color">
                                                        Customers should mirror your supplier base
                                                    </div>
                                                </div>
                                            </div> --}}
                                            {{--
                                            <div class="button-wrap mt-4">
                                                <a href="kit15-contact.html"
                                                    class="button btn-align btn-more is-link color-primary">
                                                    Learn more about diversity services
                                                    <i class="sl sl-icon-arrow-right"></i>
                                                </a>
                                            </div>
                                            --}}
                                        {{-- </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Pill content -->
                            <div id="business-units" class="navtab-content">
                                <div class="columns is-vcentered">
                                    <div class="column is-6">
                                        <figure class="image">
                                            <img class="has-light-shadow" src="https://via.placeholder.com/1920x1280" alt="" />
                                        </figure>
                                    </div>
                                    <div class="column is-6">
                                        <div class="inner-content">
                                            <h2 class="feature-headline is-clean">Business Units</h2>
                                            <p class="body-color">
                                                Get the best out of the suppliers who help you get your
                                                job done, in any department:
                                            </p>
                                            <div class="solid-list">
                                                <div class="solid-list-item">
                                                    <div class="list-bullet">
                                                        <i class="sl sl-icon-check"></i>
                                                    </div>
                                                    <div class="list-text body-color">
                                                        Marketing and media
                                                    </div>
                                                </div>
                                                <div class="solid-list-item">
                                                    <div class="list-bullet">
                                                        <i class="sl sl-icon-check"></i>
                                                    </div>
                                                    <div class="list-text body-color">
                                                        Legal, Consulting
                                                    </div>
                                                </div>
                                                <div class="solid-list-item">
                                                    <div class="list-bullet">
                                                        <i class="sl sl-icon-check"></i>
                                                    </div>
                                                    <div class="list-text body-color">IT Companies</div>
                                                </div>
                                                <div class="solid-list-item">
                                                    <div class="list-bullet">
                                                        <i class="sl sl-icon-check"></i>
                                                    </div>
                                                    <div class="list-text body-color">
                                                        Energy, Transportation
                                                    </div>
                                                </div>
                                                <div class="solid-list-item">
                                                    <div class="list-bullet">
                                                        <i class="sl sl-icon-check"></i>
                                                    </div>
                                                    <div class="list-text body-color">
                                                        …And whatever else makes your department go
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="button-wrap mt-4">
                                                <a href="kit15-contact.html"
                                                    class="button btn-align btn-more is-link color-primary">
                                                    Have some suppliers to rank?
                                                    <i class="sl sl-icon-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Pill content -->
                            <div id="boards" class="navtab-content">
                                <div class="columns is-vcentered">
                                    <div class="column is-6">
                                        <figure class="image">
                                            <img class="has-light-shadow" src="https://via.placeholder.com/1920x1280" alt="" />
                                        </figure>
                                    </div>
                                    <div class="column is-6">
                                        <div class="inner-content">
                                            <h2 class="feature-headline is-clean">
                                                C-Suite and Boards
                                            </h2>
                                            <p class="body-color">
                                                Get the best out of the suppliers who help you get your
                                                job done, in any department:
                                            </p>
                                            <div class="solid-list">
                                                <div class="solid-list-item">
                                                    <div class="list-bullet">
                                                        <i class="sl sl-icon-check"></i>
                                                    </div>
                                                    <div class="list-text body-color">
                                                        Put your money where your PR mouth is
                                                    </div>
                                                </div>
                                                <div class="solid-list-item">
                                                    <div class="list-bullet">
                                                        <i class="sl sl-icon-check"></i>
                                                    </div>
                                                    <div class="list-text body-color">
                                                        Solid numbers and action for the proof behind your
                                                        goals and public statements
                                                    </div>
                                                </div>
                                                <div class="solid-list-item">
                                                    <div class="list-bullet">
                                                        <i class="sl sl-icon-check"></i>
                                                    </div>
                                                    <div class="list-text body-color">
                                                        Suppliers are a huge budget line item
                                                    </div>
                                                </div>
                                                <div class="solid-list-item">
                                                    <div class="list-bullet">
                                                        <i class="sl sl-icon-check"></i>
                                                    </div>
                                                    <div class="list-text body-color">
                                                        Get the value and return you deserve from suppliers
                                                        and employees
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="button-wrap mt-4">
                                                <a href="kit15-contact.html"
                                                    class="button btn-align btn-more is-link color-primary">
                                                    Talk about specifics for your department
                                                    <i class="sl sl-icon-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Values Section -->
    <div class="section">
        <div class="container">
            <!-- Title -->
            <div class="section-title-wrapper my-6">
                <div class="bg-number">3</div>
                <h2 class="title section-title has-text-centered dark-text text-bold">
                How Do We Business?
                </h2>
                <!--Colored Shapes-->
                <svg class="blob-1" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#837FCB"
                        d="M37.1,-25.2C47.4,-16.7,54.5,-1.3,50,8.6C45.5,18.5,29.3,23,15.4,28.2C1.5,33.5,-10.2,39.5,-22.6,37.3C-35,35,-48,24.5,-51.2,11.4C-54.5,-1.7,-48,-17.4,-37.8,-25.9C-27.7,-34.3,-13.8,-35.6,-0.2,-35.4C13.4,-35.2,26.8,-33.7,37.1,-25.2Z"
                        transform="translate(100 100)" />
                </svg>

                <svg class="blob-2" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#837FCB"
                        d="M48,-10.8C56.2,9.7,52.8,38.8,37.8,49C22.8,59.2,-3.8,50.5,-24.2,35.2C-44.7,19.9,-59,-2,-53.9,-18.2C-48.9,-34.4,-24.4,-45,-2.3,-44.2C19.9,-43.5,39.7,-31.4,48,-10.8Z"
                        transform="translate(100 100)" />
                </svg>


                <svg class="blob-3" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#837FCB"
                        d="M45.3,-16.1C52.1,6.1,46.3,31,31.3,41.5C16.3,52,-8,48.2,-30,33.9C-52.1,19.6,-72,-5.1,-66.6,-25.4C-61.2,-45.7,-30.6,-61.5,-5.7,-59.7C19.3,-57.9,38.5,-38.3,45.3,-16.1Z"
                        transform="translate(100 100)" />
                </svg>


                <svg class="blob-4" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#837FCB"
                        d="M49.2,-14.2C58.8,13.6,58.2,46.5,43.2,56.7C28.2,66.9,-1.2,54.6,-18.6,38.8C-35.9,23.1,-41.1,4,-36,-17.5C-30.9,-39.1,-15.4,-63.1,2.2,-63.8C19.8,-64.5,39.6,-41.9,49.2,-14.2Z"
                        transform="translate(100 100)" />
                </svg>


                <svg class="blob-5" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#837FCB"
                        d="M54.1,-26.7C58.3,-4.9,41.5,14.6,23.8,25.8C6.1,37,-12.6,40,-27.3,30.9C-42.1,21.8,-52.9,0.5,-47.7,-22.7C-42.5,-46,-21.2,-71.2,1.9,-71.8C25,-72.4,50,-48.4,54.1,-26.7Z"
                        transform="translate(100 100)" />
                </svg>
            </div>

            <div class="columns is-vcentered py-6">
                <!--Benefits-->
                <div class="column is-4 is-offset-2">
                    <div class="flex-card benefits-card">
                        <div class="benefits-header">
                            <i class="im im-icon-Flash"></i>
                            <h3 class="title is-4">We Strive to</h3>
                        </div>
                        <div class="benefits-body">
                            <div class="solid-list">
                                <div class="solid-list-item">
                                    <div class="list-bullet">
                                        <i class="sl sl-icon-check"></i>
                                    </div>
                                    <div class="list-text body-color">
                                        Build trust and relationships through transparency and
                                        feedback
                                    </div>
                                </div>
                                <div class="solid-list-item">
                                    <div class="list-bullet">
                                        <i class="sl sl-icon-check"></i>
                                    </div>
                                    <div class="list-text body-color">
                                        Create a level playing field for all suppliers, including
                                        diverse businesses
                                    </div>
                                </div>
                                <div class="solid-list-item">
                                    <div class="list-bullet">
                                        <i class="sl sl-icon-check"></i>
                                    </div>
                                    <div class="list-text body-color">
                                        Weave procurement into the fabric of the company
                                    </div>
                                </div>
                                <div class="solid-list-item">
                                    <div class="list-bullet">
                                        <i class="sl sl-icon-check"></i>
                                    </div>
                                    <div class="list-text body-color">
                                        Foster positive relationships both internally and externally
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Benefits-->
                <div class="column is-4">
                    <div class="flex-card benefits-card">
                        <div class="benefits-header">
                            <i class="im im-icon-Structure"></i>
                            <h3 class="title is-4">We Care About</h3>
                        </div>
                        <div class="benefits-body">
                            <div class="solid-list">
                                <div class="solid-list-item">
                                    <div class="list-bullet">
                                        <i class="sl sl-icon-check"></i>
                                    </div>
                                    <div class="list-text body-color">
                                        Changing the course of business through engagement and
                                        intervention
                                    </div>
                                </div>
                                <div class="solid-list-item">
                                    <div class="list-bullet">
                                        <i class="sl sl-icon-check"></i>
                                    </div>
                                    <div class="list-text body-color">
                                        Ensuring you get the most value out of the dollars you spend
                                        with suppliers
                                    </div>
                                </div>
                                <div class="solid-list-item">
                                    <div class="list-bullet">
                                        <i class="sl sl-icon-check"></i>
                                    </div>
                                    <div class="list-text body-color">
                                        Helping our customers get quick, frequent and visible “wins”
                                    </div>
                                </div>
                                <div class="solid-list-item">
                                    <div class="list-bullet">
                                        <i class="sl sl-icon-check"></i>
                                    </div>
                                    <div class="list-text body-color">
                                        Surfacing critical items that would otherwise fester or never
                                        come out
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CEO Word -->
    <section id="business-types" class="section is-app-grey">
        <div class="container">
            <!-- Title -->
            <div class="section-title-wrapper has-text-centered my-6">
                <div class="bg-number">4</div>
                <h2 class="section-title-landing">A Word From Our CEO</h2>
                <h4>Every business matters, learn how we handle it.</h4>
            </div>

            <!-- Content -->
            <div class="content-wrapper pb-6">
                <div class="columns is-vcentered">
                    <div class="column is-6 is-offset-3">
                        <div class="side-feature-text">
                            {{--
                            <h2 class="feature-headline is-clean is-flex is-align-items-center">
                                <figure class="image is-64x64 mr-4">
                                    <img class="is-rounded" src="https://via.placeholder.com/150x150" alt="" />
                                </figure>
                                <span>A Word From Our CEO</span>
                            </h2>
                            --}}
                            <p class="mt-4">
                                <i class="fa fa-quote-left mr-2 is-size-4 color-primary"></i>
                                Singlebag Partner Program comes with exciting features you’ve never seen before. 
                            </p>
                            <p>
                                Our advanced technology along with a dedicated team lifts the merchants who freshly step into our family. 
                                Our e-commerce platform is a successful forum for earning multiple incomes while excelling in one's own business.
                                <i class="fa fa-quote-right ml-2 is-size-4 color-primary"></i>
                            </p>
                        </div>
                    </div>
                    <!-- Card with icons -->
                    {{--
                    <div class="column is-4 is-offset-1">
                        <div class="flex-card company-types">
                            <div class="icon-group">
                                <img src="{{ asset('assets/img/store-core.svg') }}" alt="" />
                                <span>Commerce & Services</span>
                            </div>
                            <div class="icon-group">
                                <img src="{{ asset('assets/img/bank-core.svg') }}" alt="" />
                                <span>Finance services</span>
                            </div>
                            <div class="icon-group">
                                <img src="{{ asset('assets/img/factory-core.svg') }}" alt="" />
                                <span>Industry</span>
                            </div>
                            <div class="icon-group">
                                <img src="{{ asset('assets/img/church-core.svg') }}" alt="" />
                                <span>Non Profit</span>
                            </div>
                            <div class="icon-group">
                                <img src="{{ asset('assets/img/warehouse-core.svg') }}" alt="" />
                                <span>Distribution</span>
                            </div>
                        </div>
                    </div>
                    --}}
                </div>
            </div>
        </div>
    </section>

    <!--Footer-->
    <!-- Side dark footer -->
    <footer class="footer-light-medium">
        <div class="container">
            <div class="footer-head">
                <div class="head-text">
                    <h3>Ready to get started?</h3>
                    <p>Create your online store for free now.</p>
                </div>
                <div class="head-action">
                    <div class="buttons">
                        <a href="{{ url(env('APP_URL') . '/partner/register') }}" class="button primary-btn raised action-button">Try it free</a>
                        {{-- <a class="button chat-button">Chat with us</a> --}}
                        {{-- <a href="/assets/pdf/partner_toolkit.pdf" class="button warning-btn raised action-button" target="_blank" >Partner Toolkit</a> --}}
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
                    &copy; {{ date("Y") }} | <a href="{{ url(env('APP_URL')) }}">{{ env('APP_NAME') }}</a> | All
                    Rights Reserved.
                </p>
            </div>
        </div>
    </footer>
    <!-- Side dark footer -->
    <!-- Back To Top Button -->
    <div id="backtotop"><a href="#"></a></div>
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
    <script>      

        $('.flip-card').hover(function() {
            $(this).children('div.flip-icon').slideUp().hide();
            $(this).children('div.flip-desc').slideDown().show();
        }, function() {        
            $(this).children('div.flip-desc').slideUp().hide()
            $(this).children('div.flip-icon').slideDown().show();
        });

    </script>
</body>

</html>
