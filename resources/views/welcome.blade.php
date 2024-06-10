@extends('main.app')
@section('content')
<style>
    .price-in-days{
        content: attr(data-price-days);
        font-size: .8rem;
        font-weight: 400;
        color: #999;
        text-align: left;
        text-transform: uppercase;
        max-width: 50px;
        line-height: 1.2;
    }
    .client-thumb {
        text-align: center;
    }
    .price-collapse {
        display: none;
    }
    .price-collapse.in {
        display: block;
    }
    .price-accordion {
        color: #008060;
        cursor: pointer;
    }
    @media only screen and (max-width: 600px) {
        #main-hero h1.title, #main-hero h2.subtitle, #main-hero .field-wrap span{
            color: #ffffff;
        }
    }
    .section.is-medium{
        padding:5rem 4.5rem !important;
    }
    .subtitle.cmb-25, .title.cmb-25{
        margin-bottom: 2.5rem !important;
    }
</style>
<div class="hero type-1 parallax is-cover is-relative is-fullheight"
    data-background="{{ asset($header->preview) }}" data-color="#837FCB" data-color-opacity=".3"
    data-page-theme="green">
    <img class="hero-shape-commerce" src="{{ asset('assets/img/cut-shape-v1.svg') }}" alt="" />
    <!-- Hero caption -->
    <div id="main-hero" class="hero-body">
        <div class="container">
            <div class="columns is-vcentered is-hero-caption">
                <div class="column is-5 is-offset-7">
                    <h2 class="subtitle cmb-25 is-5 is-dark">{{ $header->highlight_title ?? '' }}</h2>
                    <h1 class="title cmb-25 is-1 is-dark">
                    {{ $header->title ?? '' }}
                    </h1>
                    <h2 class="subtitle is-5 is-dark">
                    {!! $header->description ?? '' !!}
                    </h2>
                    <div class="field-wrap">
                        <div class="field is-grouped">
                            <!-- <div class="control">
                                <input class="input large-input" type="text" placeholder="Enter your email address" />
                            </div> -->
                            <div class="control">
                                <a href="{{ route('seller.signup') }}" class="button button-cta primary-btn raised">{{ __('Get Started Now') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section is-medium">
    <div class="container">
        <!--Section title-->
        <div class="section-title has-text-centered">
            <div class="title-wrap">
                <h3 class="title title-alt is-3">Made For Every Business</h3>
                <p class="subtitle is-5">
                Make money online cost-effective with smart ideas.
                </p>
            </div>
        </div>

        <!--Business Types-->
        <div class="business-types">
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
            @foreach($features as $key => $row)
            <div class="business-type">
                <div class="inner">
                    <div class="inner-icon">
                        <img src="{{ asset($row->preview->content ?? '') }}" alt="" />
                    </div>
                    <span class="inner-text">{{ $row->name }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!--Extra Benefit-->
        <div class="extra-word">
            <!-- <div class="avatar-wrap">
                <img src="https://via.placeholder.com/250x250" alt="" />
            </div> -->
            <div class="text">
                <p>
                    <i class="fa fa-quote-left"></i>We created this solution to make it
                    easy for everyone to create an online store in a matter of minutes. No
                    coding knowledge required. Just hop in and we'll guide you through.<i class="fa fa-quote-right"></i>
                </p>
                <!-- <span>Hellen Miller</span>
                <span>Head of Product Quality</span> -->
            </div>
        </div>
    </div>
</div>

<!--Themes section-->
<div class="section is-medium is-grey">
    <div class="container">
        <!--Title-->
        <div class="section-title is-flex">
            <div class="title-wrap">
                <h3 class="title title-alt is-3">Start with a Theme</h3>
                <p class="subtitle is-6">
                Pick a template and customise your online store.
                </p>
            </div>
            <!-- <div class="actions">
                <div class="link-wrap">
                    <a>View more Themes</a>
                </div>
            </div> -->
        </div>

        <!--Themes-->
        <div class="columns is-multiline theme-list">
            {{-- @foreach($templates as $template)
            <div class="column is-4">
                <a><img class="theme-preview" src="{{ asset($template->asset_path.'/screenshot.png') }}" alt="" /></a>
            </div>
            @endforeach --}}
            @for ($index = 0; $index < 7; $index++)
            <div class="column is-4">
                <a><img class="theme-preview" src="{{ asset('/frontend/Theme0'. ($index + 1) . '.png') }}" alt="" /></a>
            </div>
            @endfor
        </div>

        <div class="columns is-vcentered theme-features">
            <div class="column is-4">
                <!-- Side icon box -->
                <div class="theme-feature">
                    <div class="feature-icon">
                        <i class="im im-icon-Building is-size-2 color-primary"></i>
                    </div>
                    <div class="dark-text has-text-left ml-30">
                        <h5 class="text-bold">Get Started</h5>
                        <p>Take your Business online in a minute.</p>
                    </div>
                </div>
            </div>
            <div class="column is-4">
                <!-- Side icon box -->
                <div class="theme-feature">
                    <div class="feature-icon">
                        <i class="im im-icon-Clothing-Store is-size-2 color-primary"></i>
                    </div>
                    <div class="dark-text has-text-left ml-30">
                        <h5 class="text-bold">Pick a Template</h5>
                        <p>Choose your website template relevant to your online store.</p>
                    </div>
                </div>
            </div>
            <div class="column is-4">
                <!-- Side icon box -->
                <div class="theme-feature">
                    <div class="feature-icon">
                        <i class="im im-icon-Gear is-size-2 color-primary"></i>
                    </div>
                    <div class="dark-text has-text-left ml-30">
                        <h5 class="text-bold">Customize</h5>
                        <p>
                            Complete your eCommerce store with customizable designs, 
                            features and built-in tools to help your ideas. 

                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-cta has-text-centered">
            <a href="{{ route('seller.signup') }}" class="button button-cta primary-btn raised">Start my Free Trial</a>
            <p>No credit card required.</p>
        </div>
    </div>
</div>

<div id="main-features" class="section is-medium">
    <div class="container">
        <!--Side feature-->
        @for ($index = 0; $index < 6; $index++)
        @if(!isset($market_features->{'ecom_title_'.($index + 1) }))
            @continue
        @endif
        @if($index % 2 == 0)
        <div class="columns is-vcentered is-side-feature">
            <div class="column is-5 is-offset-1 text-column">
                <h3 class="title is-3">{{ $market_features->{'ecom_title_'.($index + 1) } ?? '' }}</h3>
                <p>{{ $market_features->{'ecom_description_'.($index + 1) } ?? '' }}</p>
            </div>
            <div class="column is-6 media-column">
                <img class="pattern is-right" src="{{ asset($market_features->{'ecom_img_'.($index + 1)} ?? '') }}" alt="" />
                <img class="mockup" src="{{ asset($market_features->{'ecom_img_'.($index + 1) } ?? '') }}" alt="" />
            </div>
        </div>
        @else
        <div class="columns is-vcentered is-side-feature">
            <div class="column is-6 media-column is-hidden-mobile">
                <img class="pattern is-right" src="{{ asset($market_features->{'ecom_img_'.($index + 1) } ?? '') }}" alt="" />
                <img class="mockup" src="{{ asset($market_features->{'ecom_img_'.($index + 1) } ?? '') }}" alt="" />
            </div>
            <div class="column is-5 text-column is-offset-1">
                <h3 class="title is-3">{{ $market_features->{'ecom_title_'.($index + 1) } ?? '' }}</h3>
                <p>{{ $market_features->{'ecom_description_'.($index + 1) } ?? '' }}</p>
            </div>
            <div class="column is-6 media-column is-hidden-desktop">
                <img class="pattern is-right" src="{{ asset($market_features->{'ecom_img_'.($index + 1) } ?? '') }}" alt="" />
                <img class="mockup" src="{{ asset($market_features->{'ecom_img_'.($index + 1) } ?? '') }}" alt="" />
            </div>
        </div>
        @endif
        @endfor 

        {{-- <div class="columns is-vcentered is-side-feature">
            <div class="column is-5 is-offset-1 text-column">
                <h3 class="title is-3">{{ $market_features->ecom_title_1 ?? '' }}</h3>
                <p>{{ $market_features->ecom_description_1 ?? '' }}</p>
            </div>
            <div class="column is-6 media-column">
                <img class="pattern is-right" src="{{ asset($market_features->ecom_img_1 ?? '') }}" alt="" />
                <img class="mockup" src="{{ asset($market_features->ecom_img_1 ?? '') }}" alt="" />
            </div>
        </div>
        <div class="columns is-vcentered is-side-feature">
            <div class="column is-6 media-column is-hidden-mobile">
                <img class="pattern is-right" src="{{ asset($market_features->ecom_img_2 ?? '') }}" alt="" />
                <img class="mockup" src="{{ asset($market_features->ecom_img_2 ?? '') }}" alt="" />
            </div>
            <div class="column is-5 text-column is-offset-1">
                <h3 class="title is-3">{{ $market_features->ecom_title_2 ?? '' }}</h3>
                <p>{{ $market_features->ecom_description_2 ?? '' }}</p>
            </div>
            <div class="column is-6 media-column is-hidden-desktop">
                <img class="pattern is-right" src="{{ asset($market_features->ecom_img_2 ?? '') }}" alt="" />
                <img class="mockup" src="{{ asset($market_features->ecom_img_2 ?? '') }}" alt="" />
            </div>
        </div>
        <div class="columns is-vcentered is-side-feature">
            <div class="column is-5 is-offset-1 text-column">
                <h3 class="title is-3">{{ $market_features->ecom_title_3 ?? '' }}</h3>
                <p>{{ $market_features->ecom_description_3 ?? '' }}</p>
            </div>
            <div class="column is-6 media-column">
                <img class="pattern is-right" src="{{ asset($market_features->ecom_img_3 ?? '') }}" alt="" />
                <img class="mockup" src="{{ asset($market_features->ecom_img_3 ?? '') }}" alt="" />
            </div>
        </div> --}}
        <!--CTA-->
        {{--
        <div class="section-cta has-text-centered">
            <a href="{{ route('seller.signup') }}" class="button button-cta primary-btn">Start my Free Trial</a>
            <p>No credit card required.</p>
        </div>
        --}}
    </div>
</div>

<div class="section is-medium is-grey">
    <div class="container">
        <div class="section-title has-text-centered">
            <div class="title-wrap">
                <h3 class="title title-alt is-3">Manage Everything</h3>
                <p class="subtitle is-6">
                The best eCommerce platform to manage your inventory, orders and deliveries in a single console.
                </p>
            </div>
        </div>
        <!--Mockup-->
        <div class="mockup-wrap">
            <div class="image-wrap">
                <img src="{{ asset('assets/img/app-ecommerce-1-core.png') }}" alt="" />

                <div class="columns is-multiline app-features">
                    <div class="column is-6">
                        <!-- Side icon box -->
                        <div class="content content-flex">
                            <div class="dark-text">
                                <i class="im im-icon-Shop-3 is-size-2 color-primary"></i>
                            </div>
                            <div class="dark-text has-text-left ml-30">
                                <h5 class="text-bold">Track Sales</h5>
                                <p>Keeps records and details of all aspects of your sales process.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <!-- Side icon box -->
                        <div class="content content-flex">
                            <div class="dark-text">
                                <i class="im im-icon-Credit-Card2 is-size-2 color-primary"></i>
                            </div>
                            <div class="dark-text has-text-left ml-30">
                                <h5 class="text-bold">Track Payments</h5>
                                <p>Keep track of the payment your customers owe you by generating 
                                    Invoice History and Customer Balance reports.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <!-- Side icon box -->
                        <div class="content content-flex">
                            <div class="dark-text">
                                <i class="im im-icon-Air-Balloon is-size-2 color-primary"></i>
                            </div>
                            <div class="dark-text has-text-left ml-30">
                                <h5 class="text-bold">Manage Deliveries</h5>
                                <p>Find addresses and guide drivers to save time & easily 
                                    assign tasks to delivery agents from the backend.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <!-- Side icon box -->
                        <div class="content content-flex">
                            <div class="dark-text">
                                <i class="im im-icon-Life-Safer is-size-2 color-primary"></i>
                            </div>
                            <div class="dark-text has-text-left ml-30">
                                <h5 class="text-bold">Built-in Support</h5>
                                <p>
                                Our personal customer service program for any general 
                                questions you may have or technical support you need.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                {{--
                <div class="section-cta has-text-centered">
                    <a href="{{ route('seller.signup') }}" class="button button-cta primary-btn">Start my Free Trial</a>
                    <p>No credit card required.</p>
                </div>
                --}}
            </div>
        </div>
    </div>
</div>

<div class="section is-medium">
    <div class="container">
        <div class="section-title has-text-centered">
            <div class="title-wrap">
                <h3 class="title title-alt is-3">{{ __('review_title') }}</h3>
                <p class="subtitle is-6">
                {{ __('review_description') }}
                </p>
            </div>
        </div>


        <!--Testimonials-->
        <div class="customer-testimonials-wrapper">
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
            <div class="customer-testimonials">
                <!--Testimonial item-->
                @foreach($testimonials as $key => $row)
                <div class="testimonial-item">
                    <div class="item-inner">
                        <div class="left">
                            <!-- <div class="rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div> -->
                            <p>
                                <i class="fa fa-quote-left"></i>
                                {{ $row->excerpt->content ?? ""}}
                                <i class="fa fa-quote-right"></i>
                            </p>
                            <div class="meta">
                                <img src="{{ $row->preview ? asset($row->preview->content) : 'https://ui-avatars.com/api/?name=' .$row->name ?? '' .'&background=random&length=1&color=#fff'}}" alt="" />
                                <div class="inner-meta">
                                    <span>{{ $row->name ?? '' }}</span>
                                    <span>{{ $row->slug ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="right">
                            <img src="{{ asset('assets/img/man-' . rand(1, 2) . '-body.png') }}" alt="" />
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .bg-white {
        background-color: #fff!important;
    }
    .p-3 {
        padding: 1rem!important;
    }
    .ml-3, .mx-3 {
        margin-left: 1rem!important;
    }
    .rounded-lg {
        border-radius: 0.3rem!important;
    }
    .portfolio__slide{
        margin-top: 40px;
    }
    .shop_feature_title {
        margin-bottom: 0.5rem;
        font-weight: 500;
        line-height: 1.2;
        font-weight: 500;
        font-size: 1.2rem;
        color: #444f60;
        text-align: center;
    }
    .price-accordion i.fa{
        margin-right: 5px !important;
    }
</style>

<div class="section is-medium is-grey">
    <div class="container">
    <div class="section-title has-text-centered">
            <div class="title-wrap">
                <h3 class="title title-alt is-3">Your Own Online Store Is Just A Few Clicks Away!</h3>
                <p class="subtitle is-6">
                Manage your full system in a single dashboard and attract your customers with {{ env('APP_NAME') }}.
                </p>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-12 ">
                <div class="" id="gallery">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="portfolio__slide shop_gallery_part">
                                    @foreach($store_features as $key => $row)
                                    <div class="bg-white p-3 ml-3 rounded-lg">
                                        <div class="portfolio__img">
                                            <img src="{{ asset($row->preview->content) }}" alt="">
                                        </div>
                                        <div class="pt-3">
                                            <h5 class="shop_feature_title">{{ $row->name }}</h5>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="pricing" class="section is-medium">
    <div class="container">
        <div class="section-title has-text-centered">
            <div class="title-wrap">
                <h3 class="title title-alt is-3">{{ __('Pricing') }}</h3>
                <p class="subtitle is-6">
                {{ __('pricing_description') }}
                </p>
            </div>
        </div>

        <div class="pricing-wrapper">
            <div class="columns">
                @foreach($plans as $row)
                @php
                $plan=json_decode($row->data);
                @endphp
                <div class="column is-4">
                    <div class="pricing-box" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);">
                        <img src="{{ $plan->image ?? asset('assets/img/ecommerce-pricing-icon-1-core.svg') }}" alt="" />
                        <h3>{{ $row->name }}</h3>
                        <p>{{ $row->description }}</p>
                        <div class="price">
                            <div class="price-number">
                                <span>
                                    {{ amount_format($row->price) }}
                                </span>
                                <span class="price-in-days">
                                    <span style="display: inline-flex;font-size: 1.4em;">/</span>
                                    <span>@if($row->days == 365){{ __('Yearly') }}@elseif($row->days == 30){{ __('Monthly') }}@else {{ $row->days }} {{ __('Days') }}@endif</span>
                                </span>
                            </div>
                        </div>
                        <span class="price-accordion" role="show" data-child="price-collapse-{{$row->id}}">
                            <i class="fa fa-plus"></i> Show plan features
                        </span>
                        <div style="margin-bottom: 20px;width:100%;"></div>
                        <ul class="list-unstyled mb-5 mt-3 text-small text-left font-weight-normal price-collapse" id="price-collapse-{{$row->id}}">
                            <li class="mb-3">
                                {{ __('Products Limit') }} :
                                @if($plan->product_limit == -1)
                                <b>{{ __('Unlimited') }}</b>
                                @else
                                <b>{{ $plan->product_limit }}</b>
                                @endif
                            </li>
                            <li class="mb-3">
                                {{ __('Storage Limit') }} :
                                <b></b>
                                @if($plan->storage == -1)
                                <b>{{ __('Unlimited') }}</b>
                                @else
                                <b>{{ human_filesize($plan->storage) }}</b>
                                @endif
                            </li>
                            <li class="mb-3">
                                {{ __('Use Subdomain') }}
                            </li>
                            <li class="mb-3">
                                @if($plan->custom_domain == 'true')
                                {{ __('Use your existing domain') }}
                                @else
                                <del>{{ __('Use your existing domain') }}</del>
                                @endif
                            </li>

                            <li class="mb-3">
                                @if($plan->inventory == 'true')
                                {{ __('Inventory Management') }}
                                @else
                                <del> {{ __('Inventory Management') }}</del>
                                @endif
                            </li>
                            <li class="mb-3">
                                @if($plan->pos == 'true')
                                {{ __('POS System') }}
                                @else
                                <del>{{ __('POS System') }}</del>
                                @endif
                            </li>
                            <li class="mb-3">
                                @if($plan->customer_panel == 'true')
                                {{ __('Customer Panel') }}
                                @else
                                <del>{{ __('Customer Panel') }}</del>
                                @endif
                            </li>
                            <li class="mb-3">
                                @if($plan->google_analytics == 'true')
                                {{ __('Google Analytics') }}
                                @else
                                <del>{{ __('Google Analytics') }}</del>
                                @endif
                            </li>
                            <li class="mb-3">
                                @if($plan->gtm == 'true')
                                {{ __('Google Tag Manager (GTM)') }}
                                @else
                                <del>{{ __('Google Tag Manager (GTM)') }}</del>
                                @endif

                            </li>
                            <li class="mb-3">
                                @if($plan->facebook_pixel == 'true')
                                {{ __('Facebook Pixel') }}
                                @else
                                <del>{{ __('Facebook Pixel') }}</del>
                                @endif
                            </li>

                            <li class="mb-3">
                                @if($plan->whatsapp == 'true')
                                {{ __('Whatsapp Api') }}
                                @else
                                <del> {{ __('Whatsapp Api') }}</del>
                                @endif
                            </li>

                            <li class="mb-3">
                                @if($plan->qr_code == 'true')
                                {{ __('QR Code') }}
                                @else
                                <del> {{ __('QR Code') }}</del>
                                @endif
                            </li>
                            <li class="mb-3">
                                @if($plan->live_support == 'true')
                                {{ __('Technical Support') }}
                                @else
                                <del> {{ __('Technical Support') }}</del>
                                @endif
                            </li>

                            <li class="mb-3">
                                {{ __('Multi Language') }}
                            </li>
                            <li class="mb-3">
                                {{ __('Image Optimization') }}
                            </li>
                        </ul>
                        {{--
                        @if($row->is_trial == 1)
                        <a href="{{ route('seller.signup') }}" class="button primary-btn raised is-fullwidth">Try it free</a>
                        @else
                        <a href="{{ route('seller.signup') }}" class="button primary-btn raised is-fullwidth">Try it now</a>
                        @endif
                        --}}
                    </div>
                </div>
                @endforeach
            </div>
            <div class="columns is-vcentered is-side-feature" style="margin-top: 100px;">
                <div class="column is-7 is-offset-1 text-column">
                    <h3 class="title is-3">Create Your Online Store For Free Now.</h3>
                    <p>No credit card required.</p>
                </div>
                @foreach($trail_plans as $row)
                @php
                $plan=json_decode($row->data);
                @endphp
                <div class="column is-4 media-column">
                    <div class="pricing-box" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);">
                        <img src="{{ $plan->image ?? asset('assets/img/ecommerce-pricing-icon-1-core.svg') }}" alt="" />
                        <h3>{{ $row->name }}</h3>
                        <p>{{ $row->description }}</p>
                        <div class="price">
                            <div class="price-number">
                                <span>
                                    {{ amount_format($row->price) }}
                                </span>
                                <span class="price-in-days">
                                    <span style="display: inline-flex;font-size: 1.4em;">/</span>
                                    <span>@if($row->days == 365){{ __('Yearly') }}@elseif($row->days == 30){{ __('Monthly') }}@else {{ $row->days }} {{ __('Days') }}@endif</span>
                                </span>
                            </div>
                        </div>
                        <span class="price-accordion" role="show" data-child="price-collapse-{{$row->id}}">
                            <i class="fa fa-plus"></i> Show plan features
                        </span>
                        <div style="margin-bottom: 20px;width:100%;"></div>
                        <ul class="list-unstyled mb-5 mt-3 text-small text-left font-weight-normal price-collapse" id="price-collapse-{{$row->id}}">
                            <li class="mb-3">
                                {{ __('Products Limit') }} :
                                @if($plan->product_limit == -1)
                                <b>{{ __('Unlimited') }}</b>
                                @else
                                <b>{{ $plan->product_limit }}</b>
                                @endif
                            </li>
                            <li class="mb-3">
                                {{ __('Storage Limit') }} :
                                <b></b>
                                @if($plan->storage == -1)
                                <b>{{ __('Unlimited') }}</b>
                                @else
                                <b>{{ human_filesize($plan->storage) }}</b>
                                @endif
                            </li>
                            <li class="mb-3">
                                {{ __('Use Subdomain') }}
                            </li>
                            <li class="mb-3">
                                @if($plan->custom_domain == 'true')
                                {{ __('Use your existing domain') }}
                                @else
                                <del>{{ __('Use your existing domain') }}</del>
                                @endif
                            </li>

                            <li class="mb-3">
                                @if($plan->inventory == 'true')
                                {{ __('Inventory Management') }}
                                @else
                                <del> {{ __('Inventory Management') }}</del>
                                @endif
                            </li>
                            <li class="mb-3">
                                @if($plan->pos == 'true')
                                {{ __('POS System') }}
                                @else
                                <del>{{ __('POS System') }}</del>
                                @endif
                            </li>
                            <li class="mb-3">
                                @if($plan->customer_panel == 'true')
                                {{ __('Customer Panel') }}
                                @else
                                <del>{{ __('Customer Panel') }}</del>
                                @endif
                            </li>
                            <li class="mb-3">
                                @if($plan->google_analytics == 'true')
                                {{ __('Google Analytics') }}
                                @else
                                <del>{{ __('Google Analytics') }}</del>
                                @endif
                            </li>
                            <li class="mb-3">
                                @if($plan->gtm == 'true')
                                {{ __('Google Tag Manager (GTM)') }}
                                @else
                                <del>{{ __('Google Tag Manager (GTM)') }}</del>
                                @endif

                            </li>
                            <li class="mb-3">
                                @if($plan->facebook_pixel == 'true')
                                {{ __('Facebook Pixel') }}
                                @else
                                <del>{{ __('Facebook Pixel') }}</del>
                                @endif
                            </li>

                            <li class="mb-3">
                                @if($plan->whatsapp == 'true')
                                {{ __('Whatsapp Api') }}
                                @else
                                <del> {{ __('Whatsapp Api') }}</del>
                                @endif
                            </li>

                            <li class="mb-3">
                                @if($plan->qr_code == 'true')
                                {{ __('QR Code') }}
                                @else
                                <del> {{ __('QR Code') }}</del>
                                @endif
                            </li>
                            <li class="mb-3">
                                @if($plan->live_support == 'true')
                                {{ __('Technical Support') }}
                                @else
                                <del> {{ __('Technical Support') }}</del>
                                @endif
                            </li>

                            <li class="mb-3">
                                {{ __('Multi Language') }}
                            </li>
                            <li class="mb-3">
                                {{ __('Image Optimization') }}
                            </li>
                        </ul>
                        @if($row->is_trial == 1)
                        <a href="{{ route('seller.signup') }}" class="button primary-btn raised is-fullwidth">Try it free</a>
                        @else
                        <a href="{{ route('seller.signup') }}" class="button primary-btn raised is-fullwidth">Try it now</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
<!--Suppport Section-->
<div class="section-wrap">
    <div class="section is-medium image-section">
        <div class="container">
            <div class="section-title">
                <div class="title-wrap">
                    <h3 class="title title-alt is-3">We're here to help!</h3>
                </div>
            </div>

            <div class="support-features">
                <p>
                Follow few easy steps to build your own eCommerce website and start selling online today!
                </p>

                <div class="columns is-multiline">
                    <div class="column is-6">
                        <a class="support-card">
                            <i class="{{ $about_1->preview }}"></i>
                            <h3>{{ $about_1->title }}</h3>
                            <p>{{ $about_1->description }}</p>
                        </a>
                    </div>
                    <div class="column is-6">
                        <a class="support-card">
                            <i class="{{ $about_2->preview }}"></i>
                            <h3>{{ $about_2->title }}</h3>
                            <p>{{ $about_2->description }}</p>
                        </a>
                    </div>
                    <div class="column is-6">
                        <a class="support-card">
                            <i class="{{ $about_3->preview }}"></i>
                            <h3>{{ $about_3->title }}</h3>
                            <p>{{ $about_3->description }}</p>
                        </a>
                    </div>
                    <div class="column is-6">
                        <a class="support-card">
                            <i class="{{ $about_4->preview }}"></i>
                            <h3>{{ $about_4->title }}</h3>
                            <p>{{ $about_4->description }}</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="right has-background-image" data-background="{{ asset('assets/img/help_banner1.jpeg') }}"></div>
</div>

<div class="section is-medium is-grey">
    <div class="container">
    <div class="section-title has-text-centered">
            <div class="title-wrap">
                <h3 class="title title-alt is-3">{{ __('brand_area_title') }}</h3>
            </div>
        </div>
        <div class="row align-items-center" style="margin-top: 70px;">
            <div class="col-lg-12 ">
                <div class="" id="gallery">
                    <div class="container">
                        <div class="row clients-logo">
                            @foreach($brands as $row)
                            <div class="col-lg-2">
                                <div class="client-thumb">
                                    <a href="{{ $row->name }}" target="_blank"> 
                                        <img src="{{ asset($row->preview->content) }}"
                                            height="50" alt="" class="img-fluid">
                                        </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <style>
    .pricing-box:hover{
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2) !important;
    }
    .slide-arrow.prev-arrow, .slide-arrow.next-arrow{
        outline: none;
        text-decoration: none;
        border: 0 none;
        display: block;
        width: 46px;
        height: 46px;
        background-color: #66676b;
        opacity: 1;
        -webkit-transition: all .3s;
        transition: all .3s;
        border-radius: 50%;
        text-align: center;
        font-size: 26px;
    }
</style> -->
@endsection

@push('js')
<script>
    (function($){
        $('.shop_gallery_part').slick({
            dots: false,
            infinite: true,
            autoplay: true,
            arrows: false,
            speed: 2000,
            prevArrow: '<i class="fas fa-long-arrow-alt-right dandik"></i>',
            nextArrow: '<i class="fas fa-long-arrow-alt-left bamdik"></i>',
            slidesToShow: 4,
            slidesToScroll: 4,
            responsive: [
            {
                breakpoint: 1199,
                settings: {
                slidesToShow: 4,
                slidesToScroll: 4,
                }
            },
            {
                breakpoint: 991,
                settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                }
            },
            {
                breakpoint: 767,
                settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
                }
            },
            {
                breakpoint: 576,
                settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                
                }
            }
            ]
        });
        $('.brand_part').slick({
            dots: false,
            infinite: true,
            autoplay: true,
            arrows: false,
            speed: 2000,
            prevArrow: '<i class="fas fa-long-arrow-alt-right dandik"></i>',
            nextArrow: '<i class="fas fa-long-arrow-alt-left bamdik"></i>',
            slidesToShow: 4,
            slidesToScroll: 4
        });
        // $('#pricing-columns').slick({
        //     dots: true,
        //     infinite: false,
        //     autoplay: false,
        //     arrows: true,
        //     speed: 2000,
        //     prevArrow: '<button class="slide-arrow prev-arrow"></button>',
        //     nextArrow: '<button class="slide-arrow next-arrow"></button>',
        //     // prevArrow: '<i class="fa fa-long-arrow-left"></i>',
        //     // nextArrow: '<i class="fa fa-long-arrow-left"></i>',
        //     slidesToShow: 3,
        //     slidesToScroll: 1
        // });
        $('.clients-logo').slick({
		infinite: true,
		arrows: false,
		autoplay: true,
		slidesToShow: 6,
		slidesToScroll: 6,
		autoplaySpeed: 6000,
		responsive: [{
				breakpoint: 1024,
				settings: {
					slidesToShow: 6,
					slidesToScroll: 6,
					infinite: true,
					dots: true
				}
			},
			{
				breakpoint: 900,
				settings: {
					slidesToShow: 4,
					slidesToScroll: 4
				}
			}, {
				breakpoint: 600,
				settings: {
					slidesToShow: 4,
					slidesToScroll: 4
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2
				}
			}
		]
	});
    $('.price-accordion').on('click', function () {
        console.log("dsdsfsd");
        if ($(this).attr('role').toLowerCase() === 'show') {
            $(this).attr('role', 'hide').html('<i class="fa fa-minus"></i> Hide plan features');
            $('#' + $(this).attr('data-child')).addClass('in').slideDown();
        } else if ($(this).attr('role').toLowerCase() === 'hide') {
            $(this).attr('role', 'show').html('<i class="fa fa-plus"></i> Show plan features');
            $('#' + $(this).attr('data-child')).removeClass('in').slideUp();
        }
    });
    })(jQuery);
</script>
@endpush