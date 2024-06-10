@extends('singlebag::layouts.app')
@push('css')
@endpush
@section('content')
<div class="page-header mt-30 mb-50">
    <div class="container">
        <div class="archive-header">
            <div class="row align-items-center">
                <div class="col-xl-3">
                    <h1 class="mb-15">{{ $info->name ?? __('Product List') }}</h1>
                    <div class="breadcrumb">
                        <a href="{{ url('/') }}" rel="nofollow"><i class="fi-rs-home mr-5"></i>{{ __('Home') }}</a>
                        <span></span> {{ $info->name ?? __('Products') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<product-index category-id="{{ $info->id ?? '' }}" inline-template>
    <div class="container mb-30">
        <div class="row">

            <div class="col-lg-1-5 primary-sidebar">
                <div class="sidebar-widget widget-category-2 mb-30" style="padding:7px !important;">
                    <h5 class="section-title style-1 mb-30">{{ __('Category') }}</h5>
                    <ul>
                        <li :key="'all'">
                            <a href="{{ url('/shop') }}">
                                {{ __('All') }}
                            </a>
                        </li>
                        <li v-for="category in categories" :key="category.id">
                            <a :href="'category/' + category.slug + '/' + category.id" style="width:90% !important;">
                                <img :src="category.url" alt=""/>
                               @{{ category.name }}
                            </a>
                            <span class="count">@{{ category.products_count }}</span>
                        </li>
                    </ul>
                </div>
                <div class="sidebar-widget price_range range mb-30">
                    <h5 class="section-title style-1 mb-30">{{ __('Brand') }}</h5>
                    <div class="list-group">
                        <div class="list-group-item mb-10 mt-10">
                            <div class="custome-checkbox">
                                <div v-for="brand in brands" :key="brand.id">
                                    <input class="form-check-input" type="checkbox" @change="get_shop_products()" name="checkbox" :id="'brand_' + brand.id" v-model="filter.brands" :value="brand.id" />
                                    <label class="form-check-label" :for="'brand_' + brand.id"><span>@{{ brand.name }} (@{{ brand.products_count }})</span></label>
                                    <br />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(current_shop_type() == 'seller')
                <div class="sidebar-widget price_range range mb-30" v-for="attr in attributes" :key="attr.id">
                    <h5 class="section-title style-1 mb-30">@{{ attr.name }}</h5>
                    <div class="list-group">
                        <div class="list-group-item mb-10 mt-10">
                            <div class="custome-checkbox">
                                <div v-for="v in attr.featured_with_product_count" :key="v.id">
                                    <input class="form-check-input" type="checkbox" @change="get_shop_products()" name="checkbox" :id="'attribute_' + v.id" v-model="filter.attrs" :value="v.id" />
                                    <label class="form-check-label" :for="'attribute_' + v.id"><span>@{{ v.name }} (@{{ v.products_count }})</span></label>
                                    <br />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-lg-4-5">
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
                <div class="row product-grid" v-if="products.length > 0">
                    <product-card-grid-view v-for="product in products" :key="product.id" :product="product"></product-card-grid-view>
                </div>
                <div class="row" v-else>
                    <div class="col">
                        <empty-page :header="'{{ __('No Products found') }}'" :message="''" img="assets/img/empty_products.png"></empty-page>
                    </div>
                </div>
               
    <!--product grid-->
    <div class="pagination-area mt-20 mb-20">
        <nav aria-label="Page navigation example">
            <v-pagination :total-page="totalPages" :current-page="current_page" @page-changed="get_shop_products" v-if="total > products.length">
            </v-pagination>
        </nav>
    </div>
    </div>
    </div>
    </div>
</product-index>

@endsection
@push('js')
<!-- <script src="{{ asset('frontend/singlebag/js/shop.js') }}"></script> -->
@endpush