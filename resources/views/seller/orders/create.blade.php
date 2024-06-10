@extends('layouts.seller')

@section('title', 'Create Order')

@section('page-style')
    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #fff !important;
            border-style: none !important;
        }

        .select2-selection--multiple {
            border: 1px solid #d8d6de !important;
            border-radius: 0.357rem !important;
        }
    </style>
@endsection

@section('content')
    @php
        $url = domain_info('full_domain');
        $cart = get_cart();
    @endphp
    <div class="content-wrapper container-md p-0 ecommerce-application">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Create order') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/seller/dashboard">{{ __('Home') }}</a>
                                <li class="breadcrumb-item"><a href="/seller/orders/all">{{ __('Orders') }}</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    {{ __('Create order') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-header">
            <div class="dt-action-buttons text-end">
                <div class="dt-buttons d-inline-flex">
                    @if (getCartCount() != 0)
                        <a href="#" type="button" class="dt-button create-new btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#cartModal">
                            <i class="ph-shopping-cart-simple-bold"></i>
                            <span id="cart_count">{{ getCartCount() }}</span>
                        </a>
                    @else
                        <a href="#" type="button" class="dt-button create-new btn btn-primary" id="product-cart">
                            <i class="ph-shopping-cart-simple-bold"></i>
                            <span id="cart_count">{{ getCartCount() }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex flex-row justify-content-end mt-2 search_mobile">
                <form class="card-header-form">
                    <div class="input-group">
                        <input type="text" name="src" value="{{ $src ?? '' }}" class="form-control" required=""
                            placeholder="Enter Product Name" />
                        <button class="btn btn-outline-primary waves-effect" type="submit">
                            <i class="ph-magnifying-glass-bold"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mobile_view desktop_view" id="order_create_table">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th><i class="ph-image"></i></th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Price') }}</th>
                                {{-- <th>{{ __('Variation') }}</th> --}}
                                <th>{{ __('Options') }}</th>
                                {{-- <th class="text-right"><span class="mr-3">{{ __('Cart') }}</span></th> --}}
                            </tr>
                        </thead>
                        @foreach ($posts as $row)
                            @php
                                
                                $price = amount_format($row->price->price);
                                $qty = $row->stock->stock_qty;
                                $product_id = $row->id;
                                
                            @endphp
                            
                                <tr class="list font-size-base rowlink" data-link="row">
                                    <td>
                                        <a href="{{ route('seller.products.edit', $row->id) }}">#{{ $row->id }}</a>
                                    </td>
                                    <td>
                                        <img src="{{ asset($row->image ?? 'uploads/default.png') }}" height="50">
                                    </td>
                                    <td>
                                        <a href="{{ url($url . '/product/' . $row->slug . '/' . $product_id) }}"
                                            target="_blank">{{ Str::limit($row->title, 50) }}</a>
                                    </td>
                                    <td style="width: 159px !important;">
                                        <span id="price{{ $row->id }}">{{ $price }}</span>
                                    </td>
                                    <td>
                                        <form method="post" class="basicform" action="{{ route('seller.order.store') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product_id }}">
                                            <input type="hidden" name="main_price_{{ $row->id }}"
                                                id="base-price-{{ $row->id }}" value="{{ $row->price->price }}">
                                            <div class="d-flex flex-row align-items-start d-flex justify-content-between">
                                                <div class="d-flex flex-column flex-fill" style="max-width:340px;">
                                                    @if (count($row->options) > 0)
                                                        @foreach ($row->options as $option)
                                                            @if ($option->select_type == 0)
                                                                <select class="select2 form-select option option-{{ $row->id }}"
                                                                    name="option[{{ $option->id }}][]"
                                                                    data-productid="{{ $row->id }}"
                                                                    @if ($option->is_required == 1) required @endif>
                                                                    <option value="" disabled selected>select
                                                                        {{ $option->name }}</option>
                                                                    @foreach ($option->variants as $item)
                                                                        <option value="{{ $item->id }}"
                                                                            data-price="{{ $item->amount }}"
                                                                            data-amounttype="{{ $item->amount_type }}"
                                                                            style="margin:4px">
                                                                            {{ $item->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                <select class="option option-{{ $row->id }} select2 form-select"
                                                                    name="option[{{ $option->id }}][]" multiple
                                                                    @if ($option->is_required == 1) required @endif
                                                                    data-productid="{{ $row->id }}"
                                                                    data-placeholder="select {{ $option->name }}">
                                                                    @foreach ($option->variants as $item)
                                                                        <option value="{{ $item->id }}"
                                                                            data-price="{{ $item->amount }}"
                                                                            data-amounttype="{{ $item->amount_type }}">
                                                                            {{ $item->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-row ms-2" style="max-width:200px;">
                                                    @if ($row->stock->stock_manage != 0 && ($row->stock->stock_qty <= 0 || $row->stock->stock_status == 0))
                                                        <div>
                                                            <p style="font-size: 17px;color: red">Out Of Stock</p>
                                                        </div>
                                                    @else
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" placeholder="Button on right"
                                                                aria-describedby="button-addon2"
                                                                @if ($row->stock->stock_manage == 1) max="{{ $row->stock->stock_qty }}" @endif
                                                                min="0" required="" value="1" name="qty">
                                                            <button onclick="assignId({{ $row->id }})"
                                                                class="btn btn-outline-primary waves-effect"
                                                                id="submitbtn{{ $row->id }}" type="submit">
                                                                <i class="ph-shopping-cart-simple-bold"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                        @endforeach
                    </table>
                </div>
                <div class="d-flex flex-row justify-content-end mt-2">
                    {{ $posts->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
        <div class="row gy-2 mobile_view" id="order_create_cards">
            @foreach ($posts as $row)
                @php
                    $price = amount_format($row->price->price);
                    $qty = $row->stock->stock_qty;
                    $product_id = $row->id;
                @endphp
                <form method="post" class="basicform" action="{{ route('seller.order.store') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product_id }}">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title card-title d-flex align-items-start justify-content-start">
                                    <div class="card-image me-50">
                                        <a href="javascript:void(0)">
                                            <img class="" src="{{ asset($row->image ?? 'uploads/default.png') }}"
                                                height="42" width="42" alt="{{ $row->title ?? '' }}" />
                                        </a>
                                    </div>
                                    <div class="more-info">
                                        <h4 class="font-medium-2 mb-25">{{ $row->title }}</h4>
                                        <div class="font-small-2 mb-1">#{{ $row->id }}</div>
                                        <h5 class="mb-1" id="price{{ $row->id }}">{{ $price }}</h5>
                                        <input type="hidden" name="main_price_{{ $row->id }}"
                                            id="base-price-{{ $row->id }}" value="{{ $row->price->price }}">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if (count($row->options) > 0)
                                    @foreach ($row->options as $option)
                                        @if ($option->select_type == 0)
                                            <select class="select2 form-select option option-{{ $row->id }}"
                                                name="option[{{ $option->id }}][]" data-productid="{{ $row->id }}"
                                                @if ($option->is_required == 1) required @endif>
                                                <option value="" disabled selected>select
                                                    {{ $option->name }}</option>
                                                @foreach ($option->variants as $item)
                                                    <option value="{{ $item->id }}" data-price="{{ $item->amount }}"
                                                        data-amounttype="{{ $item->amount_type }}" style="margin:4px">
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select class="option option-{{ $row->id }} select2 form-select w-100"
                                                name="option[{{ $option->id }}][]" multiple
                                                @if ($option->is_required == 1) required @endif
                                                data-productid="{{ $row->id }}"
                                                data-placeholder="select {{ $option->name }}">
                                                @foreach ($option->variants as $item)
                                                    <option value="{{ $item->id }}" data-price="{{ $item->amount }}"
                                                        data-amounttype="{{ $item->amount_type }}">
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    @endforeach
                                @endif
                                <div class="d-flex justify-content-end">
                                    <div class="form-check form-check-inline mb-1">
                                        @if ($row->stock->stock_manage != 0 && ($row->stock->stock_qty <= 0 || $row->stock->stock_status == 0))
                                            <div>
                                                <p style="font-size: 17px;color: red">Out Of Stock</p>
                                            </div>
                                        @else
                                            <div class="input-group">
                                                <input type="number" class="form-control" placeholder="Button on right"
                                                    aria-describedby="button-addon2"
                                                    @if ($row->stock->stock_manage == 1) max="{{ $row->stock->stock_qty }}" @endif
                                                    min="0" required="" value="1" name="qty">
                                                <button onclick="assignId({{ $row->id }})"
                                                    class="btn btn-outline-primary waves-effect"
                                                    id="submitbtn{{ $row->id }}" type="submit">
                                                    <i class="ph-shopping-cart-simple-bold"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endforeach
            <div class="d-flex flex-row justify-content-end mt-2">
                {{ $posts->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- Cart modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">{{ __('Cart Items') }}</h1>
                    </div>
                    <hr />
                    <div class="table-responsive desktop_view">
                        <table class="table table-hover">
                            <thead>
                                <tr>

                                    <th><i class="ph-image"></i></th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('QTY') }}</th>

                                    <th class="text-right">{{ __('Remove') }}</th>
                                </tr>
                            </thead>
                            <tbody id="cart-content">

                                @foreach ($cart['items'] as $row)
                                    <tr class="cart-row cart{{ $row->id }}">

                                        <td><img src="{{ asset($row->attributes->image) }}" height="50"></td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ number_format($row->price, 2) }}</td>
                                        <td>{{ $row->quantity }}</td>

                                        <td class="text-right">
                                            <a href="{{ route('seller.cart.remove', $row->id) }}"
                                                class="btn btn-danger btn-sm remove_btn">
                                                <i class="ph-trash"></i>
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                        </table>
                    </div>
                    <div class="table-responsive mobile_view">
                        @foreach ($cart['items'] as $row)
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body" style="padding: 0.5rem !important;">
                                        <div class="position-relative rounded p-25">
                                            <div class="d-flex me-3">
                                                <img src="{{ asset($row->attributes->image) }}" class="me-1"
                                                    height="50">
                                                <div class="blog-info me-1 col-8">{{ $row->name }}<br>
                                                    {{ number_format($row->price, 2) }} X {{ $row->quantity }}</div>

                                                <div class="text-right me-1 col-3">
                                                    <a href="{{ route('seller.cart.remove', $row->id) }}"
                                                        class="btn btn-danger btn-sm remove_btn">
                                                        <i class="ph-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <a href="{{ route('seller.checkout') }}"
                            class="btn btn-success">{{ __('Proceed To CheckOut') }}</a>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            aria-label="Close">
                            {{ __('Close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <input type="hidden" id="removecart_url" value="{{ url('/seller/order/cart/remove/') }}">
    <input type="hidden" id="count_value" value=" {{ $cart['count'] }} ">
@endsection
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/pages/app-ecommerce.css') }}">
@endsection
@section('page-script')
    <script>
        (function(window, document, $) {
            'use strict';
            var select = $('.select2');

            select.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative mb-50"></div>');
                $this.select2({
                    dropdownAutoWidth: true,
                    width: '100%',
                    dropdownParent: $this.parent()
                });
            });
        })(window, document, jQuery);
    </script>
    <script>
        $("#product-cart").on('click', function() {
            var count = $('#count_value').val();
            if (count == 0) {
                Sweet('error', 'Please add product to cart');
                return fasle;
            }
        });
    </script>
    <script src="{{ asset('assets/js/order_create.js') }}"></script>
@endsection
