@extends('multibag::layouts.app')
@push('css')
@endpush
@section('content')
@section('breadcrumb')
@php
$title = $info->name ?? __('Product List');
@endphp
@if(isset($info->name))
<li><a href="{{ url('/shop') }}">{{ __('Products') }}</a></li>
<li><span> > </span></li>
<li class="active">{{ $info->name}}</li>
@else
<li class="active">{{ __('Products') }}</li>
@endif


@endsection
<product-index category-id="{{ $info->id ?? '' }}" inline-template>
    <div class="shop-area pt-20 pb-160">
        <div class="container">
            <div class="row flex-row-reverse">
                <div class="col-lg-9">
                    <div class="shop-product-fillter">
                        <div class="totall-product">
                            <p v-html="$trans('We found :total_number items for you!' , { total_number : `<strong class='text-brand'>` + (total ?? 0) + `</strong>`})"></p>
                        </div>
                        <div class="sort-by-product-area">
                            <div class="sort-by-cover mr-10">
                                <div class="sort-by-product-wrap">
                                    <div class="sort-by">
                                        <span><i class="fi-rs-apps"></i>{{ __('Show:') }}</span>
                                    </div>
                                    <div class="sort-by-dropdown-wrap">
                                        <span v-if="filter.limit == '100000'">All <i class="fi-rs-angle-small-down"></i></span>
                                        <span v-else> @{{ filter.limit }} <i class="fi-rs-angle-small-down"></i></span>
                                    </div>
                                </div>
                                <div class="sort-by-dropdown">
                                    <ul>
                                        <li><a :class="filter.limit == '20' ? 'active' : ''" @click="pageLimit('20')">20</a></li>
                                        <li><a :class="filter.limit == '50' ? 'active' : ''" @click="pageLimit('50')">50</a></li>
                                        <li><a :class="filter.limit == '100' ? 'active' : ''" @click="pageLimit('100')">100</a></li>
                                        <li><a :class="filter.limit == '100000' ? 'active' : ''" @click="pageLimit('100000')">All</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="sort-by-cover">
                                <div class="sort-by-product-wrap">
                                    <div class="sort-by">
                                        <span><i class="fi-rs-apps-sort"></i>{{ __('Sort by:') }}</span>
                                    </div>
                                    <div class="sort-by-dropdown-wrap">
                                        <span v-if="filter.order == 'desc'"> {{ __('New Items') }} <i class="fi-rs-angle-small-down"></i></span>
                                        <span v-if="filter.order == 'asc'"> {{ __('Old Items') }} <i class="fi-rs-angle-small-down"></i></span>
                                        <span v-if="filter.order == 'best_sell'"> {{ __('Best selling') }} <i class="fi-rs-angle-small-down"></i></span>
                                    </div>
                                </div>
                                <div class="sort-by-dropdown">
                                    <ul>
                                        <li><a :class="filter.order == 'desc' ? 'active' : ''" @click="shortBy('desc')">{{ __('Sort by new Items') }}</a></li>
                                        <li><a :class="filter.order == 'asc' ? 'active' : ''" @click="shortBy('asc')">{{ __('Sort by old Items') }}</a></li>
                                        <li><a :class="filter.order == 'best_sell' ? 'active' : ''" @click="shortBy('best_sell')">{{ __('Sort by best selling') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pt-30">
                        <div class="row product-grid" v-if="products.length > 0">
                            <product-card-grid-view v-for="product in products" :key="product.id" :product="product"></product-card-grid-view>
                        </div>
                        <div class="row" v-else>
                            <div class="col">
                                <empty-page :header="'{{ __('No Products found') }}'" :message="''" img="assets/img/empty_products.png"></empty-page>
                            </div>
                        </div>
                        <v-pagination :total-page="totalPages" :current-page="current_page" @page-changed="get_shop_products" v-if="total > products.length">
                        </v-pagination>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="shop-sidebar-style">
                        <div class="sidebar-widget">
                            <h4 class="pro-sidebar-title">{{ __('Category') }}</h4>
                            <div class="sidebar-widget-categori mt-45 mb-70">
                                <ul>
                                    <li :key="'all'">
                                        <a href="{{ url('/shop') }}" :class="categoryId == '' ? 'active' : ''">
                                            {{ __('All') }}
                                        </a>
                                    </li>
                                    <li v-for="category in categories" :key="category.id">
                                        <a :href="'category/' + category.slug + '/' + category.id" :class="category.id == categoryId ? 'active' : ''">
                                            {{-- <img :src="category.url" alt=""/> --}}
                                           @{{ category.name }}
                                        </a>
                                        {{-- <span class="count">@{{ category.products_count }}</span> --}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @if(current_shop_type() == 'seller')
                        <div class="sidebar-widget" v-for="attr in attributes" :key="attr.id">
                            <h4 class="pro-sidebar-title">@{{ attr.name }}</h4>
                            <div class="pro-details-color-content sidebar-widget-color mt-45 mb-70">
                                <div class="checkbox-option" v-for="v in attr.featured_with_product_count" :key="v.id">
                                    <label class="checkbox-entry" :for="'attribute_' + v.id">
                                        <input type="checkbox" class="option-input"
                                        @change="get_shop_products()" name="checkbox" :id="'attribute_' + v.id" v-model="filter.attrs" :value="v.id">
                                        <span class="option-span">@{{ v.name }} (@{{ v.products_count }})</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endif
                        {{-- <div class="sidebar-widget">
                            <h4 class="pro-sidebar-title">Filter By Price Range</h4>
                            <div class="price-filter mt-55 mb-65">
                                <div id="slider-range"></div>
                                <div class="price-slider-amount">
                                    <div class="label-input">
                                        <span>Price: </span><input type="text" id="amount" name="price"
                                            placeholder="Add Your Price" />
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="sidebar-widget">
                            <h4 class="pro-sidebar-title">{{ __('Brand') }}</h4>
                            <div class="sidebar-widget-brand-logo mt-50">
                                <div class="checkbox-option" v-for="brand in brands" :key="brand.id">
                                    <label class="checkbox-entry" :for="'brand_' + brand.id">
                                        <input type="checkbox" type="checkbox" @change="get_shop_products()" name="checkbox"
                                            :id="'brand_' + brand.id" v-model="filter.brands" class="option-input"
                                            :value="brand.id"><span class="option-span">@{{ brand.name }} (@{{ brand.products_count }})</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</product-index>
@endsection
