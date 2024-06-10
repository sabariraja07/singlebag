@extends('multibag::layouts.app')
@push('css')
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
    <div class="checkout-area bg-gray pt-50 pb-160">
        <div class="container">
            <div class="col-lg-12">
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
            <div class="row">
                <div class="col-lg-6">
                    <div class="checkout-left-wrap">
                        @if(!Auth::guard('customer')->check())
                        <div class="login-guest-top">
                            <div class="tab-content">
                                <div id="checkout-login" class="tab-pane active">
                                    <div class="checkout-login-wrap">
                                        <h4>Login information</h4>
                                        <p class="mb-30 font-sm">
                                            {{ __('If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing & Shipping section.') }}
                                        </p>
                                        <div class="checkout-login-style">
                                            <form action="{{ url('/customer/login') }}" method="post">
                                                @csrf
                                                <input type="text" class="@error('email') is-invalid @enderror" name="email"
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
                                                <input type="password" name="password" placeholder="{{ __('Password') }}" required="">
                                                <div class="checkout-button-box">
                                                    <div class="checkout-login-toggle-btn">
                                                        <input type="checkbox" type="checkbox" name="remember"
                                                        id="remember" value="">
                                                        <label>{{ __('Remember Me') }}</label>
                                                        <a href="{{ url('/user/password/reset') }}">{{ __('Forgot Password ?') }}</a>
                                                    </div>
                                                    <button type="submit" name="login">{{ __('Sign In') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="shipping-address-wrap">
                            <h4 class="checkout-title">{{ __('Billing Details') }}</h4>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="billing-info">
                                        <input type="text" required="" v-model="form.first_name" name="first_name"
                                        placeholder="{{ __('First name *') }}">
                                        <div class="invalid-feedback" class="invalid-first_name"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="billing-info">
                                        <input type="text" required="" v-model="form.last_name" name="last_name"
                                        placeholder="{{ __('Last name *') }}">
                                        <div class="invalid-feedback" class="invalid-last_name"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="billing-info">
                                        <input type="text" name="delivery_address" v-model="form.delivery_address"
                                        required="" placeholder="{{ __('Address *') }}">
                                        <div class="invalid-feedback" class="invalid-delivery_address"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="billing-info">
                                        <input required="" type="text" name="phone" id="mobile_number" v-model="form.phone"
                                        placeholder="{{ __('Phone*') }}" @keypress="$isMobileNumber($event)" maxlength="10" minlength="10">
                                        <div class="invalid-feedback" class="invalid-phone"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="billing-info">
                                        <input required="" type="text" name="email" v-model="form.email"
                                        placeholder="{{ __('Email address *') }}">
                                        <div class="invalid-feedback" class="invalid-email"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="billing-info">
                                        <textarea rows="5" v-model="form.comment" name="comment"
                                        placeholder="{{ __('Additional information') }}"></textarea>
                                        <div class="invalid-feedback" class="invalid-comment"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <single-select :options="{{ $select_locations }}" v-model="form.location"
                                        @change="get_shipping_methods()">
                                        <option disabled value>{{ __('Select City') }}</option>
                                    </single-select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="billing-info">
                                        <input required="" type="text" name="zip_code" v-model="form.zip_code"
                                        placeholder="{{ __('Postcode / ZIP *') }}">
                                        <div class="invalid-feedback" class="invalid-zip_code"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="checkout-save-info mb-30">
                                        <input class="checkout-checkbox2" type="checkbox" v-model="form.create_account"
                                        name="checkbox" id="createaccount">
                                        <label class="pl-10" for="createaccount"> {{ __('Create an account?') }}</label>
                                    </div>
                                    <div class="form-group create-account" :class="form.create_account == 1 ? '' : 'd-none'">
                                        <div class="col-lg-12">
                                            <input required="" type="password" v-model="form.password"
                                                placeholder="{{ __('Password') }}" name="password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="payment-details mb-40">
                        <h4 class="checkout-title">{{ __('Payment Details') }}</h4>
                        <ul>
                            {{-- <li>{{ __('Price Total') }}<span>@{{ $formatPrice(cart.priceTotal) }}</span></li> --}}
                            <li>{{ __('Discount') }}<span>@{{ $formatPrice(cart.discount) }}</span></li>
                            <li>{{ __('Tax') }}<span>@{{ $formatPrice(cart.tax) }}</span></li>
                            <li>{{ __('Subtotal') }}<span>@{{ $formatPrice(cart.subtotal) }}</span></li>
                        </ul>
                        <div class="total-order">
                            <ul>
                                <input type="hidden"  name="total" id="cart-total" value="{{Cart::total()}}">
                                <li>{{ __('Total') }}<span id="cart_total">@{{ $formatPrice(parseFloat(cart.total) +  shipping_price) }}</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="discount-code-wrapper discount-tax-wrap mb-40">
                        <h4>{{ __('Apply Coupon') }}</h4>
                        <div class="discount-code">
                            <p>{{ __('Using A Promo Code?') }}</p>
                            <form action="#" @submit.prevent="applyCoupon()">
                                <input type="text" class="coupon" required="" placeholder="{{ __('Enter Your Coupon') }}" name="coupon" v-model="coupon">
                                <button type="submit">{{ __('Apply Coupon') }}</button>
                            </form>
                        </div>
                    </div>
                    <div class="payment-details">
                        <h4 class="checkout-title">{{ __('Payment Method') }}</h4>
                        <div class="payment-method">
                            @foreach($gateways as $key => $row)
                            @php
                            $data=json_decode($row->content);
                            @endphp
                            <div class="pay-top sin-payment">
                                <input class="input-radio" required="" type="radio" name="payment_method"
                                    v-model="form.payment_method" id="payment_method_{{ $key }}" value="{{ $row->category_id  }}"
                                    @if($key==0) checked="checked" @endif>
                                <label for="payment_method_{{ $key }}" data-bs-toggle="collapse"
                                    data-target="#{{ $data->title }}" aria-controls="{{ $data->title }}">{{ $data->title }}</label>
                                    @if(isset($data->additional_details))
                                    <div class="payment-box payment_method_bacs">
                                        <p>{{ $data->additional_details }}</p>
                                    </div>
                                    @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="payment-details" v-if="shipping_methods.length">
                        <h4 class="checkout-title">{{ __('Shipping Method') }}</h4>
                        <div class="payment-method">
                            <div class="pay-top sin-payment" v-for="method in shipping_methods" :key="method.id">
                                <input class="input-radio" type="radio" @change="updateShippingPrice(method.slug)" name="shipping_mode"
                                :id="'shipping_mode_' + method.id" :value="method.id" v-model="form.shipping_mode" required="">
                                <label :for="'shipping_mode_' + method.id">@{{ method.name }} ( + @{{ $formatPrice(method.slug) }}) @{{ method.parent_estimate_relation[0]?.content  }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="back-continue-wrap">
                <a href="{{ url('/cart') }}" class="btn btn-fill-out mt-30">{{ __('Back to cart') }}</a>
                <a href="javascript::void(0)" @click="create_order()" class="btn btn-fill-out btn-block place-order-btn mt-30">{{ __('Place an Order') }}</a>
            </div>
        </div>
    </div>
</checkout-create>
@endsection
