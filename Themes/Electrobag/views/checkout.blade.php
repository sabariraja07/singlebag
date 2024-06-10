@extends('electrobag::layouts.app')
@push('css')
@endpush
@section('content')
<div class="container">
    <div class="empty-space col-xs-b15 col-sm-b30"></div>
    <div class="breadcrumbs">
        <a href="{{ url('/') }}" rel="nofollow">{{ __('Home') }}</a>
        <a href="{{ url('/checkout') }}">{{ __('Checkout') }}</a>
    </div>
    <div class="text-center">
        <div class="h2">checkout</div>
        <div class="simple-article size-3 grey uppercase col-xs-b5">
            <h6 class="text-body"
                v-html="$trans('There are :number products in your cart' , { 'number' : `<span class='text-brand'>` + (cart.count ?? 0 ) + `</span>`})">
            </h6>
        </div>
        <div class="title-underline center"><span></span></div>
    </div>
    <checkout-create :gateways="{{ $gateways }}" :locations="{{ $locations }}" inline-template>
        <div class="row">
            <div class="col-md-6 col-xs-b50 col-md-b0">

                <h4 class="h4 col-xs-b25">billing details</h4>
                <div class="row">
                    <form method="post">
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
                        <div class="col-lg-12">
                            @if(!Auth::guard('customer')->check())
                            <div class="toggle_info">
                                <span>
                                    <i class="fi-rs-user mr-10"></i>
                                    <span class="text-muted font-lg">
                                        {{ __('Already have an account?') }}
                                    </span>
                                    <a href="{{ url('/user/login') }}">{{ __('Click here to login') }}</a>
                                </span>
                            </div>
                            <div class="empty-space col-xs-b15 col-sm-b30"></div>
                            @else
                            <div class="toggle_info">
                                <span>
                                    <i class="fi-rs-user mr-10"></i>
                                    <span class="text-muted font-lg">
                                        {{ Auth::guard('customer')->user()->fullname }}
                                    </span>
                                </span>
                            </div>
                            <div class="empty-space col-xs-b15 col-sm-b30"></div>
                            {{-- <a class="button size-2 style-2" href="javascript:void(0)">
                                <span class="button-wrapper open-popup" data-rel="3">
                                    <span class="icon"><img src="{{asset('frontend/electrobag/img/icon-1.png')}}"
                                            alt=""></span>
                                    <span class="text">{{ Auth::guard('customer')->user()->fullname }}</span>
                                </span>
                            </a> --}}
                            <div class="empty-space col-xs-b35 col-md-b70"></div>
                            @endif
                        </div>
                        <div class="col-md-12 col-xs-b50 col-md-b0">
                            <div class="row m10">
                                <div class="col-sm-6">
                                    <input class="simple-input" type="text" v-model="form.first_name" name="first_name"
                                        placeholder="{{ __('First name *') }}" />
                                    <div class="invalid-feedback" class="invalid-first_name"></div>
                                    <div class="empty-space col-xs-b20"></div>
                                </div>
                                <div class="col-sm-6">
                                    <input class="simple-input" type="text" required="" v-model="form.last_name"
                                        name="last_name" placeholder="{{ __('Last name *') }}">
                                    <div class="invalid-feedback" class="invalid-last_name"></div>
                                    <div class="empty-space col-xs-b20"></div>
                                </div>
                            </div>
                        </div>
                        <input class="simple-input" type="text" name="delivery_address" v-model="form.delivery_address"
                            required="" placeholder="{{ __('Address *') }}">
                        <div class="invalid-feedback" class="invalid-delivery_address"></div>
                        <div class="empty-space col-xs-b20"></div>
                        <div class="row m10">
                            <div class="col-sm-6">
                                <single-select :options="{{ $select_locations }}" v-model="form.location"
                                    @change="get_shipping_methods()">
                                    <option disabled value>{{ __('Select City') }}</option>
                                </single-select>


                                <div class="invalid-feedback" class="invalid-location"></div>
                                <div class="empty-space col-xs-b20"></div>
                            </div>
                            <div class="col-sm-6">
                                <input class="simple-input" required="" type="text" name="phone" v-model="form.phone"
                                    placeholder="{{ __('Phone*') }}" @keypress="$isMobileNumber($event)" maxlength="10" minlength="10">
                                <div class="invalid-feedback" class="invalid-phone"></div>
                                <div class="empty-space col-xs-b20"></div>
                            </div>
                        </div>
                        <div class="row m10">
                            <div class="col-sm-6">
                                <input class="simple-input" required="" type="text" name="email" v-model="form.email"
                                    placeholder="{{ __('Email address *') }}">
                                <div class="invalid-feedback" class="invalid-email"></div>
                                <div class="empty-space col-xs-b20"></div>
                            </div>
                            <div class="col-sm-6">
                                <input class="simple-input" required="" type="text" name="zip_code"
                                    v-model="form.zip_code" placeholder="{{ __('Postcode / ZIP *') }}">

                                <div class="invalid-feedback" class="invalid-zip_code"></div>
                                <div class="empty-space col-xs-b20"></div>
                            </div>
                        </div>
                        <textarea class="simple-input" rows="5" v-model="form.comment" name="comment"
                            placeholder="{{ __('Additional information') }}"></textarea>
                        <div class="invalid-feedback" class="invalid-comment"></div>

                </div>
                @if(!Auth::guard('customer')->check())
                <label class="checkbox-entry checkbox-toggle-title">
                    <div class="empty-space col-xs-b20"></div>
                    <div class="empty-space col-xs-b20"></div>
                    <input type="checkbox"><span>{{ __('Create an account?') }}</span>
                </label>
                <div class="empty-space col-xs-b20"></div>
                <div class="checkbox-toggle-wrapper">
                    <div class="empty-space col-xs-b25"></div>
                    <input class="simple-input" required="" type="password" v-model="form.password"
                        placeholder="{{ __('Password') }}" name="password">
                </div>
                </form>

                @endif
                <div class="empty-space col-xs-b30"></div>
                <div class="row m10">
                    <div class="col-sm-6">
                        <input type="text" class="simple-input" name="coupon" v-model="coupon"
                            placeholder="{{ __('Enter Coupon Code...') }}">
                    </div>
                    <div class="col-sm-6">
                        <div for="applyCouponBtn" @click="applyCoupon()" class="button block size-2 style-3">
                            <span class="button-wrapper">
                                <span class="icon"><img src="{{asset('frontend/electrobag/img/icon-4.png')}}"
                                        alt=""></span>
                                <span class="text" class="update-coupon-btn"
                                    name="update-coupon-btn">{{ __('Apply Coupon') }}</span>
                            </span>
                            <input id="applyCouponBtn" type="submit" />
                        </div>
                    </div>
                </div>
                <div class="empty-space col-xs-b20"></div>
                <div class="empty-space col-xs-b20"></div>
            </div>
            <div class="col-md-6">
                <h4 class="h4 col-xs-b25">your order</h4>
                <div class="cart-entry clearfix">
                    <a class="cart-entry-thumbnail" href="javascript:void(0)"><img
                            src="{{asset('frontend/electrobag/img/product-1.png')}}" alt=""></a>
                    <div class="cart-entry-description1">
                        <table>
                            <tbody>
                                @foreach(Cart::content() as $row)
                                <tr>
                                    <td><img width="85" height="85"
                                            src="{{ asset($row->options ? $row->attributes->image : '') }}" alt="#"></td>
                                    <td>
                                        <div class="h6"><a
                                                href="{{ url('/product/'.$row->name.'/'.$row->id) }}">{{ $row->name }}</a>
                                        </div>
                                        <div class="simple-article size-1">QUANTITY:{{ $row->qty }}</div>
                                    </td>
                                    <td>
                                        <div class="simple-article size-3 grey">
                                            {{ amount_format($row->qty * $row->price) }}</div>
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="order-details-entry simple-article size-3 grey uppercase">
                    <div class="row">
                        <div class="col-xs-6">
                            {{ __('Price Total') }}
                        </div>
                        <div class="col-xs-6 col-xs-text-right">
                            <div class="color">@{{ $formatPrice(cart.priceTotal) }}</div>
                        </div>
                    </div>
                </div> --}}
                <div class="order-details-entry simple-article size-3 grey uppercase">
                    <div class="row">
                        <div class="col-xs-6">
                            {{ __('Discount') }}
                        </div>
                        <div class="col-xs-6 col-xs-text-right">
                            <div class="color">@{{ $formatPrice(cart.discount) }}</div>
                        </div>
                    </div>
                </div>
                <div class="order-details-entry simple-article size-3 grey uppercase">
                    <div class="row">
                        <div class="col-xs-6">
                            {{ __('Tax') }}
                        </div>
                        <div class="col-xs-6 col-xs-text-right">
                            <div class="color">@{{ $formatPrice(cart.tax) }}</div>
                        </div>
                    </div>
                </div>
                <div class="order-details-entry simple-article size-3 grey uppercase">
                    <div class="row">
                        <div class="col-xs-6">
                            {{ __('Subtotal') }}
                        </div>
                        <div class="col-xs-6 col-xs-text-right">
                            <div class="color">@{{ $formatPrice(cart.subtotal) }}</div>
                        </div>
                    </div>
                </div>
                <div class="order-details-entry simple-article size-3 grey uppercase">
                    <div class="row">
                        <div class="col-xs-6">
                            {{ __('Total') }}
                        </div>
                        <div class="col-xs-6 col-xs-text-right">
                            <input type="hidden" name="total" id="cart-total" value="{{Cart::total()}}">
                            <div class="color" id="cart_total">
                                @{{ $formatPrice(parseFloat(cart.total) +  shipping_price) }}</div>
                        </div>
                    </div>
                </div>
                <div class="empty-space col-xs-b50"></div>
                <h4 class="h4 col-xs-b25">{{ __('Shipping Method') }}</h4>
                <div class="custome-radio" v-for="method in shipping_methods" :key="method.id">
                    <label class="checkbox-entry radio" :for="'shipping_mode_' + method.id">
                        <input type="radio" required="" type="radio" @change="updateShippingPrice(method.slug)"
                            name="shipping_mode" :id="'shipping_mode_' + method.id" :value="method.id"
                            v-model="form.shipping_mode"><span>@{{ method.name }} ( +
                            @{{ $formatPrice(method.slug) }}) @{{ method.parent_estimate_relation[0]?.content  }}</span>
                    </label>
                </div>
                <div class="empty-space col-xs-b50"></div>
                <h4 class="h4 col-xs-b25">{{ __('Payment Method') }}</h4>
                @foreach($gateways as $key => $row)
                @php
                $data=json_decode($row->content);
                @endphp
                <div class="custome-radio">
                    <label class="checkbox-entry radio">
                        <input type="radio" required="" type="radio" name="payment_method" v-model="form.payment_method"
                            id="payment_method_{{ $key }}" value="{{ $row->category_id  }}" @if($key==0)
                            checked="checked" @endif><span>{{ $data->title }}</span>
                    </label>
                    @if(isset($data->additional_details))
                    <div class="simple-article size-2" style="margin-left: 28px;">
                        <div class="payment_box payment_method_{{ $key }}">
                            <p>{{ $data->additional_details }}</p>
                        </div>
                    </div>

                    <div class="empty-space col-xs-b20"></div>
                    @endif
                </div>
                @endforeach


                <div class="empty-space col-xs-b30"></div>

                <a class="button block size-2 style-3" @click="create_order()">
                    <span class="button-wrapper">
                        <span class="icon"><img src="{{asset('frontend/electrobag/img/icon-4.png')}}" alt=""></span>
                        <span class="text">{{ __('Place an Order') }}</span>
                    </span>
                </a>
                <div class="empty-space col-xs-b30"></div>
                <div class="empty-space col-xs-b30"></div>


            </div>
        </div>
    </checkout-create>

</div>
@endsection
@push('js')
<!-- <script src="{{ asset('frontend/singlebag/js/checkout.js') }}"></script> -->
@endpush
