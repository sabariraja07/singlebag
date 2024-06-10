@extends('layouts.seller')
@section('title', 'Product View')
@section('head')
    @include('layouts.partials.headersection', ['title' => 'Product View'])
@endsection
@section('page-style')
    <link rel="stylesheet" type="text/css" href="./admin/css/pages/app-ecommerce.css">
   <!-- CSS -->
   <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <style>
        .product-features li i {
            height: 1.4rem;
            width: 1.4rem;
            font-size: 1.4rem;
            margin-right: 0.75rem;
        }

        .product-features li span {
            font-weight: 600;
        }

        .card .card-img-top {
            border-radius: 0.5rem 0.5rem 0 0;
            width: 100%;
            object-fit: cover;
            aspect-ratio: 4/3;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 25px 0 rgba(34, 41, 47, 0.25);
        }

        .product-card .item-rating ul {
            margin-bottom: 0;
        }

        .product-card .item-rating svg,
        .product-card .item-rating i {
            height: 1.143rem;
            width: 1.143rem;
            font-size: 1.143rem;
        }

        .product-card .item-name {
            margin-bottom: 0;
        }

        .product-card .item-name a {
            font-weight: 600;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-card .item-description {
            font-size: 0.875rem;
        }

        .product-card .btn-wishlist span,
        .product-card .btn-cart span {
            vertical-align: text-top;
        }

        .product-card .btn-wishlist i,
        .product-card .btn-wishlist svg,
        .product-card .btn-cart i,
        .product-card .btn-cart svg {
            margin-right: 0.25rem;
            vertical-align: text-top;
        }

        .product-card .btn-wishlist i.text-danger,
        .product-card .btn-wishlist svg.text-danger,
        .product-card .btn-cart i.text-danger,
        .product-card .btn-cart svg.text-danger {
            fill: #ea5455;
        }

        .product-card {
            overflow: hidden;
        }

        /* .product-card .item-img {
            padding-top: 0.5rem;
            min-height: 15.85rem;
            display: flex;
            align-items: center;
        } */

        .product-card .item-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }

        .product-card .shipping,
        .product-card .item-company,
        .product-card .item-options .item-price {
            display: none;
        }

        .product-card .item-options {
            display: flex;
            flex-wrap: wrap;
        }

        .product-card .item-options .btn-cart,
        .product-card .item-options .btn-wishlist {
            flex-grow: 1;
            border-radius: 0;
        }

        .product-card .item-name {
            margin-top: 0.75rem;
        }

        .product-card .item-description {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            margin-top: 0.2rem;
        }

        .product-card .item-price {
            font-weight: 600;
        }

        .product-card .card-body {
            padding: 1rem;
        }
    </style>
@endsection
@section('content')
    @php
        $url = domain_info('full_domain');
        $msg = 'No Product Found';
    @endphp
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            @if (Session::has('success'))
                <div class="alert alert-success">
                    <ul style="margin-top: 1rem;">
                        <li>{{ Session::get('success') }}</li>
                    </ul>
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger">
                    <ul style="margin-top: 1rem;">
                        <li>{{ Session::get('error') }}</li>
                    </ul>
                </div>
            @endif
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Add Products') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ url('/seller/products') }}">{{ __('Products') }}</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="card @if ($type == 1) card-success @elseif($type == 2) card-info @elseif($type == 3) card-warning @elseif($type == 0 && $type != 'all') card-danger @endif">
            <div class="card-body">
                <br>
                <div class="float-right">
                    <form>
                        <div class="input-group mb-2">

                            <input type="text" id="src" class="typeahead form-control" placeholder="Search..." required=""
                                name="src" value="{{ $src ?? '' }}">
                            <select class="form-control selectric d-none" name="type" id="type">
                                <option value="title">{{ __('Search By Name') }}</option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="search_btn" type="submit"><i class="ph-magnifying-glass"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if (count($posts) > 0)
        <div class="row">
            @foreach ($posts as $row)
                @if ($row->stock->stock_manage != 0 && ($row->stock->stock_qty <= 0 || $row->stock->stock_status == 0))
                @else
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card product-card">
                            <div class="item-img text-center">
                                <a href="javascript:void(0);">
                                    <img class="img-fluid card-img-top"
                                        src="{{ asset($row->image ?? './images/default_product.png') }}"
                                        alt="img-placeholder" /></a>
                            </div>
                            {{-- @endif --}}
                            <div class="card-body">
                                <div class="item-wrapper">
                                    <div class="item-rating">
                                        {{ $row->title }}
                                    </div>
                                    <div>
                                        <h6 class="item-price">{{ amount_format($row->price->regular_price) ?? '' }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="item-cost">
                                    <span class="company-name">{{ __('By') }} {{ shop_details($row->shop_id)->name ?? '' }}</span>
                                </div>
                            </div>
                            <div class="item-options text-center">
                                <a href="#"
                                    class="btn btn-light btn-wishlist waves-effect waves-float waves-light"
                                    onclick="viewSupplierProduct({{ $row->id }})">
                                    <i class="ph-eye-bold"></i>
                                    <span>{{ __('View') }}</span>
                                </a>
                                <a href="{{ route('seller.reseller.add_product', $row->id) }}"
                                    class="btn btn-primary btn-cart">
                                    <i class="ph-plus-bold"></i>
                                    <span>{{ __('Add Products') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        {{ $posts->appends($request->all())->links('vendor.pagination.bootstrap-4') }}
        @else
        <div class="alert alert-success" style="padding: 10px !important;">
            <ul style="margin-top: 1rem;">
                <li>{{ $msg }}</li>
            </ul>
        </div>
        @endif
    </div>

    <div class="modal fade" id="viewProductModal" tabindex="-1" aria-labelledby="viewProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewProductModalBody"></div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/seller/product/index.js') }}"></script>
    <script src="./assets/js/scripts/forms/form-select2.js"></script>
    <script src="./assets/js/scripts/pages/app-ecommerce-details.js"></script>
       <!-- Script -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        function viewSupplierProduct(id) {
            $.ajax({
                type: "GET",
                url: "{{ route('seller.supplier.products.show', '') }}/" + id,
                success: function(result) {
                    $('#viewProductModal').modal("show");
                    $('#viewProductModalBody').html(result).show();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
        var path = "{{ route('seller.supplier_products.autocomplete') }}";
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $( "#src" ).autocomplete({
            source: function( request, response ) {
                $.ajax({
                url: path,
                type: 'GET',
                dataType: "json",
                data: {
                    _token: CSRF_TOKEN,
                    search: request.term
                },
                success: function( data ) {
                    console.log(data);
                    response( data );
                }
                });
            },
            select: function (event, ui) {
                $('#src').val(ui.item.label);
                $("#search_btn").click();
                return false;
            }
            });
    </script>
@endsection
