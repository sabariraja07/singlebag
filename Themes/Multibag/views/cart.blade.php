@extends('multibag::layouts.app')
@push('css')
@endpush
@section('breadcrumb')
@php
$title="Cart";
@endphp
<li class="active">{{ __('Cart') }}</li>
@endsection
@section('content')
<cart-index inline-template>
    <div class="cart-area bg-gray pt-80 pb-160">
        <div class="container" v-if="cart.count > 0">
            {{-- <form action="#">
                <div class="proceed-btn">
                    <a href="{{ url('/checkout') }}" :disabled="loadingOrderSummary">{{ __('Proceed To CheckOut') }}</a>
                </div> --}}
                @include('multibag::cart.items')
            {{-- </form> --}}
            <div class="row">
                @if(domain_info('shop_type') != 'reseller')
                <div class="col-lg-6 col-md-6">
                    @include('multibag::cart.coupon')
                </div>
                <div class="col-lg-6 col-md-6">
                    @include('multibag::cart.order_summary')
                </div>
                @else
                <div class="col-lg-12 col-md-12">
                    @include('multibag::cart.order_summary')
                </div>
                @endif
            </div>
        </div>
        <div class="container" v-else>
            <div class="empty-cart-content text-center">
                <img src="assets/img/empty_cart.png" alt="">
                <h3>{{ __('Your cart is empty') }}</h3>
                <div class="empty-cart-btn">
                    <a href="{{ url('shop') }}">{{ __('Return To Shop') }}</a>
                </div>
            </div>
        </div>
    </div>
</cart-index>
@endsection
