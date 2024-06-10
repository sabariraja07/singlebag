@extends('fashionbag::layouts.app')
@section('content')
<home-slider :sliders="{{ json_encode($sliders) }}"></home-slider>
 <!-- Feature Section Start -->
 {{-- <div class="section section-margin">
    <div class="container">
        <div class="feature-wrap">
            <div class="row row-cols-lg-4 row-cols-xl-auto row-cols-sm-2 row-cols-1 justify-content-between mb-n5">
                <!-- Feature Start -->
                <div class="col mb-5" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature">
                        <div class="icon text-primary align-self-center">
                            <img src="assets/images/icons/feature-icon-2.png" alt="Feature Icon">
                        </div>
                        <div class="content">
                            <h5 class="title">Free Shipping</h5>
                            <p>Free shipping on all order</p>
                        </div>
                    </div>
                </div>
                <!-- Feature End -->
                <!-- Feature Start -->
                <div class="col mb-5" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature">
                        <div class="icon text-primary align-self-center">
                            <img src="assets/images/icons/feature-icon-3.png" alt="Feature Icon">
                        </div>
                        <div class="content">
                            <h5 class="title">Support 24/7</h5>
                            <p>Support 24 hours a day</p>
                        </div>
                    </div>
                </div>
                <!-- Feature End -->
                <!-- Feature Start -->
                <div class="col mb-5" data-aos="fade-up" data-aos-delay="700">
                    <div class="feature">
                        <div class="icon text-primary align-self-center">
                            <img src="assets/images/icons/feature-icon-4.png" alt="Feature Icon">
                        </div>
                        <div class="content">
                            <h5 class="title">Money Return</h5>
                            <p>Back guarantee under 5 days</p>
                        </div>
                    </div>
                </div>
                <!-- Feature End -->
                <!-- Feature Start -->
                <div class="col mb-5" data-aos="fade-up" data-aos-delay="900">
                    <div class="feature">
                        <div class="icon text-primary align-self-center">
                            <img src="assets/images/icons/feature-icon-1.png" alt="Feature Icon">
                        </div>
                        <div class="content">
                            <h5 class="title">Order Discount</h5>
                            <p>Onevery order over $150</p>
                        </div>
                    </div>
                </div>
                <!-- Feature End -->
            </div>
        </div>
    </div>
</div> --}}
<!-- Feature Section End -->
{{-- @if(count($featured_categories) > 0)
<featured-categories :categories="{{ json_encode($featured_categories) }}"></featured-categories>
@endif --}}
@if(count($banner_adds) > 0)
<banner-ads :banners="{{ json_encode($banner_adds) }}"></banner-ads>
@endif
@if(count($featured_categories) > 0)
<home-category-section-tab :categories="{{ json_encode($featured_categories) }}"></home-category-section-tab>
@endif
@if(count($latest_products) > 0)
<popular-products :products="{{ json_encode($latest_products) }}"></popular-products>
@endif
@if(count($offerable_products) > 0)
<offer-products-list :products="{{ json_encode($offerable_products) }}"></offer-products-list>
@endif

@if(count($bump_adds) > 0)
<banner-ads :banners="{{ json_encode($bump_adds) }}"></banner-ads>
@endif
@if(count($best_selling_products) > 0 || count($latest_products) > 0 || count($trending_products) > 0 || count($top_rated_products) > 0)
@if(count($top_rated_products) > 0)
<div class="section section-padding">
    <div class="container">
        <div class="row mb-n8">
            @php
            $count =1 ;
            @endphp          
            {{-- @if(count($best_selling_products) > 0) --}}
            <home-column-list-products title="{{ __('Top Selling') }}" :count="{{ $count }}"  :products="{{ json_encode($best_selling_products) }}"></home-column-list-products>
            {{-- @endif --}}
            {{-- @if(count($trending_products) > 0) --}}
            <home-column-list-products title="{{ __('Trending Products') }}" :count="{{ $count }}"  :products="{{ json_encode($trending_products) }}"></home-column-list-products>
            {{-- @endif --}}
            {{-- @if(count($latest_products) > 0) --}}
            <home-column-list-products title="{{ __('Recently Added') }}" :count="{{ $count }}"  :products="{{ json_encode($latest_products) }}"></home-column-list-products>
            {{-- @endif --}}
            {{-- @if(count($top_rated_products) > 0) --}}
            <home-column-list-products title="{{ __('Top Rated') }}" :count="{{ $count }}"  :products="{{ json_encode($top_rated_products) }}"></home-column-list-products>
            {{-- @endif --}}
        </div>
    </div>
</div>
 @else
<div class="section section-padding">
    <div class="container">
        <div class="row mb-n8">
            @php
            $count =0 ;
            @endphp 
            <home-column-list-products title="{{ __('Top Selling') }}" :count="{{ $count }}"  :products="{{ json_encode($best_selling_products) }}"></home-column-list-products>
         
            <home-column-list-products title="{{ __('Trending Products') }}" :count="{{ $count }}"  :products="{{ json_encode($trending_products) }}"></home-column-list-products>
        
            <home-column-list-products title="{{ __('Recently Added') }}" :count="{{ $count }}"  :products="{{ json_encode($latest_products) }}"></home-column-list-products>
            
        </div>
    </div>
</div>
@endif 
@endif
@if(count($brands) > 0)
<brand-section :brands="{{ json_encode($brands) }}"></brand-section>
@endif
{{-- <section class="section section-padding"></section> --}}
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