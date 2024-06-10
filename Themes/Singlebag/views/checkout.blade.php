@extends('singlebag::layouts.app')
@push('css')
@endpush
@section('content')
<checkout-create :gateways="{{ $gateways }}"
    :locations="{{ $locations }}" inline-template>
    <div>
        <div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="{{ url('/') }}" rel="nofollow"><i class="fi-rs-home mr-5"></i>{{ __('Home') }}</a>
                    <span></span> {{ __('Checkout') }}
                </div>
            </div>
        </div>
        <div class="container mb-80 mt-50">
            <div class="row">
                <div class="col-lg-8 mb-40">
                    <h1 class="heading-2 mb-10">{{ __('Checkout') }}</h1>
                    <div class="d-flex justify-content-between">
                        <h6 class="text-body" v-html="$trans('There are :number products in your cart' , { 'number' : `<span class='text-brand'>` + (cart.count ?? 0 ) + `</span>`})">
                
                        </h6>
                    </div>
                </div>
            </div>
            <div class="row">
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
                <div class="col-lg-7">
                    <div class="row mb-50">
                        @if(!Auth::guard('customer')->check())
                        <div class="col-lg-6 mb-sm-15 mb-lg-0 mb-md-3">
                            <div class="toggle_info">
                                <span>
                                    <i class="fi-rs-user mr-10"></i>
                                    <span class="text-muted font-lg">
                                        {{ __('Already have an account?') }}
                                    </span>
                                    <a href="#loginform" data-bs-toggle="collapse" class="collapsed font-lg"
                                        aria-expanded="false">{{ __('Click here to login') }}</a>
                                </span>
                            </div>
                            <div class="panel-collapse collapse login_form" id="loginform">
                                <div class="panel-body">
                                    <p class="mb-30 font-sm">
                                        {{ __('If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing & Shipping section.') }}
                                    </p>
                                    <form action="{{ url('/customer/login') }}" method="post">
                                        @csrf
                                        <div class="form-group">
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
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" placeholder="{{ __('Password') }}" required="">
                                        </div>
                                        <div class="login_footer form-group">
                                            <div class="chek-form">
                                                <div class="custome-checkbox">
                                                    <input class="form-check-input" type="checkbox" name="remember"
                                                        id="remember" value="">
                                                    <label class="form-check-label" for="remember">
                                                        <span>{{ __('Remember Me') }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <a href="{{ url('/user/password/reset') }}">{{ __('Forgot Password ?') }}</a>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-md" name="login">{{ __('Sign In') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-lg-6 mb-sm-15 mb-lg-0 mb-md-3">
                            <div class="toggle_info">
                                <span>
                                    <i class="fi-rs-user mr-10"></i>
                                    <span
                                        class="text-muted font-lg">{{ Auth::guard('customer')->user()->fullname }}</span>
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <h4 class="mb-30">{{ __('Billing Details') }}</h4>
                        <form method="post">
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="">{{ __('First Name') }}</label>
                                    <input type="text" required="" v-model="form.first_name" name="first_name"
                                        placeholder="{{ __('First name *') }}">
                                    <div class="invalid-feedback" class="invalid-first_name"></div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="">{{ __('Last Name') }}</label>
                                    <input type="text" required="" v-model="form.last_name" name="last_name"
                                        placeholder="{{ __('Last name *') }}">
                                    <div class="invalid-feedback" class="invalid-last_name"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <input type="text" name="delivery_address" v-model="form.delivery_address"
                                        required="" placeholder="{{ __('Address *') }}">
                                    <div class="invalid-feedback" class="invalid-delivery_address"></div>
                                </div>
                                <!-- <div class="form-group col-lg-6">
                                <input type="text" name="billing_address2" required="" placeholder="Address line2">
                            </div> -->
                            </div>
                            <div class="row shipping_calculator">
                                <div class="form-group col-lg-6">
                                    <div class="custom_select">
                                        <single-select :options="{{ $select_locations }}" v-model="form.location"
                                            @change="get_shipping_methods()">
                                            <option disabled value>{{ __('Select City') }}</option>
                                        </single-select>
                                    </div>
                                    <div class="invalid-feedback" class="invalid-location"></div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <input required="" type="text" name="phone" id="mobile_number" v-model="form.phone"
                                        placeholder="{{ __('Phone*') }}" @keypress="$isMobileNumber($event)" maxlength="10" minlength="10">
                                    <div class="invalid-feedback" class="invalid-phone"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <input required="" type="text" name="email" v-model="form.email"
                                        placeholder="{{ __('Email address *') }}">
                                    <div class="invalid-feedback" class="invalid-email"></div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <input required="" type="text" name="zip_code" v-model="form.zip_code"
                                        placeholder="{{ __('Postcode / ZIP *') }}">
                                    <div class="invalid-feedback" class="invalid-zip_code"></div>
                                </div>
                            </div>
                            <div class="form-group mb-30">
                                <textarea rows="5" v-model="form.comment" name="comment"
                                    placeholder="{{ __('Additional information') }}"></textarea>
                                <div class="invalid-feedback" class="invalid-comment"></div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <div class="custome-checkbox">
                                        <input class="form-check-input" v-model="form.create_account" type="checkbox"
                                            name="checkbox" id="createaccount">
                                        <label class="form-check-label label_info" data-bs-toggle="collapse"
                                            href="#collapsePassword" data-target="#collapsePassword"
                                            aria-controls="collapsePassword" for="createaccount"><span>
                                                {{ __('Create an account?') }}</span></label>
                                    </div>
                                </div>
                            </div>
                            <div id="collapsePassword" class="form-group create-account collapse in">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input required="" type="password" v-model="form.password"
                                            placeholder="{{ __('Password') }}" name="password">
                                    </div>
                                </div>
                            </div>
                           
    </form>
    </div>
    <div class="row mt-50">
        <div class="col-lg-6">
            <form method="post" @submit.prevent="applyCoupon()" class="apply-coupon">
                <input type="text" name="coupon" v-model="coupon" placeholder="{{ __('Enter Coupon Code...') }}">
                <button class="btn btn-md update-coupon-btn" name="update-coupon-btn">{{ __('Apply Coupon') }}</button>
            </form>
        </div>
    </div>
    </div>
    <div class="col-lg-5">
        <div class="border p-40 cart-totals ml-30 mb-20">
            <div class="d-flex align-items-end justify-content-between mb-30">
                <h4>{{ __('Your Order') }}</h4>
            </div>
            <div class="divider-2 mb-30"></div>
            <div class="table-responsive order_table checkout">
                <table class="table no-border">
                    <tbody>
                        @foreach(Cart::content() as $row)
                        <tr>
                            <td class="image product-thumbnail">
                                <img src="{{ asset($row->options ? $row->attributes->image : '') }}" alt="#"></td>
                            <td>
                                <h6 class="w-160 mb-5"><a href="{{ url('/product/'.$row->name.'/'.$row->id) }}"
                                       >{{ $row->name }}</a></h6></span>
                                       @foreach ($row->attributes->options as $item)
                                       <p>{{ $item->name }}</p>
                                       @if($loop->last)
                                       <br>
                                       @endif
                                       @endforeach
                            </td>
                            <td>
                                <h6 class="text-muted pl-20 pr-20">x {{ $row->qty }}</h6>
                            </td>
                            <td>
                                <h4 class="text-brand">{{ amount_format($row->qty * $row->price) }}</h4>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="border p-md-4 cart-totals ml-30 mb-50">
            <div class="table-responsive">
                {{-- <table class="table no-border">
                    <tbody>
                        <tr>
                            <td class="cart_total_label">
                                <h6 class="text-muted">{{ __('Price Total') }}</h6>
                            </td>
                            <td class="cart_total_amount">
                                <h6 class="text-heading text-end">{{ amount_format(Cart::priceTotal()) }}</h6>
                            </td>
                        </tr>
                        <tr>
                            <td class="cart_total_label">
                                <h6 class="text-muted">{{ __('Discount') }}</h6>
                            </td>
                            <td class="cart_total_amount">
                                <h6 class="text-heading text-end">{{ amount_format(Cart::discount()) }}</h6>
                            </td>
                        </tr>
                        <tr>
                            <td class="cart_total_label">
                                <h6 class="text-muted">{{ __('Tax') }}</h6>
                            </td>
                            <td class="cart_total_amount">
                                <h6 class="text-heading text-end">{{ amount_format(Cart::tax()) }}</h6>
                            </td>
                        </tr>
                        <tr>
                            <td class="cart_total_label">
                                <h6 class="text-muted">{{ __('Subtotal') }}</h6>
                            </td>
                            <td class="cart_total_amount">
                                <h6 class="text-heading text-end">{{ amount_format(Cart::subtotal()) }}</h6>
                            </td>
                        </tr>
                        <tr>
                            <td scope="col" colspan="2">
                                <div class="divider-2 mt-10 mb-10"></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="cart_total_label">
                                <h6 class="text-muted">{{ __('Total') }}</h6>
                            </td>
                            <td class="cart_total_amount">
                                <h4 class="text-brand text-end" id="cart_total">{{ amount_format(Cart::total()) }}</h4>
                                <input type="hidden"  name="total" id="cart-total" value="{{Cart::total()}}">
                            </td>
                        </tr>
                    </tbody>
                </table> --}}
                <table class="table no-border">
                    <tbody>
                        {{-- <tr>
                            <td class="cart_total_label">
                                <h6 class="text-muted">{{ __('Price Total') }}</h6>
                            </td>
                            <td class="cart_total_amount">
                                <h6 class="text-heading text-end">@{{ $formatPrice(cart.priceTotal) }}</h6>
                            </td>
                        </tr> --}}
                        <tr>
                            <td class="cart_total_label">
                                <h6 class="text-muted">{{ __('Discount') }}</h6>
                            </td>
                            <td class="cart_total_amount">
                                <h6 class="text-heading text-end">@{{ $formatPrice(cart.discount) }}</h6>
                            </td>
                        </tr>
                        <tr>
                            <td class="cart_total_label">
                                <h6 class="text-muted">{{ __('Tax') }}</h6>
                            </td>
                            <td class="cart_total_amount">
                                <h6 class="text-heading text-end">@{{ $formatPrice(cart.tax) }}</h6>
                            </td>
                        </tr>
                        <tr>
                            <td class="cart_total_label">
                                <h6 class="text-muted">{{ __('Subtotal') }}</h6>
                            </td>
                            <td class="cart_total_amount">
                                <h6 class="text-heading text-end">@{{ $formatPrice(cart.subtotal) }}</h6>
                            </td>
                        </tr>
                        <tr>
                            <td scope="col" colspan="2">
                                <div class="divider-2 mt-10 mb-10"></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="cart_total_label">
                                <h6 class="text-muted">{{ __('Total') }}</h6>
                            </td>
                            <td class="cart_total_amount">
                                <input type="hidden"  name="total" id="cart-total" value="{{Cart::total()}}">
                                <h4 class="text-brand text-end" id="cart_total">@{{ $formatPrice(parseFloat(cart.total) +  shipping_price) }}</h4>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="payment ml-30 mb-50" v-if="shipping_methods.length">
            <h4 class="mb-30">{{ __('Shipping Method') }}</h4>
            <div class="payment_option">
                <div class="custome-radio" v-for="method in shipping_methods" :key="method.id">
                    <input class="form-check-input" required="" type="radio" @change="updateShippingPrice(method.slug)" name="shipping_mode"
                        :id="'shipping_mode_' + method.id" :value="method.id" v-model="form.shipping_mode">
                    <label class="form-check-label" :for="'shipping_mode_' + method.id">@{{ method.name }} ( +
                        @{{ $formatPrice(method.slug) }})@{{ method.parent_estimate_relation[0]?.content  }}</label>
                </div>
            </div>
        </div>
        <div class="payment ml-30">
            <h4 class="mb-30">{{ __('Payment Method') }}</h4>
            <div class="payment_option">
                @foreach($gateways as $key => $row)
                @php
                $data=json_decode($row->content);
                @endphp
                <div class="custome-radio">
                    <input class="form-check-input" required="" type="radio" name="payment_method"
                        v-model="form.payment_method" id="payment_method_{{ $key }}" value="{{ $row->category_id  }}"
                        @if($key==0) checked="checked" @endif>
                    <label class="form-check-label" for="payment_method_{{ $key }}" data-bs-toggle="collapse"
                        data-target="#{{ $data->title }}" aria-controls="{{ $data->title }}">{{ $data->title }}</label>
                    @if(isset($data->additional_details))
                    <div class="payment_box payment_method_{{ $key }}">
                        <p>{{ $data->additional_details }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            {{--
                    <div class="payment-logo d-flex">
                        <img class="mr-15" src="assets/imgs/theme/icons/payment-paypal.svg" alt="">
                        <img class="mr-15" src="assets/imgs/theme/icons/payment-visa.svg" alt="">
                        <img class="mr-15" src="assets/imgs/theme/icons/payment-master.svg" alt="">
                        <img src="assets/imgs/theme/icons/payment-zapper.svg" alt="">
                    </div>
                    --}}
            <a @click="create_order()" class="btn btn-fill-out btn-block place-order-btn mt-30">
                {{ __('Place an Order') }}
                <i class="fi-rs-sign-out ml-15"></i>
            </a>
        </div>
    </div>
    </div>
    </div>
    </div>
</checkout-create>
@endsection
@push('js')
<!-- <script src="{{ asset('frontend/singlebag/js/checkout.js') }}"></script> -->
@endpush
