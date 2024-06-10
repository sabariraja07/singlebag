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
@section('breadcrumb')
@php
$title="Checkout";
@endphp
<li class="active">{{ __('Checkout') }}</li>
@endsection
@section('content')
<checkout-create :gateways="{{ $gateways }}"
    :locations="{{ $locations }}" inline-template>
    <div class="section section-margin">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
                </div>
                <div class="col-lg-12">
                    @if($errors->has('email'))
                    <div class="alert alert-danger" role="alert">
                        {{ $errors->first('email') }}
                    </div>
                    @endif
                    @if(Session::get('message'))
                    <div class="alert alert-danger" role="alert">
                        {{ Session::get('message') }}
                    </div>
                    @endif
                </div>
                <div class="col-12">
                    <!-- Coupon Accordion Start -->
                    <div class="coupon-accordion">
                        @if(!Auth::guard('customer')->check())
                        <!-- Title Start -->
                        <h3 class="title">{{ __('Already have an account?') }} <span id="showlogin">{{ __('Click here to login') }}</span></h3>
                        <!-- Title End -->

                        <!-- Checkout Login Start -->
                        <div id="checkout-login" class="coupon-content">
                            <div class="coupon-info">
                                <p class="coupon-text mb-2">
                                    {{ __('If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing & Shipping section.') }}
                                </p>

                                <!-- Form Start -->
                                <form action="{{ url('/customer/login') }}" method="post">
                                    @csrf
                                    <!-- Input Email Start -->
                                    <p class="form-row-first">
                                        <label>{{ __('Email') }} <span class="required">*</span></label>
                                        <input type="text"  class="@error('email') is-invalid @enderror" name="email"
                                        placeholder="{{ __('Email') }}" value="{{ old('email') }}" required="">
                                        @if($errors->has('email'))
                                        <div class="invalid-feedback" style="display: block;">
                                            {{ $errors->first('email') }}
                                        </div>
                                        @endif
                                        @if(Session::get('message'))
                                        <div class="invalid-feedback" style="display: block;">
                                            {{ Session::get('message') }}
                                        </div>
                                        @endif
                                    </p>
                                    <!-- Input Email End -->

                                    <!-- Input Password Start -->
                                    <p class="form-row-last">
                                        <label>{{ __('Password') }} <span class="required">*</span></label>
                                        <input type="password" name="password" placeholder="{{ __('Password') }}" required="">
                                    </p>
                                    <!-- Input Password End -->

                                    <!-- Remember Password Start -->
                                    <p class="form-row mb-2">
                                        <input type="checkbox" id="remember_me" name="remember">
                                        <label for="remember_me" class="checkbox-label">{{ __('Remember Me') }}</label>
                                    </p>
                                    <!-- Remember Password End -->

                                    <!-- Lost Password Start -->
                                    <p class="lost-password"><a href="{{ url('/user/password/reset') }}">{{ __('Forgot Password ?') }}</a></p>
                                    <!-- Lost Password End -->
                                    <button class="btn btn-fb btn-hover-primary rounded-0" name="login">{{ __('Sign In') }}</button>
                                </form>
                                <!-- Form End -->

                            </div>
                        </div>
                        <!-- Checkout Login End -->
                        @endif

                        <!-- Title Start -->
                        <h3 class="title">{{ __('Have a coupon?') }} <span id="showcoupon">{{ __('Click here to enter your code') }}</span></h3>
                        <!-- Title End -->

                        <!-- Checkout Coupon Start -->
                        <div id="checkout_coupon" class="coupon-checkout-content">
                            <div class="coupon-info">
                                <form @submit.prevent="applyCoupon()">
                                    <p class="checkout-coupon d-flex">
                                        <input name="coupon" type="text" v-model="coupon" placeholder="{{ __('Enter Coupon Code...') }}">
                                        <button class="btn btn-fb btn-hover-primary rounded-0" name="update-coupon-btn">{{ __('Apply Coupon') }}</button>
                                    </p>
                                </form>
                            </div>
                        </div>
                        <!-- Checkout Coupon End -->

                    </div>
                    <!-- Coupon Accordion End -->
                </div>
            </div>
            <div class="row mb-n4">
                <div class="col-lg-6 col-12 mb-40">

                    <!-- Checkbox Form Start -->
                    <form action="#">
                        <div class="checkbox-form">

                            <!-- Checkbox Form Title Start -->
                            <h3 class="title">{{ __('Billing Details') }}</h3>
                            <!-- Checkbox Form Title End -->

                            <div class="row">
                                <!-- First Name Input Start -->
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>{{ __('First Name') }}<span class="required">*</span></label>
                                        <input v-model="form.first_name" name="first_name"
                                        placeholder="{{ __('First name *') }}" required type="text">
                                    </div>
                                </div>
                                <!-- First Name Input End -->

                                <!-- Last Name Input Start -->
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>{{ __('Last Name') }} <span class="required">*</span></label>
                                        <input v-model="form.last_name" name="last_name"
                                        placeholder="{{ __('Last name *') }}" required type="text">
                                    </div>
                                </div>
                                <!-- Last Name Input End -->

                                <!-- Address Input Start -->
                                <div class="col-md-12">
                                    <div class="checkout-form-list">
                                        <label>{{ __('Address') }} <span class="required">*</span></label>
                                        <input type="text" name="delivery_address" v-model="form.delivery_address"
                                        required="" placeholder="{{ __('Address *') }}">
                                    </div>
                                </div>
                                <!-- Address Input End -->

                                <div class="col-md-12">
                                    <div class="checkout-form-list">
                                        <label>{{ __('Phone') }} <span class="required">*</span></label>
                                        <input required="" type="text" name="phone" id="mobile_number" v-model="form.phone"
                                        placeholder="{{ __('Phone*') }}" @keypress="$isMobileNumber($event)" maxlength="10" minlength="10">
                                    </div>
                                </div>

                                <!-- Postcode or Zip Input Start -->
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>{{ __('Postcode / ZIP') }} <span class="required">*</span></label>
                                        <input required="" type="text" name="zip_code" v-model="form.zip_code"
                                        placeholder="{{ __('Postcode / ZIP *') }}">
                                    </div>
                                </div>
                                <!-- Postcode or Zip Input End -->

                                <!-- Email Address Input Start -->
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>{{ __('Email address') }} <span class="required">*</span></label>
                                        <input required="" type="text" name="email" v-model="form.email"
                                        placeholder="{{ __('Email address *') }}">
                                    </div>
                                </div>
                                <!-- Email Address Input End -->
                                <!-- Select Country Name Start -->
                                <div class="col-md-12">
                                    <div class="country-select">
                                        <label>{{ __('Select City') }} <span class="required">*</span></label>
                                        <single-select class="form-control" :options="{{ $select_locations }}" v-model="form.location"
                                            @change="get_shipping_methods()" style="width:100%;">
                                            <option disabled value>{{ __('Select City') }}</option>
                                        </single-select>
                                    </div>
                                </div>
                                <!-- Select Country Name End -->

                                <!-- Checkout Form List checkbox Start -->
                                <div class="col-md-12">
                                    <div class="checkout-form-list create-acc">
                                        <input id="cbox" type="checkbox">
                                        <label for="cbox" class="checkbox-label">{{ __('Create an account?') }}</label>
                                    </div>
                                    <div id="cbox-info" class="checkout-form-list create-account" style="display: none;">
                                        <label>{{ __('Password') }} <span class="required">*</span></label>
                                        <input required="" type="password" v-model="form.password"
                                            placeholder="{{ __('Password') }}" name="password">
                                    </div>
                                </div>
                                <!-- Checkout Form List checkbox End -->

                            </div>

                            <!-- Different Address Start -->
                            <div class="different-address">

                                <!-- Order Notes Textarea Start -->
                                <div class="order-notes mt-3 mb-n2">
                                    <div class="checkout-form-list checkout-form-list-2">
                                        <label>{{ __('Additional information') }}</label>
                                        <textarea id="checkout-mess" cols="30" rows="10" v-model="form.comment" name="comment"
                                        placeholder="{{ __('Additional information') }}"></textarea>
                                    </div>
                                </div>
                                <!-- Order Notes Textarea End -->

                            </div>
                            <!-- Different Address End -->
                        </div>
                    </form>
                    <!-- Checkbox Form End -->

                </div>

                <div class="col-lg-6 col-12 mb-40">

                    <!-- Your Order Area Start -->
                    <div class="your-order-area border">

                        <!-- Title Start -->
                        <h3 class="title">{{ __('Your Order') }}</h3>
                        <!-- Title End -->

                        <!-- Your Order Table Start -->
                        <div class="your-order-table table-responsive">
                            <table class="table">

                                <!-- Table Head Start -->
                                <thead>
                                    <tr class="cart-product-head">
                                        <th class="cart-product-name text-start">Product</th>
                                        <th class="cart-product-total text-end">Total</th>
                                    </tr>
                                </thead>
                                <!-- Table Head End -->

                                <!-- Table Body Start -->
                                <tbody>
                                    @foreach(Cart::content() as $row)
                                    <tr class="cart_item">
                                        <td class="cart-product-name text-start ps-0"> 
                                            {{ $row->name }}<strong class="product-quantity"> × {{ $row->qty }}</strong>
                                            <br/>
                                            @foreach ($row->attributes->options as $item)
                                            <p>{{ $item->name }}</p>
                                            @if($loop->last)
                                            <br>
                                            @endif
                                            @endforeach
                                        </td>
                                        <td class="cart-product-total text-end pe-0">
                                            <span class="amount">{{ amount_format($row->qty * $row->price) }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <!-- Table Body End -->

                                <!-- Table Footer Start -->
                                <tfoot>
                                    {{-- <tr class="cart-subtotal">
                                        <th class="text-start ps-0">{{ __('Price Total') }}</th>
                                        <td class="text-end pe-0">
                                            <strong><span class="amount">@{{ $formatPrice(cart.priceTotal) }}</span></strong>
                                        </td>
                                    </tr> --}}
                                    <tr class="cart-subtotal">
                                        <th class="text-start ps-0">{{ __('Discount') }}</th>
                                        <td class="text-end pe-0">
                                            <strong><span class="amount">@{{ $formatPrice(cart.discount) }}</span></strong>
                                        </td>
                                    </tr>
                                    <tr class="cart-subtotal">
                                        <th class="text-start ps-0">{{ __('Tax') }}</th>
                                        <td class="text-end pe-0">
                                            <strong><span class="amount">@{{ $formatPrice(cart.tax) }}</span></strong>
                                        </td>
                                    </tr>
                                    <tr class="cart-subtotal">
                                        <th class="text-start ps-0">{{ __('Subtotal') }}</th>
                                        <td class="text-end pe-0">
                                            <strong><span class="amount">@{{ $formatPrice(cart.subtotal) }}</span></strong>
                                        </td>
                                    </tr>
                                    <tr class="order-total">
                                        <th class="text-start ps-0">{{ __('Total') }}</th>
                                        <td class="text-end pe-0">
                                            <input type="hidden"  name="total" id="cart-total" value="{{Cart::total()}}">
                                            <strong><span class="amount">@{{ $formatPrice(parseFloat(cart.total) +  shipping_price) }}</span></strong>
                                        </td>
                                    </tr>
                                </tfoot>
                                <!-- Table Footer End -->

                            </table>
                        </div>
                        <!-- Your Order Table End -->

                        <div class="payment-accordion-order-button" v-if="shipping_methods.length" style="margin-bottom: 40px;">
                            <h4 class="mb-30">{{ __('Shipping Method') }}</h4>
                            <div class="payment_option">
                                <div class="custome-radio" style="padding-top: 10px;" v-for="method in shipping_methods" :key="method.id">
                                    <input class="form-check-input" required="" type="radio" @change="updateShippingPrice(method.slug)" name="shipping_mode"
                                        :id="'shipping_mode_' + method.id" :value="method.id" v-model="form.shipping_mode">
                                    <label class="form-check-label" :for="'shipping_mode_' + method.id">@{{ method.name }} ( +
                                        @{{ $formatPrice(method.slug) }}) @{{ method.parent_estimate_relation[0]?.content  }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="payment-accordion-order-button" style="margin-bottom: 40px;">
                            <h4 class="mb-30">{{ __('Payment Method') }}</h4>
                            <div class="payment_option">
                                @foreach($gateways as $key => $row)
                                @php
                                $data=json_decode($row->content);
                                @endphp
                                <div class="custome-radio" style="padding-top: 10px;">
                                    <input class="form-check-input" required="" type="radio" name="payment_method"
                                        v-model="form.payment_method" id="payment_method_{{ $key }}" value="{{ $row->category_id  }}"
                                        @if($key==0) checked="checked" @endif>
                                    <label class="form-check-label" for="payment_method_{{ $key }}" data-bs-toggle="collapse"
                                        data-target="#{{ $data->title }}" aria-controls="{{ $data->title }}">{{ $data->title }}</label>
                                    @if(isset($data->additional_details))
                                    <div class="card card-body rounded-0 payment_method_info payment_method_{{ $key }}" style="display: none;margin-bottom: 20px;margin-top: 20px;">
                                        <p>{{ $data->additional_details }}</p>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="order-button-payment"@click="create_order()">
                            <button class="btn btn-fb btn-hover-primary rounded-0 w-100 btn btn-fill-out btn-block place-order-btn mt-30">{{ __('Place an Order') }}</button>
                        </div>
                        <!-- Payment Accordion Order Button End -->
                    </div>
                    <!-- Your Order Area End -->
                </div>
            </div>
        </div>
    </div>
</checkout-create>
@endsection
@push('js')
<script>
    $('input[name="payment_method"]').change(function ($e){
        $('.payment_method_info').hide();
        $('.' + $(this).attr("id")).show();
    });
</script>
@endpush
