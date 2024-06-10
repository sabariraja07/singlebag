<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Checkout</title>
    <!-- Template CSS -->
    <link href="{{ asset('frontend/fashionbag/css/style.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <script>
        window.singlebag = {
            baseUrl: '{{ url("/") }}',
            rtl: false,
            storeName: '{{ env("APP_NAME") }}',
            loggedIn: '{{ auth()->guard('customer')->check()? 1: 0 }}',
            csrfToken: '{{ csrf_token() }}',
            langs: {!! get_lang_trans() !!},
            currency: {!! json_encode(currency_info(), true) !!},
            theme_color: '{{ Cache::get(domain_info("shop_id") . "theme_color", "#dc3545") }}'
        };
    </script>
    <style>
        .HeaderLogo {
            margin: 20px;
            text-align: center;
        }

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
            padding-left: 10px;
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
    @include('layouts.partials.analytics')
</head>

<body>
    <div class="HeaderLogo">
        <a href="/"><img src="{{ current_shop_logo_url() }}" alt="Logo" width="150px"></a>
    </div>
    <div class="section">
        <div class="breadcrumb-area bg-light">
            <div class="container-fluid">
                <div class="breadcrumb-content text-center">
                    <h1 class="title">{{ __('Checkout') }}</h1>
                    <ul>
                        <li>
                            <a href="/">{{ __('Home') }}</a>
                        </li>
                        <li class="active">{{ __('Checkout') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if(domain_info('shop_type') != 'reseller')
    <div class="section section-margin">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @if ($errors->has('email'))
                    <div class="alert alert-danger" role="alert">
                        {{ $errors->first('email') }}
                    </div>
                    @endif
                    @if (Session::get('message'))
                    <div class="alert alert-danger" role="alert">
                        {{ Session::get('message') }}
                    </div>
                    @endif
                </div>
                <div class="col-12">
                    <!-- Coupon Accordion Start -->
                    <div class="coupon-accordion">
                        @if (!Auth::guard('customer')->check())
                        <!-- Title Start -->
                        <h3 class="title">{{ __('Already have an account?') }} <span id="showlogin"
                                style=" color: blue; ">{{ __('Click here to login') }}</span></h3>
                        <!-- Title End -->
                        <!-- Checkout Login Start -->
                        <div id="checkout-login" class="coupon-content">
                            <div class="coupon-info">
                                <p class="coupon-text mb-2">
                                    {{ __('If you have shopped with us before, please enter your details below. If you
                                    are a new customer, please proceed to the Billing & Shipping section.') }}
                                </p>
                                <!-- Form Start -->
                                <form action="{{ url('/customer/login') }}" method="post">
                                    @csrf
                                    <!-- Input Email Start -->
                                    <p class="form-row-first">
                                        <label>{{ __('Email') }} <span class="required">*</span>
                                        </label>
                                        <input type="text" class="" name="email" placeholder="{{ __('Email') }}"
                                            value="" required="">
                                        @if ($errors->has('email'))
                                    <div class="invalid-feedback" style="display: block;">
                                        {{ $errors->first('email') }}
                                    </div>
                                    @endif
                                    @if (Session::get('message'))
                                    <div class="invalid-feedback" style="display: block;">
                                        {{ Session::get('message') }}
                                    </div>
                                    @endif
                                    </p>
                                    <!-- Input Email End -->
                                    <!-- Input Password Start -->
                                    <p class="form-row-last">
                                        <label>{{ __('Password') }}<span class="required">*</span>
                                        </label>
                                        <input type="password" name="password" placeholder="{{ __('Password') }}"
                                            required="">
                                    </p>
                                    <!-- Input Password End -->
                                    <!-- Remember Password Start -->
                                    <p class="form-row mb-2">
                                        <input type="checkbox" id="remember_me" name="remember">
                                        <label for="remember_me" class="checkbox-label">{{ __('Remember Me') }}</label>
                                    </p>
                                    <!-- Remember Password End -->
                                    <!-- Lost Password Start -->
                                    <p class="lost-password">
                                        <a href="{{ url('/user/password/reset') }}">{{ __('Forgot Password ?') }}</a>>
                                    </p>
                                    <!-- Lost Password End -->
                                    <button class="btn btn-primary btn-hover-primary rounded-0" name="login">{{ __('Sign
                                        In') }}</button>
                                </form>
                                <!-- Form End -->
                            </div>
                        </div>
                        <!-- Checkout Login End -->
                        @endif
                        @if (domain_info('shop_type') != 'reseller')
                        <!-- Title Start -->
                        <h3 class="title">{{ __('Have a coupon?') }} <span id="showcoupon" style=" color: blue; ">{{
                                __('Click here to enter your code') }}</span></h3>
                        <!-- Title End -->
                        @endif
                        <!-- Checkout Coupon Start -->
                        <div id="checkout_coupon" class="coupon-checkout-content">
                            <div class="coupon-info">
                                <form action="{{ url('/apply_coupon') }}" method="post" id="coupon_form">
                                    @csrf
                                    <p class="checkout-coupon d-flex">
                                        <input name="code" type="text" placeholder="{{ __('Enter Coupon Code...') }}">
                                        <button type="submit" class="btn btn-primary btn-hover-primary rounded-0"
                                            name="update-coupon-btn">{{ __('Apply Coupon') }}</button>
                                    </p>
                                </form>
                            </div>
                        </div>
                        <!-- Checkout Coupon End -->
                    </div>
                    <!-- Coupon Accordion End -->
                </div>
            </div>
            <form
                action="{{ domain_info('shop_type') == 'reseller' ? url('/make-reseller-order') : url('/make_order') }}"
                method="post" id="make_order">
                @csrf
                <div class="row mb-n4">
                    <div class="col-lg-6 col-12 mb-40">
                        <!-- Checkbox Form Start -->
                        <div class="checkbox-form">
                            <!-- Checkbox Form Title Start -->
                            <h3 class="title">{{ __('Billing Details') }}</h3>
                            <!-- Checkbox Form Title End -->
                            <div class="row">
                                <!-- First Name Input Start -->
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>{{ __('First Name') }}<span class="required">*</span>
                                        </label>
                                        <input name="first_name" placeholder="{{ __('First name *') }}" required
                                            type="text">
                                    </div>
                                </div>
                                <!-- First Name Input End -->
                                <!-- Last Name Input Start -->
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>{{ __('Last Name') }}<span class="required">*</span>
                                        </label>
                                        <input name="last_name" placeholder="{{ __('Last name *') }}" required
                                            type="text">
                                    </div>
                                </div>
                                <!-- Last Name Input End -->
                                <!-- Address Input Start -->
                                <div class="col-md-12">
                                    <div class="checkout-form-list">
                                        <label>{{ __('Address') }} <span class="required">*</span>
                                        </label>
                                        <input type="text" name="delivery_address" required=""
                                            placeholder="{{ __('Address *') }}">
                                    </div>
                                </div>
                                <!-- Address Input End -->
                                <div class="col-md-12">
                                    <div class="checkout-form-list">
                                        <label>{{ __('Phone') }} <span class="required">*</span>
                                        </label>
                                        <input required="" type="text" name="phone" id="mobile_number"
                                            placeholder="{{ __('Phone*') }}" maxlength="10" minlength="10">
                                    </div>
                                </div>
                                <!-- Postcode or Zip Input Start -->
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>{{ __('Postcode / ZIP') }}<span class="required">*</span>
                                        </label>
                                        <input required="" type="text" name="zip_code"
                                            placeholder="{{ __('Postcode / ZIP *') }}">
                                    </div>
                                </div>
                                <!-- Postcode or Zip Input End -->
                                <!-- Email Address Input Start -->
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>{{ __('Email address') }}<span class="required">*</span>
                                        </label>
                                        <input required="" type="email" name="email"
                                            placeholder="{{ __('Email address *') }}">
                                    </div>
                                </div>
                                <!-- Email Address Input End -->
                                <!-- Select Country Name Start -->
                                <div class="col-md-12">
                                    <div class="country-select">
                                        <label>{{ __('Select City') }}<span class="required">*</span>
                                        </label>
                                        <select name="location" id="city" class="select2 form-select">
                                        </select>
                                    </div>
                                </div>
                                <!-- Select Country Name End -->
                                <!-- Checkout Form List checkbox Start -->
                                <div class="col-md-12">
                                    <div class="checkout-form-list create-acc">
                                        <input id="cbox" type="checkbox" name="create_account" value="1">
                                        <label for="cbox" class="checkbox-label">{{ __('Create an account?') }}</label>
                                    </div>
                                    <div id="cbox-info" class="checkout-form-list create-account"
                                        style="display: none;">
                                        <label>{{ __('Password') }} <span class="required">*</span>
                                        </label>
                                        <input type="password" placeholder="{{ __('Password') }}" name="password">
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
                                        <textarea id="checkout-mess" cols="30" rows="10" name="comment"
                                            placeholder="{{ __('Additional information') }}"></textarea>
                                    </div>
                                </div>
                                <!-- Order Notes Textarea End -->
                            </div>
                            <!-- Different Address End -->
                        </div>
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
                                        @php
                                        $cart = get_cart(true);
                                        @endphp
                                        @foreach ($cart['items'] as $row)
                                        <tr class="cart_item">
                                            <td class="cart-product-name text-start text-dark ps-0">
                                                {{ $row->name }}<strong class="product-quantity"> ×
                                                    {{ $row->quantity }}</strong>
                                                <br />
                                                @foreach ($row->attributes->options as $item)
                                                <p>{{ $item->name }}</p>
                                                @if ($loop->last)
                                                <br>
                                                @endif
                                                @endforeach
                                                <small
                                                    class="text-black-50 cart-item estimated-delivery-{{ $row->attributes->product_id }}"></small>
                                                @if (product_shipping_availability($row->attributes->product_id) == 0)
                                                <small
                                                    class="text-danger cart-item cart-item-{{ $row->attributes->product_id }}">
                                                    <b>Out Of stock.</b>
                                                    <input type="hidden" class="out_of_stock" value="1">
                                                </small>
                                                @else
                                                <small
                                                    class="text-danger cart-item cart-item-{{ $row->attributes->product_id }}"></small>
                                                <input type="hidden" class="out_of_stock" value="0">
                                                @endif
                                            </td>
                                            <td class="cart-product-total text-end pe-0">
                                                <span class="amount">{{ amount_format($row->quantity * $row->price)
                                                    }}</span>
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
                                                <strong class="amount cart_price_total">
                                                    {{ amount_format($cart['priceTotal']) }}
                                                </strong>
                                            </td>
                                        </tr> --}}

                                        <tr class="cart-subtotal">
                                            <th class="text-start ps-0">{{ __('Subtotal') }}</th>
                                            <td class="text-end pe-0">
                                                <strong class="amount cart_subtotal">
                                                    {{ amount_format($cart['subtotal']) }}
                                                </strong>
                                            </td>
                                        </tr>
                                        @if (domain_info('shop_type') != 'reseller')
                                        <tr class="cart-subtotal">
                                            <th class="text-start ps-0">{{ __('Discount') }}</th>
                                            <td class="text-end pe-0">
                                                <strong class="amount cart_discount">
                                                    {{ amount_format($cart['discount']) }}
                                                </strong>
                                            </td>
                                        </tr>
                                        @endif
                                        <tr class="cart-subtotal">
                                            <th class="text-start ps-0">{{ __('Tax') }}</th>
                                            <td class="text-end pe-0">
                                                <strong class="amount cart_tax">
                                                    {{ amount_format($cart['tax']) }}
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr class="shipping-cost">
                                            <th class="text-start ps-0">{{ __('Shipping Cost') }}</th>
                                            <td class="text-end pe-0">
                                                <strong class="amount shipping_cost">{{ amount_format(0) }}</strong>
                                            </td>
                                        </tr>
                                        <tr class="order-total">
                                            <th class="text-start ps-0">{{ __('Total') }}</th>
                                            <td class="text-end pe-0">
                                                <input type="hidden" name="total" id="cart-total"
                                                    value="{{ $cart['total'] }}">
                                                <strong class="amount cart_total">
                                                    {{ amount_format($cart['total']) }}
                                                </strong>
                                            </td>
                                        </tr>
                                    </tfoot>
                                    <!-- Table Footer End -->
                                </table>
                            </div>
                            <!-- Your Order Table End -->
                            @if (domain_info('shop_type') != 'reseller')
                            <div class="payment-accordion-order-button d-none" id="shipping_mode_parent_div"
                                style="margin-bottom: 40px;">
                                <h4 class="mb-30">{{ __('Shipping Method') }}</h4>
                                <div class="payment_option" id="shipping_mode">
                                </div>
                            </div>
                            @else
                            <div class="payment-accordion-order-button d-none" id="shipping_mode_parent_div">
                            </div>
                            @endif

                            <div class="payment-accordion-order-button" style="margin-bottom: 40px;">
                                <h4 class="mb-30">{{ __('Payment Method') }}</h4>
                                <div class="payment_option">
                                    @if (domain_info('shop_type') == 'reseller')
                                    @foreach ($gateways as $row)
                                    <div class="custome-radio" style="padding-top: 10px;">
                                        <input class="form-check-input" required="" type="radio" name="payment_method"
                                            id="payment_method_{{ $row->slug }}" value="{{ $row->slug }}"
                                            checked="checked">
                                        <label class="form-check-label" data-bs-toggle="collapse"
                                            data-target="#{{ $row->name }}" aria-controls="{{ $row->name }}">{{
                                            $row->name }}</label>
                                    </div>
                                    @endforeach
                                    @else
                                    @foreach ($gateways as $row)
                                    <div class="custome-radio" style="padding-top: 10px;">
                                        <input class="form-check-input" required="" type="radio" name="payment_method"
                                            id="payment_method_{{ $row->payment_method }}"
                                            value="{{ $row->payment_method }}" @if ($row->payment_method == 'cod')
                                        checked="checked" @endif>
                                        <label class="form-check-label" data-bs-toggle="collapse"
                                            data-target="#{{ $row->content['title'] ?? "" }}"
                                            aria-controls="{{ $row->content['title'] ?? "" }}">{{ $row->content['title']
                                            ?? "" }}</label>
                                        @if (isset($row->content['additional_details']))
                                        <div class="card card-body rounded-0 payment_method_info payment_method_{{ $row->payment_method }}"
                                            style="display: none;margin-bottom: 20px;margin-top: 20px;">
                                            <p>{{ $row->content['additional_details'] }}</p>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="order-button-payment">
                                <button type="submit"
                                    class="btn btn-primary btn-hover-primary rounded-0 w-100 btn btn-fill-out btn-block place-order-btn mt-30">{{
                                    __('Place an Order') }}</button>
                                <b><small class="text-danger d-none" id="out_of_stock">Please remove Out of stock
                                        product from cart to place an order.</small></b>
                            </div>
                            <!-- Payment Accordion Order Button End -->
                        </div>
                        <!-- Your Order Area End -->
                    </div>
                </div>
            </form>
        </div>
    </div>
    @else
    <div class="section section-margin">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-danger" role="alert">
                        Payment Mode on Hold: Due to technical issues, the payment option is currently unavailable. We are working on resolving the issue. Thank you for your understanding.
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <script src="{{ asset('frontend/fashionbag/js/plugins/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('frontend/fashionbag/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script>
        function formatPrice(amount = 0) {
            let currency = window.singlebag.currency;

            if (currency) {
                if (currency['position'] == 0) {
                    return currency['symbol'] + "" + formatMoney(amount);
                } else {
                    return amount + "" + currency['symbol'];
                }
            }

            return amount;
        }

        function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
            try {

                if (isNaN(amount)) {
                    amount = parseInt(amount.replace(/[,]/g, ''));
                }

                decimalCount = Math.abs(decimalCount);
                decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

                const negativeSign = amount < 0 ? "-" : "";

                let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
                let j = (i.length > 3) ? i.length % 3 : 0;

                return negativeSign +
                    (j ? i.substr(0, j) + thousands : '') +
                    i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) +
                    (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
            } catch (e) { }
        }

        function update_cart() {
            $.ajax({
                type: 'GET',
                url: '/get_cart',
                dataType: 'json',
                success: function (response) {
                    $('.cart_discount').html(formatPrice(response.discount));
                    $('.cart_price_total').html(formatPrice(response.priceTotal));
                    $('.cart_tax').html(formatPrice(response.tax));
                    $('.cart_subtotal').html(formatPrice(response.subtotal));
                    $('#cart-total').val(response.total);
                    update_cart_total();
                },
                error: function (error) {
                    Sweet('error', 'Something went error');
                }
            });
        }



        function Sweet(icon, title, time = 3000) {

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: time,
                timerProgressBar: true,
                onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })


            Toast.fire({
                icon: icon,
                title: title,
            })
        }


        function SweetAudio(icon, title, time = 3000, audio = "") {

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: time,
                timerProgressBar: true,
                onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                    var aud = new Audio(audio)
                    aud.play();
                }
            })


            Toast.fire({
                icon: icon,
                title: title,
            })
        }
        /*----------------------------------------*/
        /* Toggle Function Active
        /*----------------------------------------*/

        // showlogin toggle
        $('#showlogin').on('click', function () {
            $('#checkout-login').slideToggle(500);
        });
        // showlogin toggle
        $('#showcoupon').on('click', function () {
            $('#checkout_coupon').slideToggle(500);
        });
        // showlogin toggle
        $('#cbox').on('click', function () {
            $('#cbox-info').slideToggle(500);
        });
        $(document).ready(function () {
            $('#city').select2();
        });

        var shipping_amount = 0;
        @if (domain_info('shop_type') != 'reseller')
            $("#city").on('change', function (e) {
                shipping_amount = 0;
                $(".shipping_mode_div").remove();
                $(".cart-item").html("");
                $('.place-order-btn').prop('disabled', false);
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var id = $(this).find(":selected").val();
                $('.shipping_cost').html(formatPrice(0));
                $.ajax({
                    type: 'GET',
                    url: "{{ url('/location-shipping-methods') }}",
                    data: {
                        'id': id
                    },
                    success: function (response) {
                        $("#shipping_mode_parent_div").removeClass("d-none");
                        for (var i = 0; i < response.length; i++) {
                            var description = response[i].estimated_delivery ?? '';
                            $("#shipping_mode").append(
                                "<div class='shipping_mode_div'><div class='custome-radio' style='padding-top: 10px;'> <input class='form-check-input' required='' value=" +
                                response[i].id + " data-value=" + response[i].cost +
                                " type='radio' name='shipping_mode'> <label class='form-check-label'>" +
                                response[i].title + ' ' + ' ( + ' + formatPrice(response[i].cost) +
                                ') ' + description + "</label></div></div>");
                        }
                        update_cart_total();
                        if (response == '') {
                            $("#shipping_mode_parent_div").addClass("d-none");
                            $("#shipping_mode").append("<div class='shipping_mode_div'></div>");
                        }
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: "{{ url('/supplier-product-availability') }}/" + id,
                    data: {
                        'id': id
                    },
                    success: function (response) {
                        $.each(response, function (index) {
                            if (response[index].status == 0) {
                                $('.place-order-btn').prop('disabled', true);
                                $('.cart-item-' + response[index].id).html("Out of stock");
                            } else if (response[index].status == 2) {
                                $('.place-order-btn').prop('disabled', true);
                                $('.cart-item-' + response[index].id).html(
                                    "This item not deliverable");
                            } else {
                                $('.estimated-delivery-' + response[index].id).html(response[
                                    index].estimated_delivery);
                            }
                        });
                    }
                });
            });
        @else
        $("#city").on('change', function (e) {
            shipping_amount = 0;
            $(".shipping_mode_div").remove();
            $(".cart-item").html("");
            $('.place-order-btn').prop('disabled', false);
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var id = $(this).find(":selected").val();
            $('.shipping_cost').html(formatPrice(0));
            $.ajax({
                type: 'GET',
                url: "{{ url('/supplier-shipping-prices') }}/" + id,
                data: {
                    'id': id
                },
                success: function (response) {
                    $("#shipping_mode_parent_div").removeClass("d-none");
                    $.each(response, function (index) {
                        if (response[index]) {
                            shipping_amount += parseFloat(response[index].cost);
                        }
                    });
                    $('.shipping_cost').html(formatPrice(shipping_amount));
                    // var total = parseFloat($('#cart-total').val()) ?? 0;
                    // var cart_total = shipping_amount + total;
                    // $('.cart_total').html(formatPrice(cart_total));
                    update_cart_total();
                    // if (response == '') {
                    //     $("#shipping_mode_parent_div").addClass("d-none");
                    //     $("#shipping_mode").append("<div class='shipping_mode_div'></div>");
                    // }
                }
            });

            $.ajax({
                type: 'GET',
                url: "{{ url('/supplier-product-availability') }}/" + id,
                data: {
                    'id': id
                },
                success: function (response) {
                    $.each(response, function (index) {
                        if (response[index].status == 0) {
                            $('.place-order-btn').prop('disabled', true);
                            $('.cart-item-' + response[index].id).html("Out of stock");
                        } else if (response[index].status == 2) {
                            $('.place-order-btn').prop('disabled', true);
                            $('.cart-item-' + response[index].id).html(
                                "This item not deliverable");
                        } else {
                            $('.estimated-delivery-' + response[index].id).html(response[
                                index].estimated_delivery);
                        }
                    });
                }
            });
        });
        @endif
        $("#coupon_form").on('submit', function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: this.action,
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    update_cart();
                    Sweet('success', response.message);
                },
                error: function (error) {
                    Sweet('error', error.responseJSON.message ?? 'Coupon is Invalid');
                }
            })
        });
        $(".place-order-btn").on('click', function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var btn = $(this).find('.place-order-btn');
            $.ajax({
                type: 'POST',
                url: "{{ url('/make_order_validation') }}",
                data: $('#make_order').serialize(),
                contentType: 'application/x-www-form-urlencoded; charset=utf-8',
                cache: false,
                processData: false,
                beforeSend: function () {
                    btn.html("Please Wait....");
                    btn.attr('disabled', '');
                },
                success: function (result, status, xhr) {

                    $(".place-order-btn").html("Please Wait....", true);
                    $(".place-order-btn").prop("disabled", true);
                    $("#make_order").submit();
                    return true;
                },
                error: function (xhr, status, error) {
                    if (xhr.responseJSON.message) {
                        Sweet('error', xhr.responseJSON.message);
                    }
                    console.log(xhr.responseJSON.errors);
                    $.each(xhr.responseJSON.errors, function (key, item) {
                        Sweet('error', item ?? 'Invalid data');
                        exit;
                    });
                    btn.html("{{ __('Place an Order') }}");
                    btn.removeAttr("disabled");
                }
            })
        });
        $('input[name="payment_method"]').change(function ($e) {
            $('.payment_method_info').hide();
            $('.' + $(this).attr("id")).show();
        });
        $(document).on('change', 'input[name="shipping_mode"]', function (e) {
            update_cart_total();
        });

        function update_cart_total() {
            var shipping_mode = $("input[type='radio'][name='shipping_mode']:checked").length != 0 ? parseFloat($(
                "input[type='radio'][name='shipping_mode']:checked").data('value')) : 0;
            var total = parseFloat($('#cart-total').val()) ?? 0;
            $('.shipping_cost').html(formatPrice(shipping_mode + shipping_amount));
            var cart_total = shipping_mode + total + shipping_amount;
            $('.cart_total').html(formatPrice(cart_total));
        }
    </script>
    <script type='text/javascript'>
        // $(document).ready(function() {
        //     $("#city").select2({
        //         ajax: {
        //             url: "{{ url('search-cities') }}",
        //             type: "post",
        //             delay: 250,
        //             dataType: 'json',
        //             data: function(params) {
        //                 return {
        //                     q: params.term, // search term
        //                     "_token": "{{ csrf_token() }}",
        //                 };
        //             },
        //             processResults: function(response) {
        //                 return {
        //                     results: response
        //                 };
        //             },
        //             cache: true
        //         }
        //     });

        // });

        $(document).ready(function () {
            $("#city").select2({
                ajax: {
                    url: "{{ url('search-cities') }}",
                    dataType: 'json',
                    delay: 250,
                    type: "post",
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page,
                            "_token": "{{ csrf_token() }}"
                        };
                    },
                    processResults: function (params) {
                        params.page = params.page || 1;

                        return {
                            results: params.cities,
                            pagination: {
                                // more: (params.page) 
                                more: (params.page * 10) < params.count
                            }
                        };
                    },
                    cache: true
                },
                placeholder: 'Search for a City',
                //   minimumInputLength: 1,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            });

            function formatRepo(repo) {
                if (repo.loading) {
                    return repo.text;
                }

                var $container = $(
                    "<div class='select2-result-repository clearfix'>" +
                    "<div class='select2-result-repository__meta'>" +
                    "<div class='select2-result-repository__title'></div>" +
                    "<div class='select2-result-repository__description'></div>" +
                    "<div class='select2-result-repository__statistics'>" +
                    "</div>" +
                    "</div>" +
                    "</div>"
                );

                $container.find(".select2-result-repository__title").text(repo.text);


                return $container;
            }

            function formatRepoSelection(repo) {
                return repo.text;
            }
        });
        $(document).ready(function () {
            var out_of_stock = $('.out_of_stock').val();
            if (out_of_stock == 1) {
                $("#out_of_stock").removeClass('d-none');
            } else if (out_of_stock == 0) {
                $("#out_of_stock").addClass('d-none');
            } else {

            }
        });
    </script>
</body>

</html>