@extends('electrobag::layouts.app')
@section('content')

<div class="content-margins grey">
    @if(isset($sliders) && count($sliders) > 0)
    <section class="home-slider">
        <home-slider :sliders="{{ json_encode($sliders) }}"></home-slider>
    </section>
    <div class="empty-space col-xs-b35 col-md-b20"></div>
    @endif

    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-xs-b30 col-sm-b0">
                <div class="categories-wrapper">
                    <h4 class="h4 col-xs-b20">All Categories</h4>
                    @if(count($categories) > 0)
                    <home-category :categories="{{ json_encode($categories) }}"></home-category>
                    @endif
                    <div class="empty-space col-xs-b20"></div>

                    {{-- @if(count($offerable_products) > 0)
                    <offer-products-list :products="{{ json_encode($offerable_products) }}"></offer-products-list>
                    @endif --}}
                </div>
            </div>
            <div class="col-sm-6 col-md-8">

                <div class="text-center">
                    <h4 class="h4 col-xs-b20">OUR LATEST PRODUCTS</h4>
                    <div class="title-underline center"><span></span></div>
                </div>
                @if(count($latest_products) > 0)
                <popular-products :products="{{ json_encode($latest_products) }}"></popular-products>
                @endif
            </div>
        </div>
    </div>

    @if(count($offerable_products) > 0)
    <div class="container">
        <offer-products-list :products="{{ json_encode($offerable_products) }}"></offer-products-list>
    </div>
    @endif

    @if(count($banner_adds) > 0)
    <div class="container">
        <banner-ads :banners="{{ json_encode($banner_adds) }}"></banner-ads>
    </div>
    @endif

    @if(count($menu_categories) > 0)
    <div class="empty-space col-xs-b20"></div>
    <div class="container">
        <featured-categories :categories="{{ json_encode($featured_categories) }}"></featured-categories>
    </div>
    @endif

    
    <div class="empty-space col-xs-b20"></div>
    @if(count($bump_adds) > 0)
    <div class="empty-space col-xs-b20"></div>
    <div class="container">
        <banner-ads :banners="{{ json_encode($bump_adds) }}"></banner-ads>
    </div>
    @endif

    <div class="empty-space col-xs-b35 col-md-b70"></div>
    <div class="container">
        {{-- <div class="text-center">
            <h4 class="h4 col-xs-b20">CHOOSE THE BEST MOVING</h4>

        </div> --}}
        @if(count($best_selling_products) > 0 || count($latest_products) > 0 || count($trending_products) > 0 ||
        count($top_rated_products) > 0)
        <div class="tabs-block">
            <div class="container">
                <div class="tabulation-menu-wrapper text-center">
                    <div class="tabulation-title simple-input">{{ __('Top Selling') }}</div>
                    <ul class="tabulation-toggle">
                        <li><a class="tab-menu active">{{ __('Top Selling') }}</a></li>
                        <li><a class="tab-menu">{{ __('Trending Products') }}</a></li>
                        <li><a class="tab-menu">{{ __('Recently added') }}</a></li>
                        <li><a class="tab-menu">{{ __('Top Rated') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="empty-space col-xs-b30 col-sm-b60"></div>
            <div class="tab-entry visible">
                @if(count($best_selling_products) > 0)
                <home-column-list-products title="{{ __('Top Selling') }}"
                    :products="{{ json_encode($best_selling_products) }}"></home-column-list-products>
                @endif
            </div>
            <div class="tab-entry">
                @if(count($trending_products) > 0)
                <home-column-list-products title="{{ __('Trending Products') }}"
                    :products="{{ json_encode($trending_products) }}"></home-column-list-products>
                @endif
            </div>
            <div class="tab-entry">
                @if(count($latest_products) > 0)
                <home-column-list-products title="{{ __('Recently added') }}"
                    :products="{{ json_encode($latest_products) }}"></home-column-list-products>
                @endif
            </div>
            <div class="tab-entry">
                @if(count($top_rated_products) > 0)
                <home-column-list-products title="{{ __('Top Rated') }}"
                    :products="{{ json_encode($top_rated_products) }}">
                </home-column-list-products>
                @endif
            </div>
        </div>
        @endif

    </div>
    <div class="empty-space col-md-b70"></div>
    {{-- <div class="container">
        <div class="row">
            @if(count($best_selling_products) > 0)
            <small-product-list title="{{ __('Top Selling') }}" :products="{{ json_encode($best_selling_products) }}"></small-product-list>
            @endif
            @if(count($trending_products) > 0)
            <small-product-list title="{{ __('Trending Products') }}" :products="{{ json_encode($trending_products) }}"></small-product-list>
            @endif
            @if(count($latest_products) > 0)
            <small-product-list title="{{ __('Recently added') }}" :products="{{ json_encode($latest_products) }}"></small-product-list>
            @endif
            @if(count($top_rated_products) > 0)
            <small-product-list title="{{ __('Top Rated') }}" :products="{{ json_encode($top_rated_products) }}"></small-product-list>
            @endif
        </div>
    </div>
    <div class="empty-space col-xs-b20"></div> --}}
    <div class="empty-space col-xs-b20"></div> 
</div>

@endsection
@push('js')
<!-- <script src="{{ asset('frontend/singlebag/js/home.js') }}"></script> -->
@endpush
