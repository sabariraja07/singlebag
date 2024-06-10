@extends('singlebag::layouts.app')
@push('css')
@endpush
@section('breadcrumb')
  <span></span> {{__('Cart')}}
@endsection
@section('content')
<cart-index inline-template>
    <div class="container mb-80 mt-50">
        <div class="row">
            <div class="col-lg-8 mb-40">
                <h1 class="heading-2 mb-10">{{ __('Your Cart') }}</h1>
                <div class="d-flex justify-content-between"  v-if="cart.count > 0">
                    <h6 class="text-body" v-html="$trans('There are :number products in your cart' , { 'number' : `<span class='text-brand'>` + (cart.count ?? 0 ) + `</span>`})"></h6>
                    <h6 class="text-body"><a @click="clearCart" class="text-muted clear-cart-btn"><i class="fi-rs-trash mr-5"></i>{{ __('Clear Cart') }}</a></h6>
                </div>
            </div>
        </div>
        <div class="row" v-if="cart.count > 0">
            <div class="col-lg-9">
                @include('singlebag::cart.items')
                <div class="divider-2 mb-30"></div>
                <div class="cart-action d-flex justify-content-between">
                    <a href="{{ url('/shop') }}" class="btn"><i class="fi-rs-arrow-left mr-10"></i>{{ __('Continue Shopping') }}</a>
                    {{-- <a @click="updateCart" class="btn mr-10 mb-sm-15 get-cart-btn"><i class="fi-rs-refresh mr-10"></i>{{ __('Update Cart') }}</a> --}}
                </div>
                @if(domain_info('shop_type') != 'reseller')
                <div class="row mt-50">
                    <div class="col-lg-5">
                        @include('singlebag::cart.coupon')
                    </div>
                </div>
                @endif
            </div>
            <div class="col-lg-3">
            @include('singlebag::cart.order_summary')
            </div>
        </div>
        <div class="row" v-else>
            <div class="col">
            <empty-page :header="'{{ __('Your cart is empty') }}'" :message="''" img="assets/img/empty_cart.png"></empty-page>
            </div>
        </div>
    </div>
</cart-index>
@endsection
@push('js')
<!-- <script src="{{ asset('frontend/singlebag/js/cart.js') }}"></script> -->
@endpush