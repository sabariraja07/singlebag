@extends('multibag::layouts.app')
@section('content')
<home-slider :sliders="{{ json_encode($sliders) }}"></home-slider>
{{-- <div class="service-area pt-160 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="service-wrap text-center mb-30">
                    <img class="inject-me" src="assets/images/icon-img/headphones.svg" alt="">
                    <h3>Happiness </h3>
                    <p class="service-peragraph-2">Free Shipping for any product and it's world wide</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="service-wrap text-center mb-30">
                    <img class="inject-me" src="assets/images/icon-img/shipping-car.svg" alt="">
                    <h3>Free Shipping </h3>
                    <p class="service-peragraph-2">Free Shipping for any product and it's world wide</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="service-wrap text-center mb-30">
                    <img class="inject-me" src="assets/images/icon-img/trusty.svg" alt="">
                    <h3>All Trusty Brand </h3>
                    <p class="service-peragraph-2">Free Shipping for any product and it's world wide</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="service-wrap text-center mb-30">
                    <img class="inject-me" src="assets/images/icon-img/support-expart.svg" alt="">
                    <h3>24/7 Support Expert </h3>
                    <p class="service-peragraph-2">Free Shipping for any product and it's world wide</p>
                </div>
            </div>
        </div>
    </div>
</div> --}}
{{-- <div class="service-area pt-60"></div> --}}
@if(count($featured_categories) > 0)
<div class="categorie-area">
    <div class="container-fluid p-0">
        <div class="row g-0">
            @foreach($featured_categories as $category)
            <div class="custom-col-8">
                <div class="single-categories-5 text-center">
                    <div class="single-categories-5-img">
                        <a href="{{ 'category/' . $category['slug'] . '/' . $category['id'] }}">
                            <img style="max-width: 100%;" class="inject-me" src="{{ isset($category['url']) ? $category['url'] : 'uploads/default.png'}}" alt="">
                        </a>
                    </div>
                    <div class="categorie-content-6">
                        <h4>
                            <a class="color-light" href="{{ 'category/' . $category['slug'] . '/' . $category['id'] }}">
                                {{ $category['name'] }}
                            </a>
                        </h4>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
{{-- @if(count($featured_categories) > 0)
<div class="categorie-area pt-80 pb-30">
    <div class="container">
        <div class="section-title-6 mb-65 text-center">
            <h2>{{ __('Featured Categories') }}</h2>
        </div>
        <div class="row g-0">
            @foreach($featured_categories as $category)
            <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                <div class="single-categories-5 text-center">
                    <div class="single-categories-5-img">
                        <a href="{{ 'category/' . $category['slug'] . '/' . $category['id'] }}">
                            <img style="max-width: 100%;" class="inject-me" src="{{ isset($category['preview']) ? $category['preview']['content'] : 'uploads/default.png'}}" alt="">
                        </a>
                    </div>
                    <div class="categorie-content-6">
                        <h4><a href="{{ 'category/' . $category['slug'] . '/' . $category['id'] }}">{{ $category['name'] }}</a></h4>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif --}}
@if(count($banner_adds) > 0)
<banner-ads :banners="{{ json_encode($banner_adds) }}"></banner-ads>
@endif
{{-- @if(count($featured_categories) > 0)
<featured-category-products :categories="{{ json_encode($featured_categories) }}" :popular-products="{{ json_encode($latest_products) }}"></featured-category-products>
@endif --}}
{{-- @if(count($featured_categories) > 0)
<featured-categories :categories="{{ json_encode($featured_categories) }}"></featured-categories>
@endif --}}
@if(count($latest_products) > 0)
<popular-products :products="{{ json_encode($latest_products) }}"></popular-products>
@endif
@if(count($offerable_products) > 0)
<offer-products-list :products="{{ json_encode($offerable_products) }}"></offer-products-list>
@endif
@if(count($bump_adds) > 0)
<bumper-ads :banners="{{ json_encode($bump_adds) }}"></bumper-ads>
@endif
<home-product-tab :top-rated="{{ json_encode($top_rated_products) }}" 
:recently-added="{{ json_encode($latest_products) }}" 
:trending-products="{{ json_encode($trending_products) }}" 
:top-selling="{{ json_encode($best_selling_products) }}"></home-product-tab>

@if(count($brands) > 0)
<brand-section :brands="{{ json_encode($brands) }}"></brand-section>
@endif
{{-- <section class="home-slider position-relative mb-30">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-2 d-none d-lg-flex">
                <home-category :categories="{{ json_encode($categories) }}"></home-category>
            </div>
            <div class="col-lg-9">
                <home-slider :sliders="{{ json_encode($sliders) }}"></home-slider>
            </div>
            <div class="col-lg-2 d-lg-flex categories_show">
                <home-category :categories="{{ json_encode($categories) }}"></home-category>
            </div>
        </div>
    </div>
</section>
@if(count($featured_categories) > 0)
<featured-categories :categories="{{ json_encode($featured_categories) }}"></featured-categories>
@endif
@if(count($banner_adds) > 0)
<banner-ads :banners="{{ json_encode($banner_adds) }}"></banner-ads>
@endif
@if(count($latest_products) > 0)
<popular-products :products="{{ json_encode($latest_products) }}"></popular-products>
@endif
@if(count($bump_adds) > 0)
<banner-ads :banners="{{ json_encode($bump_adds) }}"></banner-ads>
@endif
@if(count($offerable_products) > 0)
<offer-products-list :products="{{ json_encode($offerable_products) }}"></offer-products-list>
@endif
@if(count($best_selling_products) > 0 || count($latest_products) > 0 || count($trending_products) > 0 || count($top_rated_products) > 0)
<div class="section-padding mb-30">
    <div class="container">
        <div class="row">
            @if(count($best_selling_products) > 0)
            <home-column-list-products title="{{ __('Top Selling') }}" :products="{{ json_encode($best_selling_products) }}"></home-column-list-products>
            @endif
            @if(count($trending_products) > 0)
            <home-column-list-products title="{{ __('Trending Products') }}" :products="{{ json_encode($trending_products) }}"></home-column-list-products>
            @endif
            @if(count($latest_products) > 0)
            <home-column-list-products title="{{ __('Recently added') }}" :products="{{ json_encode($latest_products) }}"></home-column-list-products>
            @endif
            @if(count($top_rated_products) > 0)
            <home-column-list-products title="{{ __('Top Rated') }}" :products="{{ json_encode($top_rated_products) }}"></home-column-list-products>
            @endif
        </div>
    </div>
</div>
@endif --}}
@endsection
