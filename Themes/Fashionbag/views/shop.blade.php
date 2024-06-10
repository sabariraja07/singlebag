@extends('fashionbag::layouts.app')
@push('css')
<style>

.select2.select2-container {
  width: 100% !important;
}

.select2.select2-container .select2-selection {
  border: 1px solid #ccc;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
  height: 34px;
  margin-bottom: 15px;
  outline: none !important;
  transition: all .15s ease-in-out;
}

.select2.select2-container .select2-selection .select2-selection__rendered {
  color: #333;
  line-height: 32px;
  padding-right: 33px;
}

.select2.select2-container .select2-selection .select2-selection__arrow {
  background: #f8f8f8;
  border-left: 1px solid #ccc;
  -webkit-border-radius: 0 3px 3px 0;
  -moz-border-radius: 0 3px 3px 0;
  border-radius: 0 3px 3px 0;
  height: 32px;
  width: 33px;
}

.select2.select2-container.select2-container--open .select2-selection.select2-selection--single {
  background: #f8f8f8;
}

.select2.select2-container.select2-container--open .select2-selection.select2-selection--single .select2-selection__arrow {
  -webkit-border-radius: 0 3px 0 0;
  -moz-border-radius: 0 3px 0 0;
  border-radius: 0 3px 0 0;
}

.select2.select2-container.select2-container--open .select2-selection.select2-selection--multiple {
  border: 1px solid #34495e;
}

.select2.select2-container .select2-selection--multiple {
  height: auto;
  min-height: 34px;
}

.select2.select2-container .select2-selection--multiple .select2-search--inline .select2-search__field {
  margin-top: 0;
  height: 32px;
}

.select2.select2-container .select2-selection--multiple .select2-selection__rendered {
  display: block;
  padding: 0 4px;
  line-height: 29px;
}

.select2.select2-container .select2-selection--multiple .select2-selection__choice {
  background-color: #f8f8f8;
  border: 1px solid #ccc;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
  margin: 4px 4px 0 0;
  padding: 0 6px 0 22px;
  height: 24px;
  line-height: 24px;
  font-size: 12px;
  position: relative;
}

.select2.select2-container .select2-selection--multiple .select2-selection__choice .select2-selection__choice__remove {
  position: absolute;
  top: 0;
  left: 0;
  height: 22px;
  width: 22px;
  margin: 0;
  text-align: center;
  color: #e74c3c;
  font-weight: bold;
  font-size: 16px;
}

.select2-container .select2-dropdown {
  background: transparent;
  border: none;
  margin-top: -5px;
}

.select2-container .select2-dropdown .select2-search {
  padding: 0;
}

.select2-container .select2-dropdown .select2-search input {
  outline: none !important;
  border: 1px solid #34495e !important;
  border-bottom: none !important;
  padding: 4px 6px !important;
}

.select2-container .select2-dropdown .select2-results {
  padding: 0;
}

.select2-container .select2-dropdown .select2-results ul {
  background: #fff;
  border: 1px solid #34495e;
}

.select2-container .select2-dropdown .select2-results ul .select2-results__option--highlighted[aria-selected] {
  background-color: #3498db;
}
</style>
@endpush
@section('content')
@section('breadcrumb')
@php
$title = $info->name ?? __('Product List');
@endphp
@if(isset($info->name))
<li><a href="{{ url('/shop') }}">{{ __('Products') }}</a></li>
<li class="active">{{ $info->name}}</li>
@else
<li class="active">{{ __('Products') }}</li>
@endif


@endsection
<product-index category-id="{{ $info->id ?? '' }}" inline-template>
    <div class="section section-margin">
        <div class="container">
            <div class="row flex-row-reverse">
                <div class="col-lg-9 col-12 col-custom">

                    <!--shop toolbar start-->
                    <div class="shop_toolbar_wrapper flex-column flex-md-row mb-10">

                        <!-- Shop Top Bar Left start -->
                        <div class="shop-top-bar-left mb-md-0 mb-2">
                            <div class="shop-top-show">
                                <span v-html="$trans('We found :total_number items for you!' , { total_number : `<strong class='text-brand'>` + (total ?? 0) + `</strong>`})"></span>
                            </div>
                        </div>
                        <!-- Shop Top Bar Left end -->

                        <!-- Shopt Top Bar Right Start -->
                        <div class="shop-top-bar-right">
                            <div class="shop-short-by mr-4">
                                <single-select class="form-control" v-model="filter.limit" @change="pageLimit(filter.limit)">
                                    <option value="20">Show 20 Per Page</option>
                                    <option value="50">Show 50 Per Page</option>
                                    <option value="100">Show 100 Per Page</option>
                                    <option value="10000">Show All</option>
                                </single-select>
                            </div>

                            <div class="shop-short-by mr-4">
                                <single-select class="form-control" v-model="filter.order" @change="shortBy(filter.order)">
                                    <option value="desc">{{ __('Sort by new Items') }}</option>
                                    <option value="asc">{{ __('Sort by old Items') }}</option>
                                    <option value="best_sell">{{ __('Sort by best selling') }}</option>
                                </single-select>
                            </div>

                            {{-- <div class="shop_toolbar_btn">
                                <button data-role="grid_3" type="button" class="active btn-grid-4" title="Grid"><i class="fa fa-th"></i></button>
                                <button data-role="grid_list" type="button" class="btn-list" title="List"><i class="fa fa-th-list"></i></button>
                            </div> --}}
                        </div>
                        <!-- Shopt Top Bar Right End -->

                    </div>
                    <!--shop toolbar end-->

                    <!-- Shop Wrapper Start -->
                    <div class="row shop_wrapper grid_3">
                        <product-card-grid-view v-for="product in products" :key="product.id" :product="product"></product-card-grid-view>
                    </div>
                    <!-- Shop Wrapper End -->

                    <!--shop toolbar start-->
                    <div class="shop_toolbar_wrapper mt-10">

                        <!-- Shop Top Bar Left start -->
                        <div class="shop-top-bar-left">
                            <div class="shop-short-by mr-4">
                                {{-- <select class="nice-select rounded-0" aria-label=".form-select-sm example">
                                    <option selected>Show 12 Per Page</option>
                                    <option value="1">Show 12 Per Page</option>
                                    <option value="2">Show 24 Per Page</option>
                                    <option value="3">Show 15 Per Page</option>
                                    <option value="3">Show 30 Per Page</option>
                                </select> --}}
                                <single-select class="form-control" v-model="filter.limit" @change="pageLimit(filter.limit)">
                                    <option value="20">Show 20 Per Page</option>
                                    <option value="50">Show 50 Per Page</option>
                                    <option value="100">Show 100 Per Page</option>
                                    <option value="10000">Show All</option>
                                </single-select>
                            </div>
                        </div>
                        <!-- Shop Top Bar Left end -->

                        <!-- Shopt Top Bar Right Start -->
                        <div class="shop-top-bar-right">
                            <nav>
                                <v-pagination :total-page="totalPages" :current-page="current_page" @page-changed="get_shop_products" v-if="total > products.length">
                                </v-pagination>
                            </nav>
                        </div>
                        <!-- Shopt Top Bar Right End -->

                    </div>
                    <!--shop toolbar end-->

                </div>
                <div class="col-lg-3 col-12 col-custom">
                    <!-- Sidebar Widget Start -->
                    <aside class="sidebar_widget mt-10 mt-lg-0">
                        <div class="widget_inner" data-aos="fade-up" data-aos-delay="200">
                            {{-- <div class="widget-list mb-10">
                                <h3 class="widget-title mb-40">Search</h3>
                                <div class="search-box">
                                    <input type="text" class="form-control" placeholder="Search Our Store" aria-label="Search Our Store">
                                    <button class="btn btn-fb btn-hover-primary" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div> --}}
                            <div class="widget-list mb-10">
                                <h3 class="widget-title mb-40">{{ __('Category') }}</h3>
                                <!-- Widget Menu Start -->
                                <nav>
                                    <ul class="category-menu mb-n3">
                                        <li class="menu-item-has-children pb-4" v-for="category in categories" :key="category.id">
                                            <a :href="'category/' + category.slug + '/' + category.id">
                                               @{{ category.name }} (@{{ category.products_count }})
                                               {{-- <i class="fa fa-angle-down"></i> --}}
                                            </a>
                                            {{-- <ul class="dropdown">
                                                <li><a href="javascript::void(0)"></a></li>
                                            </ul> --}}
                                        </li>
                                    </ul>
                                </nav>
                                <!-- Widget Menu End -->
                            </div>
                            {{-- <div class="widget-list mb-10">
                                <h3 class="widget-title">{{ __('Category') }}</h3>
                                <div class="sidebar-body">
                                    <ul class="sidebar-list" v-for="category in categories" :key="category.id">
                                        <li><a :href="'category/' + category.slug + '/' + category.id">@{{ category.name }} (@{{ category.products_count }})</a></li>
                                    </ul>
                                </div>
                            </div> --}}
                            @if(current_shop_type() == 'seller')
                            <div class="widget-list mb-10" v-for="attr in attributes" :key="attr.id">
                                <h3 class="widget-title">@{{ attr.name }}</h3>
                                <div class="sidebar-body">
                                    <ul class="checkbox-container categories-list">
                                        <li v-for="v in attr.featured_with_product_count" :key="v.id">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" @change="get_shop_products()" name="checkbox" :id="'attribute_' + v.id" v-model="filter.attrs" :value="v.id" />
                                                <label class="custom-control-label" :for="'attribute_' + v.id"><span>@{{ v.name }} (@{{ v.products_count }})</span></label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @endif
                            <div class="widget-list mb-10">
                                <h3 class="widget-title">{{ __('Brand') }}</h3>
                                <div class="sidebar-body">
                                    <ul class="checkbox-container categories-list">
                                        <li v-for="brand in brands" :key="brand.id">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                @change="get_shop_products()" name="checkbox" :id="'brand_' + brand.id" v-model="filter.brands" :value="brand.id">
                                                <label class="custom-control-label" :for="'brand_' + brand.id">@{{ brand.name }} (@{{ brand.products_count }})</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </aside>
                    <!-- Sidebar Widget End -->
                </div>
            </div>
        </div>
    </div>
</product-index>
@endsection