@extends('layouts.seller')
@section('title', 'Create Product')
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/form-pickadate.css') }}">
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">{{ __('Create Product') }}</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ url('/seller/products') }}">{{ __('Products') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Create Product') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="post" action="{{ route('seller.products.store_new') }}" id="product_create">
                        @csrf
                        <div class="row mb-1">
                            <div class="col-md-3">
                                <label for="price">{{ __('Product Title') }}</label>
                            </div>
                            <div class="form-group col-md-9 col-12">

                                <input type="text" class="form-control" placeholder="Enter Title" name="title"
                                    required>

                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-3">
                                <label for="price">{{ __('Price') }}</label>
                            </div>
                            <div class="form-group col-md-9 col-12">

                                <input type="number" step="any" class="form-control" id="price"
                                    placeholder="Enter Price" name="price" required="">

                            </div>
                        </div>
                        @if (current_shop_type() != 'supplier')
                        <div class="row mb-1">
                            <div class="col-md-3">
                                <label for="special_price">{{ __('Selling Price') }}</label>
                            </div>
                            <div class="form-group col-md-9 col-12">
                                <input type="number" step="any" class="form-control" id="special_price" placeholder=""
                                    name="special_price" max="">
                            </div>
                        </div>

                        {{-- <div class="row mb-1">
                            <div class="col-md-3">
                                <label for="special_price_type">{{ __('Special Price Type') }}</label>
                            </div>
                            <div class="form-group col-md-9 col-12">
                                <select name="price_type" id="special_price_type" class="form-control selectric">
                                    <option value="1">{{ __('Fixed') }}</option>
                                    <option value="0">{{ __('Percent') }}</option>
                                </select>
                            </div>
                        </div> --}}
                        <div class="row mb-1">
                            <div class="col-md-3">
                                <label for="special_price_start">{{ __('Offer Price Start') }}</label>
                            </div>
                            <div class="form-group col-md-9 col-12">
                                <input type="text" id="special_price_start" name="special_price_start"
                                    class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                                {{-- <input type="date" class="form-control" id="special_price_start" placeholder=""  name="special_price_start"> --}}
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-md-3">
                                <label for="special_price_end">{{ __('Offer Price End') }}</label>
                            </div>
                            <div class="form-group col-md-9 col-12">
								<input type="text" id="special_price_end" name="special_price_end"
                                    class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                                {{-- <input type="date" class="form-control" id="special_price_end" placeholder=""
                                    name="special_price_end"> --}}
                            </div>
                        </div>
                        @endif
                        <div class="row mb-1">
                            <div class="col-md-3">
                                <label for="sku">{{ __('SKU') }}</label>
                            </div>
                            <div class="form-group col-md-9 col-12">

                                <input type="text" class="form-control" id="sku" placeholder="#ABC-123"
                                    name="sku">

                            </div>
                        </div>



                        <div class="row mb-1">
                            <div class="col-md-3">
                                <label for="sku">{{ __('Manage Stock') }}</label>
                            </div>
                            <div class="form-group col-md-9 col-12">
                                <div class="form-check form-check-primary form-switch mt-1">
                                    <label>
                                        <input type="checkbox" name="stock_manage"
                                            class="custom-switch-input sm form-check-input" value="1">
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-md-3">
                                <label for="qty">{{ __('Stock Quantity') }}</label>
                            </div>
                            <div class="form-group col-md-9 col-12">

                                <input type="number" class="form-control" id="qty" placeholder="Enter Quantity"
                                    name="stock_qty">

                            </div>
                        </div>
                        <br>
                        <div class="offset-sm-3">
                            <button type="submit" class="btn btn-primary col-4" id="submit_btn"><i
                                    class="fa fa-save"></i> {{ __('Save') }}</button>
                        </div>
                    </form>
                    </div>
                </div>
        </div>
    </div>

@endsection
@section('page-script')
    <script src="{{ asset('assets/seller/product/index.js') }}"></script>
    <script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-pickers.js') }}"></script>
    <script>
        $(document).on('change', '#price', function() {
            var price = $('#price').val();
            $("#special_price").attr({
                "max" : price,
            });
        });
    </script>
@endsection
