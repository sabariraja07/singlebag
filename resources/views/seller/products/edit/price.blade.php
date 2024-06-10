@extends('layouts.seller')
@section('title', 'Edit Price')
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/form-pickadate.css') }}">
@endsection
@section('content')
    <div class="content-header row">
        @if (Session::has('success'))
            <div class="alert alert-success">
                <ul style="margin-top: 1rem;">
                    <li>{{ Session::get('success') }}</li>
                </ul>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin-top: 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Price') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ url('/seller/products') }}">{{ __('Products') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('Price') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-3">
                            @include('seller.products.edit.tab')
                        </div>
                        <div class="col-sm-9">
                            <form class="basicform" method="post"
                                action="{{ route('seller.products.price', current_shop_type() == 'reseller' ? $info->id : $info->price->id) }}">
                                @csrf
                                @method('PUT')

                                {{-- @if (current_shop_type() == 'seller')
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="price" class="mt-2">{{ __('Discounted Price') }}</label>
                                        <input type="number" disabled value="{{ $info->price->price }}" step="any"
                                            class="form-control" id="price" placeholder="Enter Price"
                                            required="">
                                        @if ($info->price->price_type === 1 && $info->price->special_price != '')
                                            <span class="badge rounded-pill badge-light-danger"
                                                style=" margin-top: 6px; float: right; ">
                                                @if (isset($currency))
                                                    {{ $currency->symbol }} {{ $info->price->special_price }}{{ __(' FLAT RATE OFF') }}
                                                @else
                                                    ₹ {{ $info->price->special_price }}{{ __(' FLAT RATE OFF') }}
                                                @endif
                                            </span>
                                        @elseif($info->price->price_type === 0)
                                            <span class="badge rounded-pill badge-light-danger"
                                                style=" margin-top: 6px; float: right; ">
                                                {{ $info->price->special_price }}{{ __('% OFF') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @endif --}}

                                @if (current_shop_type() == 'reseller')
                                <div class="row">
                                    <div class="mb-2 pb-50">
                                        <h5>{{ __('Supplier Price') }} <strong>:</strong></h5>
                                        <span>{{ $info->price->price }}</span>
                                    </div>
                                    <div class="mb-2 pb-50">
                                        <h5>{{ __('Product Tax') }} <strong>:</strong></h5>
                                        <span>{{ $info->tax ?? 0 }} %</span>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="price" class="mt-2">{{ __('Price') }}</label>
                                        <input type="number" value="{{ $info->ResellerProduct->price ?? 0 }}"
                                            step="any" class="form-control" id="price" name="price" placeholder="Enter Price"
                                            min="{{ $info->price->price }}"
                                            required="">
                                    </div>
                                    {{-- <div class="col-md-12">
                                        <label for="margin_amount" class="mt-2">{{ __('Margin Price') }}</label>
                                        <input type="number" value="{{ $info->ResellerProduct->amount }}" step="any"
                                            class="form-control" id="margin_amount" placeholder="" name="amount">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="amount_type"
                                            class="mt-2">{{ __('Price Type') }}</label>
                                        <select name="amount_type" id="amount_type"
                                            class="form-control selectric">
                                            <option value="1" @if ($info->ResellerProduct->amount_type === 1) selected @endif>
                                                {{ __('Fixed') }}</option>
                                            <option value="0" @if ($info->ResellerProduct->amount_type === 0) selected @endif>
                                                {{ __('Percent') }}</option>
                                        </select>
                                    </div> --}}
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="price" class="mt-2">{{ __('Regular Price') }}</label>
                                        <input type="number" value="{{ $info->price->regular_price }}" step="any"
                                            class="form-control" id="price" placeholder="Enter Price" name="price"
                                            required="">
                                    </div>
                                </div>
                                @endif

                                
                                @if (current_shop_type() == 'seller')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="special_price" class="mt-2">{{ __('Selling Price') }}</label>
                                            <input type="number" value="{{ $info->price->regular_price - $info->price->special_price }}" step="any"
                                                class="form-control" id="special_price" placeholder="" name="special_price" max="{{$info->price->regular_price}}">
                                        </div>
                                    </div>

                                    {{-- <div class="row">
                                        <div class="col-md-12">
                                            <label for="special_price_type"
                                                class="mt-2">{{ __('Special Price Type') }}</label>
                                            <select name="price_type" id="special_price_type"
                                                class="form-control selectric">
                                                <option value="1" @if ($info->price->price_type === 1) selected @endif>
                                                    {{ __('Fixed') }}</option>
                                                <option value="0" @if ($info->price->price_type === 0) selected @endif>
                                                    {{ __('Percent') }}</option>
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="special_price_start"
                                                class="mt-2">{{ __('Offer Price Start') }}</label>
                                            <input type="text" id="special_price_start" name="special_price_start"
                                                class="form-control flatpickr-basic"
                                                value="{{ $info->price->starting_date }}" />
                                            {{-- <input type="date" class="form-control" value="{{ $info->price->starting_date }}" id="special_price_start" placeholder=""  name="special_price_start" >	 --}}
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-md-12">
                                            <label for="special_price_end"
                                                class="mt-2">{{ __('Offer Price End') }}</label>
                                            <input type="text" id="special_price_end" name="special_price_end"
                                                class="form-control flatpickr-basic"
                                                value="{{ $info->price->ending_date }}" />
                                            {{-- <input type="date" class="form-control" id="special_price_end" value="{{ $info->price->ending_date }}" placeholder=""  name="special_price_end" > --}}
                                        </div>
                                    </div>
                                @endif
                                <button type="submit"
                                    class="btn btn-primary basicbtn mt-2">{{ __('Save Changes') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-pickers.js') }}"></script>
@endsection