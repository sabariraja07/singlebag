@extends('layouts.seller')
@section('title', 'Checkout')
@section('page-style')
    <style>
        .select2-container--classic .select2-selection--single .select2-selection__arrow b,
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            padding-right: 0px !important;
        }

        .detail-amt {
            float: right !important;
            margin-top: -17px !important;
        }
    </style>
@endsection
@section('content')
    @php
    $cart = get_cart();
    @endphp
    <div class="content-wrapper container-md p-0 ecommerce-application">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Checkout') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/seller/dashboard">{{ __('Home') }}</a>
                                <li class="breadcrumb-item"><a href="/seller/orders/all">{{ __('Orders') }}</a>
                                <li class="breadcrumb-item"><a href="/seller/order/create">{{ __('Create Order') }}</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    {{ __('Checkout') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-md-4 order-md-2 mb-4">
                    <div class="checkout-options" id="cart-details">
                        @include('seller.orders.cart_detail')
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('Apply Coupon') }}</h4>
                        </div>
                        <div class="card body">
                            <form class="card p-1 apply_coupon_form" method="post"
                                action="{{ route('seller.orders.apply_coupon') }}">
                                @csrf
                                <div class="input-group" id="promo_code">
                                    <input type="text" class="form-control" placeholder="Promo code" required=""
                                        name="code">
                                    <div class="input-group-append">
                                        <button type="submit"
                                            class="btn btn-secondary redeembtn">{{ __('Redeem') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 order-md-1">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3">{{ __('Billing address') }}</h4>
                            <form class="needs-validation basicform_with_redirect" novalidate method="post"
                                action="{{ route('seller.orders.make_order') }}">
                                @csrf

                                <div class="mb-1">
                                    <label for="name">{{ __('Customer Name') }}</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                    <div class="invalid-feedback">
                                        {{ __('Please enter customer name.') }}
                                    </div>
                                </div>
                                <input type="hidden" name="customer_id" id="customer_id">
                                <div class="mb-1">
                                    <label for="email">{{ __('Customer Email') }}</label>
                                    <input type="email" class="form-control" id="email" name="email" required=""
                                        placeholder="customer@example.com">
                                    <div class="invalid-feedback">
                                        {{ __('Please enter a valid email address for shipping updates.') }}
                                    </div>
                                </div>
                                <div class="mb-1 location">
                                    <label for="phone">{{ __('Customer Phone') }}</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="+11223344556" maxlength="10" minlength="10">
                                    <div class="invalid-feedback">
                                        {{ __('Please enter a valid phone number') }}
                                    </div>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="name">{{ __('Customer Type') }}</label>
                                    <select class="form-select" name="customer_type">
                                        @if (env('MULTILEVEL_CUSTOMER_REGISTER') == true)
                                            <option value="1">{{ __('Website Customer') }}</option>
                                        @endif
                                        <option value="0" selected>{{ __('Guest Customer') }}</option>
                                    </select>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="name">{{ __('Delivery Type') }}</label>
                                    <select class="form-select type" name="delivery_type">
                                        <option value="1">{{ __('Hand Over Delivery') }}</option>
                                        <option value="0">{{ __('Virtual Delivery (Vertual Products)') }}</option>
                                    </select>
                                </div>


                                <div class="location">
                                    <div class="mb-1">
                                        <label for="address">{{ __('Address') }}</label>
                                        <input type="text" name="address" class="form-control" id="address"
                                            placeholder="1234 Main St">
                                        <div class="invalid-feedback">
                                            {{ __('Please enter your shipping address.') }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-1">
                                            <label for="city">{{ __('Location') }}</label>
                                            {{-- <select class="form-select select2" id="city" name="location"> --}}
                                            {{-- <option value="">{{ __('Choose...') }}</option> --}}
                                            <!-- {{ ConfigCategoryMulti('city') }} -->
                                            {{-- </select> --}}
                                            <select name="location" id="city" class="select2 form-select">

                                            </select>

                                        </div>

                                        <div class="col-md-6 mb-1">
                                            <label for="zip">{{ __('Zip') }}</label>
                                            <input type="text" name="zip_code" class="form-control" id="zip"
                                                placeholder="">
                                            <div class="invalid-feedback">
                                                {{ __('Zip code required.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="mb-4">

                                <div class="row">
                                    <div class="col-sm-4">
                                        <h4 class="mb-3">{{ __('Payment Status') }}</h4>

                                        <div class="d-block my-3">
                                            <div class="form-check form-check-primary mb-2">
                                                <input id="credit" name="payment_status" value="1"
                                                    type="radio" class="form-check-input" checked required>
                                                <label class="form-check-label"
                                                    for="credit">{{ __('Complete') }}</label>
                                            </div>
                                            <div class="form-check form-check-primary">
                                                <input id="debit" name="payment_status" value="2"
                                                    type="radio" class="form-check-input" required>
                                                <label class="form-check-label"
                                                    for="debit">{{ __('Pending') }}</label>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <h4 class="mb-3">{{ __('Payment Method') }}</h4>
                                        <div class="d-block my-3">
                                            @foreach ($posts as $key => $item)
                                                @php
                                                    $data = $item->content;
                                                @endphp

                                                <div class="form-check form-check-primary">
                                                    <input type="radio" name="payment_method"
                                                        id="gateway_{{ $item->payment_method }}" value="{{ $item->payment_method }}"
                                                        class="form-check-input" />
                                                    <label class="form-check-label"
                                                        for="gateway_{{ $item->payment_method }}">{{ $data['title'] }}</label>
                                                </div>

                                                <br>
                                            @endforeach

                                        </div>
                                    </div>
                                    <div class="col-sm-4 location">
                                        <h4 class="mb-3">{{ __('Shipping Method') }}</h4>
                                        <div class="d-block my-3" id="shipping_mode_parent_div">
                                            <div class="payment_option" id="shipping_mode">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mb-1">
                                        <label>{{ __('Payment Id') }}</label>
                                        <input type="text" required class="form-control" name="payment_id">
                                    </div>
                                    <div class="col-sm-12 mb-1">
                                        <label>{{ __('Order Note') }}</label>
                                        <textarea class="form-control height-100" name="comment"></textarea>
                                    </div>
                                </div>

                                <hr class="mb-4">
                                <button class="btn btn-success btn-lg btn-block submit_btn basicbtn"
                                    type="submit">{{ __('Make Order') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="shipping" value="{{ url('seller/shipping/') }}">
        <input type="hidden" id="shipping" value="{{ url('seller/shipping/') }}">
        <input type="hidden" id="base_url" value="{{ url('/') }}">
        <input type="hidden" id="TotalAmount" value="{{ $cart['total'] }}">
        <input type="hidden" id="weight" value="{{ $cart['weight'] }}">
    </div>
@endsection
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/pages/app-ecommerce.css') }}">
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/js/checkout.js') }}"></script>
    <script>
        window.singlebag = {
            baseUrl: '{{ url('/') }}',
            rtl: false,
            storeName: '{{ env('APP_NAME') }}',
            loggedIn: '{{ auth()->guard('customer')->check()? 1: 0 }}',
            csrfToken: '{{ csrf_token() }}',
            langs: {!! get_lang_trans() !!},
            currency: {!! json_encode(currency_info(), true) !!},
            theme_color: '{{ Cache::get(domain_info('shop_id') . 'theme_color', '#dc3545') }}'
        };
    </script>
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
            } catch (e) {}
        }

        $(document).on('change', 'input[name="shipping_mode"]', function(e) {
            update_cart_total();
        });

        function update_cart_total() {
            var total = parseFloat($('#cart-total').val()) ?? 0;
            var shipping_mode = $("input[type='radio'][name='shipping_mode']:checked").length != 0 ? parseFloat($(
                "input[type='radio'][name='shipping_mode']:checked").data('value')) : 0;
            $('.shipping_cost').html(formatPrice(shipping_mode));
            var cart_total = shipping_mode + total;
            $('.cart_total').html(formatPrice(cart_total));
        }

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

        $(document).ready(function() {
            $("#city").select2({
                ajax: {
                    url: "{{ url('search-cities') }}",
                    dataType: 'json',
                    delay: 250,
                    type: "post",
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page,
                            "_token": "{{ csrf_token() }}"
                        };
                    },
                    processResults: function(params) {
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

        var shipping_amount = 0;
        @if (domain_info('shop_type') != 'reseller')
            $("#city").on('change', function(e) {
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
                    success: function(response) {
                        $("#shipping_mode_parent_div").removeClass("d-none");
                        for (var i = 0; i < response.length; i++) {
                            var description = response[i].estimated_delivery ?? '';
                            $("#shipping_mode").append(
                                "<div class='shipping_mode_div'><div class='custome-radio'> <input class='form-check-input' required='' value=" +
                                response[i].id + " data-value=" + response[i].cost +
                                " type='radio' name='shipping_mode'> <label class='form-check-label' style='display: inline !important;'>" +
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
                    success: function(response) {
                        $.each(response, function(index) {
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
            $("#city").on('change', function(e) {
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
                    success: function(response) {
                        $("#shipping_mode_parent_div").removeClass("d-none");
                        $.each(response, function(index) {
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
                    success: function(response) {
                        $.each(response, function(index) {
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
        $(".apply_coupon_form").on('submit', function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var basicbtnhtml = $('.redeembtn').html();
            $.ajax({
                type: 'POST',
                url: this.action,
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {

                    $('.redeembtn').html("Please Wait....");
                    $('.redeembtn').attr('disabled', '')

                },

                success: function(response) {
                    $('.redeembtn').removeAttr('disabled')
                    Sweet('success', response.message);
                    $('.redeembtn').html(basicbtnhtml);
                    console.log(response);
                    $('#cart-details').html(response.data);
                    var total = parseFloat($('#cart-total').val()) ?? 0;
                    var shipping_mode = $("input[type='radio'][name='shipping_mode']:checked").length !=
                        0 ? parseFloat($(
                            "input[type='radio'][name='shipping_mode']:checked").data('value')) : 0;
                    $('.shipping_cost').html(formatPrice(shipping_mode));
                    var cart_total = shipping_mode + total;
                    $('.cart_total').html(formatPrice(cart_total));

                },
                error: function(xhr, status, error) {
                    $('.redeembtn').html(basicbtnhtml);
                    $('.redeembtn').removeAttr('disabled')
                    $('.errorarea').show();
                    $.each(xhr.responseJSON.errors, function(key, item) {
                        Sweet('error', item)
                        $("#errors").html("<li class='text-danger'>" + item + "</li>")
                    });
                    errosresponse(xhr, status, error);
                }
            })


        });
    </script>
@endsection
