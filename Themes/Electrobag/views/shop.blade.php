@extends('electrobag::layouts.app')

@section('content')
<product-index category-id="{{ $info->id ?? '' }}" inline-template>
    <div class="container">
        <div class="empty-space col-xs-b15 col-sm-b30"></div>
        <div class="breadcrumbs">
            <a href="{{ url('/') }}">{{ __('Home') }}</a>
            <a href="{{ url('/shop') }}">{{ __('All Products') }}</a>
            @if(isset($info->name))
            <a href="javascript:void(0)" >{{ $info->name }}</a>
            @endif
        </div>
        <div class="empty-space col-xs-b15 col-sm-b50 col-md-b100"></div>
        <div class="row">
            <div class="col-md-9 col-md-push-3">
                <div class="text-center">
                    <div class="h2">{{ $info->name ?? __('All Products') }}</div>
                    <div class="title-underline center"><span></span></div>
                </div>
                <div class="empty-space col-xs-b35 col-md-b70"></div>

                <div class="align-inline spacing-1">
                    <div class="h4"></div>
                </div>
                <div class="align-inline spacing-1">
                    <div class="simple-article size-1"
                    v-html="$trans('We found :total_number items for you!' , { total_number : `<b class='grey'>` + (total ?? 0) + `</b>`})">
                    </div>
                </div>
                
                <div class="align-inline spacing-1 filtration-cell-width-1">
                    <select class="SlectBox small" name="sort_by" id="sort_by"
                    v-model="filter.order">
                        <option disabled="disabled" selected="selected">{{ __('Sort by new Items') }}</option>
                        <option :value="'desc'">{{ __('Sort by new Items') }}</option>
                        <option :value="'asc'">{{ __('Sort by old Items') }}</option>
                        <option :value="'best_sell'">{{ __('Sort by best selling') }}</option>
                    </select>
                </div>
                <div class="align-inline spacing-1 filtration-cell-width-1">
                    <select class="SlectBox small" name="page_limit" id="page_limit"
                        v-model="filter.limit">
                        <option disabled="disabled" selected="selected">Show 30</option>
                        <option value="30">Show 30</option>
                        <option value="50">Show 50</option>
                        <option value="100">Show 100</option>
                        <option value="200">Show 200</option>
                    </select>
                </div>
                <div class="align-inline spacing-1 hidden-xs">
                    <a class="pagination toggle-products-view active">
                        <img src="{{asset('frontend/electrobag/img/icon-14.png')}}" alt="" />
                        <img src="{{asset('frontend/electrobag/img/icon-15.png')}}" alt="" />
                    </a>
                    <a class="pagination toggle-products-view">
                        <img src="{{asset('frontend/electrobag/img/icon-16.png')}}" alt="" />
                        <img src="{{asset('frontend/electrobag/img/icon-17.png')}}" alt="" />
                    </a>
                </div>
                <div class="empty-space col-xs-b25 col-sm-b60"></div>
                <div class="products-content">
                    <div class="products-wrapper">
                        <div class="row nopadding">
                            <div class="row product-grid">

                                <product-card-grid-view v-for="product in products" :key="product.id"
                                    :product="product"></product-card-grid-view>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="empty-space col-xs-b35 col-sm-b0"></div>
                <v-pagination :total-page="totalPages" :current-page="current_page" @page-changed="get_shop_products" v-if="total > products.length">
                </v-pagination>
            </div>
            <div class="col-md-3 col-md-pull-9">
                <div class="empty-space col-xs-b25 col-sm-b50"></div>
                <div class="h4 col-xs-b10">Categories</div>
                <category-menu :categories="categories"></category-menu>
                {{-- <ul class="categories-menu transparent">
                    <li v-for="category in categories" :key="category.id">
                        <a v-if="category.p_id===null" :href="'category/' + category.slug + '/' + category.id">
                            @{{ category.name }}
                        </a>
                        <div v-if="category.p_id===null" class="toggle"></div>
                        <ul>
                            <li v-for="subcategory in category.children_categories" :key="subcategory.id">
                                <a :href="'category/' + subcategory.slug + '/' + subcategory.id">
                                    @{{ subcategory.name }}
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul> --}}
                <div class="empty-space col-xs-b25 col-sm-b50"></div>
                <div class="h4 col-xs-b25">Brands</div>
                <div class="text-left" v-for="brand in brands" :key="brand.id">
                    <label class="checkbox-entry" :for="'brand_' + brand.id">
                        <input type="checkbox" type="checkbox" @change="get_shop_products()" name="checkbox"
                            :id="'brand_' + brand.id" v-model="filter.brands"
                            :value="brand.id"><span>@{{ brand.name }} (@{{ brand.products_count }})</span>
                    </label>
                    <div class="empty-space col-xs-b10"></div>
                </div>
                <div class="empty-space col-xs-b25 col-sm-b50"></div>
                @if(current_shop_type() == 'seller')
                <div v-for="attr in attributes" :key="attr.id">
                    <div class="h4 col-xs-b25">@{{ attr.name }}</div>
                    <div class="text-left" v-for="v in attr.featured_with_product_count" :key="v.id">
                        <label class="checkbox-entry" :for="'attribute_' + v.id">
                            <input type="checkbox" @change="get_shop_products()" name="checkbox" :id="'attribute_' + v.id" v-model="filter.attrs" :value="v.id">
                            <span>@{{ v.name }} (@{{ v.products_count }})</span>
                        </label>
                        <div class="empty-space col-xs-b10"></div>
                    </div>
                    <div class="empty-space col-xs-b25 col-sm-b50"></div>
                </div>
                @endif
                
                <div class="empty-space col-xs-b10"></div>
                <div class="empty-space col-xs-b25 col-sm-b50"></div>
                <div class="empty-space col-xs-b25 col-sm-b50"></div>
            </div>
        </div>
    </div>
</product-index>

<div class="empty-space col-xs-b15 col-sm-b45"></div>
<div class="empty-space col-md-b70"></div>
@endsection

@push('js')

@endpush
