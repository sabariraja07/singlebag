@extends('fashionbag::layouts.app')
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
    <div class="section section-margin">
        <div class="container" v-if="cart.count > 0">

            <div class="row">
                <div class="col-12">

                    <!-- Cart Table Start -->
                    <div class="cart-table table-responsive">
                        @include('fashionbag::cart.items')
                    </div>
                    <!-- Cart Table End -->

                    <!-- Cart Update Option Start -->
                    <div class="cart-update-option d-block d-md-flex justify-content-between">
                        @if(domain_info('shop_type') != 'reseller')
                        <!-- Apply Coupon Wrapper Start -->
                        @include('fashionbag::cart.coupon')
                        <!-- Apply Coupon Wrapper End -->
                        @endif

                        <!-- Cart Update Start -->
                        <div class="cart-update mt-sm-16">
                            <a href="{{ url('/shop') }}" class="btn btn-fb btn-hover-primary rounded-0">{{ __('Add Items') }}</a>
                            {{-- <a href="javascript::void(0)" class="btn btn-fb btn-hover-primary rounded-0">Update Cart</a> --}}
                            <a href="javascript::void(0)" class="btn btn-danger btn-hover-primary rounded-0" @click="clearCart">{{ __('Clear Cart') }}</a>
                        </div>
                        <!-- Cart Update End -->

                    </div>
                    <!-- Cart Update Option End -->

                </div>
            </div>

            <div class="row">
                <div class="col-lg-5 ms-auto col-custom">

                    <!-- Cart Calculation Area Start -->
                    <div class="cart-calculator-wrapper">

                        <!-- Cart Calculate Items Start -->
                        @include('fashionbag::cart.order_summary')
                        <!-- Cart Calculate Items End -->

                        <!-- Cart Checktout Button Start -->
                        <a href="{{ url('/checkout') }}" :disabled="loadingOrderSummary" class="btn btn-fb btn-hover-primary rounded-0 w-100">{{ __('Proceed To CheckOut') }}</a>
                        <!-- Cart Checktout Button End -->

                    </div>
                    <!-- Cart Calculation Area End -->

                </div>
            </div>

        </div>
        <div class="row" v-else>
            <div class="col">
            <empty-page :header="'{{ __('Your cart is empty') }}'" :message="''" :img="'assets/img/empty_cart.png'"></empty-page>
            </div>
        </div>
    </div>
</cart-index>
@endsection
