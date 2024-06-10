@extends('singlebag::layouts.app')
@section('content')
<section class="home-slider position-relative mb-30">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-2 d-none d-lg-flex" style="overflow: hidden !important;">
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
@endif
@endsection
@push('js')
<!-- <script src="{{ asset('frontend/singlebag/js/home.js') }}"></script> -->
@endpush
