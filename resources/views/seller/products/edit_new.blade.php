@extends('layouts.seller')
@section('title', 'Edit Product')
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/form-pickadate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.css') }}">
    <style>
        #footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background: hsla(0,0%,100%,.75);
            color: white;
            text-align: center;
        }
        /* == Google colors */
        /*For google*/
        .google{
        word-break: break-all;
        margin: 30px auto;
        max-width: 632px;
        font-family: 'Roboto', sans-serif;
        }
        .google #gg-result{
        color: #23c5b4;
        font-weight: bold;
        border-color: #40e0d0;
        border-width: 0 0 3px 0;
        transition: all .2s ease-out;
        }
        .google h3{
        color: #4285f4;
        font-size: 18px;
        line-height: 1.2;
        letter-spacing: 1px;
        font-weight: normal;
        }
        .google cite{
        color: #009930;
        display: block;
        font-style: normal;
        font-weight: normal;
        letter-spacing: .8px;
        }
        .google #gg-desc{
        display: block;
        line-height: 28px;
        letter-spacing: 1px;
        word-wrap: break-word;
        margin: 10px 0;
        }
    </style>
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">{{ __('Edit Product') }}</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ url('/seller/products') }}">{{ __('Products') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Edit Product') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $url = domain_info('full_domain');
    @endphp
    <div class="row">
        <div class="col-lg-9">
            <form method="post" action="{{ route('seller.products.update', $info->id) }}" id="productform">
            @csrf
            @method('PUT')
            <div class="card" id="basic-information">
                <div class="card-body">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        {{ __('Basic Information') }}
                      </h3>
                      <div class="form-group mb-1 mt-1">
                        <label>{{ __('Brand') }}</label>
                        <a href="" data-bs-target="#add_brand" class="float-end"
                            data-bs-toggle="modal">
                            <i class="ph-plus-bold"></i> {{ __('Add Brands') }}
                        </a>
                        <select class="form-control" name="brand" id="brand-select">
                            <option value="">None</option>
                            {{ ConfigBrand('brand', $info->brand_id) }}
                        </select>
                       </div>
                       <div class="form-group mb-1">
                        <label>{{ __('Category') }}</label>
                        <a href="javascript:void(0)" data-bs-target="#add_category" class="float-end"
                            data-bs-toggle="modal">
                            <i class="ph-plus-bold"></i> {{ __('Add Categories') }}
                        </a>
                        <select multiple class="form-control select2" style="width: 100% !important;"
                            name="cats[]" id="category-select">
                            <option value="">None</option>
                            {{ ConfigCategoryMulti('category', $cats) }}
                        </select>
                    </div>
                </div>
            </div>
            <div class="card" id="attributes">
                <div class="card-body">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        {{ __('Details') }}
                    </h3>
                    <div class="form-group mb-1 mt-1">
                        <label>{{ __('Name') }}</label>
                        <input type="text" name="title" class="form-control" required=""
                            value="{{ $info->title }}">
                    </div>
                    <div class="form-group mb-1">
                        <label>{{ __('Short Description') }}</label>
                        <textarea class="form-control" name="short_description">{{ $info->short_description ?? '' }}</textarea>
                    </div>
                    {{ editor(['title' => 'Product Content', 'name' => 'description', 'value' => $info->description ?? '']) }}
                    <div class="col-md-12 mb-1 mt-1">
                        <label for="tax_amount">{{ __('Tax') }} (%)</label>
                        <input type="number" value="{{ $info->tax ?? ''}}" step="any" min="0" max="100"
                            class="form-control" id="tax_amount" placeholder="" name="tax">
                    </div>
                    {{-- <div class="col-md-12 mb-1">
                        <label for="tax_type">{{ __('Tax Type') }}</label>
                        <select name="tax_type" id="tax_type" class="form-control selectric">
                            <option value="1" @if ($info->tax_type === 1) selected @endif>
                                {{ __('Fixed') }}</option>
                            <option value="0" @if ($info->tax_type === 0) selected @endif>
                                {{ __('Percent') }}</option>
                        </select>
                    </div> --}}
                    @if (current_shop_type() != 'supplier')
                        <div class="form-group mb-1">
                            <label>{{ __('Featured') }}</label>
                            <select class="form-control" name="featured">
                                <option value="0"
                                    @if ($info->featured == 0) selected="" @endif
                                    >
                                    {{ __('None') }}</option>
                                <option value="1"
                                    @if ($info->featured == 1) selected="" @endif
                                    >
                                    {{ __('Trending products') }}</option>
                                <option value="2"
                                    @if ($info->featured == 2) selected="" @endif
                                    >
                                    {{ __('Best selling products') }}</option>

                            </select>
                        </div>
                    @endif
                </div>
            </div> 
            <div class="card" id="images">
                <div class="card-body">
                    <div class="col-sm-9">
                        @if (current_shop_type() == 'reseller')
                        <h4 class="card-title">{{ __('Feature Image') }}</h4>
                        <div class="d-flex mb-2">
                            <a href="{{ $image->url ?? asset('uploads/default.png')}}" class="me-25">
                                <img src="{{ $image->url ?? asset('uploads/default.png')}}" class="uploadedAvatar rounded me-50" alt="Product image" height="150" width="150">
                            </a>
                        </div>
                        @if(count($images) > 0)
                        <div class="mb-1">
                            <h4 class="card-title">{{ __('Images') }}</h4>
                            <div class="row">
                                @foreach ($images as $key => $row)
                                    <div class="col-sm-3" id="m_area{{ $key }}">
                                        <div class="card">
                                            <div class="card-body">
                                                <img src="{{ asset($row->url) }}" alt="" height="150" width="150">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @else
                        <h4 class="card-title">{{ __('Feature Image') }}</h4>
                        <div class="d-flex mb-2">
                            <a href="{{ $image->url ?? asset('uploads/default.png')}}" class="me-25">
                                <img src="{{ $image->url ?? asset('uploads/default.png')}}" id="product-img" class="uploadedAvatar rounded me-50" alt="Product image" height="150" width="150">
                            </a>
                            <!-- upload and reset button -->
                            <div class="d-flex align-items-end mt-75 ms-1">
                                <div>
                                    <label for="product-img-upload" class="btn btn-sm btn-primary mb-75 me-75 waves-effect waves-float waves-light">Upload</label>
                                    <input type="file" id="product-img-upload" hidden="" accept="image/png,image/jpg,image/jpeg">
                                    {{-- <button type="button" id="account-reset" class="btn btn-sm btn-outline-secondary mb-75 waves-effect">Reset</button> --}}
                                    <p class="mb-0">{{ __('Product_Image_Format') }} </p>
                                </div>
                            </div>
                            <!--/ upload and reset button -->
                        </div>
                        <div class="mb-1">
                            <h4 class="card-title">{{ __('Images') }}</h4>
                            <label>
                                <span style="color:red;">(</span> 650 x 650 mm <span style="color:red;">)</span>
                            </label>
                            {{-- <form action="{{ route('seller.media.store') }}" enctype="multipart/form-data"
                                class="dropzone" id="mydropzone">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $info->id }}">
                            </form> --}}
                            <div class="row">
                                @foreach ($images as $key => $row)
                                    <div class="col-sm-3" id="m_area{{ $key }}">
                                        <div class="card">
                                            <div class="card-body">
                                                <img src="{{ asset($row->url) }}" alt="" height="150" width="150">
                                            </div>
                                            <div class="card-footer">
                                                <button class="btn btn-danger col-12"
                                                    onclick="remove_image('{{ base64_encode($row->id) }}',{{ $key }})">{{ __('Remove') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="form-group mb-1 mt-1">
                        <label>{{ __('Video Provider') }}</label>
                        <select class="form-control" name="video_provider">
                            <option value="" @if ($info->video_provider == 0) selected="" @endif>
                                {{ __('None') }}</option>
                            <option value="youtube" @if ($info->video_provider == 'youtube') selected="" @endif>
                                {{ __('Youtube') }}</option>
                            <option value="dailymotion"
                                @if ($info->video_provider == 'dailymotion') selected="" @endif>
                                {{ __('Dailymotion') }}</option>
                            <option value="vimeo" @if ($info->video_provider == 'vimeo') selected="" @endif>
                                {{ __('Vimeo') }}</option>
                        </select>
                    </div>
                    <div class="form-group mb-1">
                        <label>{{ __('Video Link') }}</label>
                        <input type="text" name="video_url" class="form-control"
                            value="{{ $info->video_url ?? ''}}">
                    </div>
                </div> 
            </div>
            <div class="card" id="availability">
                <div class="card-body">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        {{ __('Availability') }}
                    </h3>
                    <div class="alert alert-primary" role="alert">
                        <div class="alert-body" style=" display: flex; ">
                            <div class="inline-block">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13 16H12V12H11M12 8H12.01M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <div class="inline-block" style=" text-align: justify; ">
                                &nbsp;When you schedule availability, that product won't be available for the channel group until the date has past and the product is active.
                            </div>
                        </div>
                    </div>
                    <h5 class="text-lg font-medium leading-6 text-gray-900">
                        {{ __('Channels') }}
                    </h5>
                    <p>Select which channels this product is available on.</p>
                    <div class="row">
                        <div class="col-4 mt-1">
                            <h6> Webstore </h6>
                        </div>
                        {{-- <div class="col-4 mt-1">
                            <span class="text-danger">Never available</span>
                        </div> --}}
                        <div class="col-4 mt-1">
                            <div class="form-check form-check-primary form-switch">
                                <label>
                                    <input type="checkbox" name="status" @if ($info->status == 1) checked="" @endif
                                        class="custom-switch-input sm form-check-input" value="1">
                                    <span class="custom-switch-indicator"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 mt-1">
                            <h6> POS </h6>
                        </div>
                        {{-- <div class="col-4 mt-1">
                            <span class="text-danger">Never available</span>
                        </div> --}}
                        <div class="col-4 mt-1">
                            <div class="form-check form-check-primary form-switch">
                                <label>
                                    <input type="checkbox" name="pos"
                                        class="custom-switch-input sm form-check-input" value="1" @if ($info->pos == 1) checked="" @endif>
                                    <span class="custom-switch-indicator"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="card" id="variants">
                <div class="card-body">
                    <div class="row">
                        <div class="col-10 mt-1">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                {{ __('Variants') }}
                            </h3>
                            <span>This product has multiple options, like different sizes or colors.</span>
                        </div>
                        <div class="col-2 mt-1">
                            <div class="form-check form-check-primary form-switch">
                                <label>
                                    <input type="checkbox" name="stock_manage"
                                        class="custom-switch-input sm form-check-input" value="1">
                                    <span class="custom-switch-indicator"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="card" id="pricing">
                <div class="card-body">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        {{ __('Pricing') }}
                    </h3>
                    <div class="row">
                    @if (current_shop_type() == 'reseller')
                        <div class="mb-2 pb-50">
                            <h5>{{ __('Supplier Price') }} <strong>:</strong></h5>
                            <span></span>
                        </div>
                        <div class="mb-2 pb-50">
                            <h5>{{ __('Product Tax') }} <strong>:</strong></h5>
                            <span> %</span>
                        </div>
                        <div class="col-md-12">
                            <label for="price" class="mt-2">{{ __('Price') }}</label>
                            <input type="number" value=""
                                step="any" class="form-control" id="price" name="price" placeholder="Enter Price"
                                min=""
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
                    @else
                        <div class="col-md-6">
                            <label for="price" class="mt-2">{{ __('Regular Price') }}</label>
                            <input type="number" value="" step="any"
                                class="form-control" id="price" placeholder="Enter Regular Price" name="price"
                                required="">
                        </div>
                    @endif

                    
                    @if (current_shop_type() == 'seller')
                            <div class="col-md-6">
                                <label for="special_price" class="mt-2">{{ __('Selling Price') }}</label>
                                <input type="number" value="" step="any"
                                    class="form-control" id="special_price" placeholder="Enter Selling Price" name="special_price" max="">
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
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="special_price_start"
                                    class="mt-2">{{ __('Offer Price Start') }}</label>
                                <input type="text" id="special_price_start" name="special_price_start"
                                    class="form-control flatpickr-basic"
                                    value="" />
                                {{-- <input type="date" class="form-control" value="{{ $info->price->starting_date }}" id="special_price_start" placeholder=""  name="special_price_start" >	 --}}
                            </div>
                            <div class="col-md-6">
                                <label for="special_price_end"
                                    class="mt-2">{{ __('Offer Price End') }}</label>
                                <input type="text" id="special_price_end" name="special_price_end"
                                    class="form-control flatpickr-basic"
                                    value="" />
                                {{-- <input type="date" class="form-control" id="special_price_end" value="{{ $info->price->ending_date }}" placeholder=""  name="special_price_end" > --}}
                            </div>
                        </div>
                    @endif
                </div> 
            </div>
            <div class="card" id="identifiers">
                <div class="card-body">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        {{ __('Identifiers') }}
                    </h3>
                    <div class="form-group mb-1">
                        <label for="sku">{{ __('SKU') }}</label>
                        <input type="text" name="sku" value="{{ '' }}"
                        class="form-control">
                    </div>
                </div> 
            </div>
            <div class="card" id="inventory">
                <div class="card-body">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        {{ __('Inventory') }}
                    </h3>
                        <div class="form-group mb-1">
                            <label for="stock_manage">{{ __('Manage Stock') }}</label>
                            <select name="stock_manage" id="stock_manage" class="form-select">
                                <option value="1" @if ($info->stock->stock_manage == 1) selected @endif>
                                    {{ __('Manage Stock') }}</option>
                                <option value="0" @if ($info->stock->stock_manage == 0) selected @endif>
                                    {{ __('Dont Need To Manage Stock') }}</option>
                            </select>
                        </div>
                        <div class="stock_area row">
                            <div class="form-group mb-1 col-md-6">
                                <label for="stock_status">{{ __('Stock Status') }}</label>
                                <select name="stock_status" id="stock_status" class="form-select">
                                    <option value="1" @if ($info->stock->stock_status == 1) selected @endif>
                                        {{ __('In Stock') }}</option>
                                    <option value="0" @if ($info->stock->stock_status == 0) selected @endif>
                                        {{ __('Out Of Stock') }}</option>
                                </select>
                            </div>
                            <div class="form-group mb-1 col-md-6">
                                <label for="stock_qty">{{ __('Stock Quantity') }}</label>
                                <input type="text" name="stock_qty"
                                value="{{ $info->stock->stock_qty ?? '' }}" class="form-control">
                            </div>
                        </div>
                </div> 
            </div>
            <div class="card" id="files">
                <div class="card-body">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        {{ __('Files') }}
                    </h3>
                    <span id="create_mobile_view">
                        <a href="javascript:void(0)" data-bs-target="#attribute_modal" data-bs-toggle="modal">
                            <span class="btn btn-primary mb-1"
                                >{{ __('Create File') }}</span>
                        </a>
                    </span>
                    <table class="table table-hover table-nowrap card-table">
                        <thead>
                            <tr>
                                <th>{{ __('Url') }}</th>
                                <th width="200">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($info->files as $row)
                                <tr>
                                    <td>{{ $row->url }}</td>
                                    @if(current_shop_type() != 'reseller')
                                        <td class="text-right">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-success edit"
                                                    data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-id="{{ $row->id }}"
                                                    data-attribute="{{ $row->attribute_id }}"
                                                    data-name="{{ $row->name }}"
                                                    data-url="{{ $row->url }}"><i
                                                        class="ph-pencil-bold"></i></button>

                                                <button type="button"
                                                    onclick="make_trash('{{ base64_encode($row->id) }}')"
                                                    class="btn btn-danger"><i class="ph-trash"
                                                        aria-hidden="true"></i></button>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> 
            </div>
            <div class="card" id="seo">
                <div class="card-body">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        {{ __('SEO') }}
                    </h3>
                    @if($seo)
                        <div class="google">
                            <span id="gg-title"><h3>{{ $seo->title ?? '' }}</h3>
                                <span>                                
                                    <div class="dropdown chart-dropdown card-dropdown">
                                        <i data-feather="more-vertical" class="font-medium-3 cursor-pointer" data-bs-toggle="dropdown"></i>
                                        <div class="dropdown-menu dropdown-menu-end" id="seo_edit">
                                            <span class="dropdown-item d-flex align-items-center">
                                                <i data-feather="eye" class="me-50"></i><span>{{ __('Edit') }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </span>
                            </span>
                            <cite id="gg-url">{{ $url . '/product/' . $info->slug . '/' . $info->id }}</cite>
                            <span id="gg-desc">{{ $seo->description ?? '' }}</span>
                        </div>
                        <div id="seo_edit_row" class="d-none">
                            <div class="row">
                                <div class="form-group mb-1 col-6">
                                    <label>{{ __('Meta Title') }}</label>
                                    <input type="text" name="meta_title" class="form-control"  value="{{ $seo->title ?? '' }}">
                                </div>				
                                <div class="form-group mb-1 col-6">
                                    <label>{{ __('Meta Keyword') }}</label>
                                    <input type="text" name="meta_keyword" class="form-control"  value="{{ $seo->keywords ?? '' }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mb-1 col-12">
                                    <label>{{ __('Meta Description') }}</label>
                                    <textarea class="form-control" name="meta_description"  >{{ $seo->description ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="form-group mb-1 col-6">
                                <label>{{ __('Meta Title') }}</label>
                                <input type="text" name="meta_title" class="form-control" value="{{ $seo->title ?? '' }}">
                            </div>				
                            <div class="form-group mb-1 col-6">
                                <label>{{ __('Meta Keyword') }}</label>
                                <input type="text" name="meta_keyword" class="form-control" value="{{ $seo->keywords ?? '' }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group mb-1 col-12">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="meta_description"  >{{ $seo->description ?? '' }}</textarea>
                            </div>
                        </div>
                    @endif
                </div> 
            </div>
            <div id="footer">
                <div class="form-group mb-1 mt-1">
                    <button class="btn btn-primary basicbtn" style=" margin-left: 600px; "
                        type="submit">{{ __('Save Product') }}</button>
                </div>
            </div>
            </form>
        </div>
        <div class="col-lg-3">
            <div class="card" style="position: fixed;">
                <div class="card-body">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#basic-information"
                                >{{ __('Basic Information') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#attributes"
                                >{{ __('Attributes') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  href="#images"
                                >{{ __('Images') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  href="#availability"
                                >{{ __('Availability') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#variants"
                                >{{ __('Variants') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#pricing"
                                >{{ __('Pricing') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#identifiers"
                                >{{ __('Identifiers') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#inventory"
                                >{{ __('Inventory') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#files"
                                >{{ __('Files') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#seo"
                                >{{ __('SEO') }}</a>
                        </li>
                    </ul>
                </div> 
            </div>
        </div>
    </div>
        <!-- Modal -->
            <!-- Brand Modal -->
                <div class="modal fade" id="add_brand" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{ __('Create Brand') }}</h5>
                            </div>
                            <form action="{{ route('seller.brands.new.create') }}" method="post" enctype="multipart/form-data"
                                id="product_brand_creation">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group mb-1">
                                        <label> {{ __('Name') }}</label>
                                        <div>
                                            <input type="text" class="form-control" required="" name="name">
                                        </div><br>
                                        @if (current_shop_type() != 'supplier')
                                            <div>
                                                <label> {{ __('Featured') }}</label>
                                                <select class="form-control selectric" name="featured">
                                                    <option value="1">{{ __('Yes') }}</option>
                                                    <option value="0" selected="">{{ __('No') }}</option>

                                                </select>
                                            </div><br>
                                        @endif
                                        <div>
                                            <label> {{ __('Thumbnail') }}<span style="color:red;">(</span> 250 x250 mm <span
                                                    style="color:red;">)</span></label>
                                            <input type="file" name="file" accept="image/*" id="file"
                                                class="form-control">
                                            <span id="file_error"></span>
                                        </div><br>
                                        <div>
                                            <label> {{ __('Status') }}</label>
                                            <select class="form-control selectric" name="status">
                                                <option value="1">{{ __('Active') }}</option>
                                                <option value="0" selected="">{{ __('Inactive') }}</option>

                                            </select>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer">


                                    <div class="import_area">

                                        <div>
                                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                                aria-label="Close" id='brand_close'>
                                                {{ __('Close') }}
                                            </button>
                                            <button type="submit" class="btn btn-primary brand_save">{{ __('Save') }}</button>
                                        </div>

                                    </div>


                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Category Modal -->
                <div class="modal fade" id="add_category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{ __('Create Category') }}</h5>
                            </div>
                            <form action="{{ route('seller.category.new.create') }}" method="post" enctype="multipart/form-data"
                                id="product_category_creation">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group mb-1">
                                        <label> {{ __('Name') }}</label>
                                        <div>
                                            <input type="text" class="form-control" required="" name="name">

                                        </div><br>
                                        <div>
                                            <label> {{ __('Parent Category') }}</label>
                                            <select class="form-control selectric" name="p_id" id="p_id">
                                                <option value="">{{ __('None') }}</option>
                                                <?php echo ConfigCategory('category'); ?>
                                            </select>
                                        </div><br>
                                        @if (current_shop_type() != 'supplier')
                                            <div>
                                                <label> {{ __('Featured') }}</label>
                                                <select class="form-control selectric" name="featured">
                                                    <option value="1">Yes</option>
                                                    <option value="0" selected="">No</option>

                                                </select>
                                            </div><br>
                                            <div>
                                                <label> {{ __('Assign To Menu') }}</label>
                                                <select class="form-control selectric" name="menu_status">
                                                    <option value="1">{{ __('Yes') }}</option>
                                                    <option value="0" selected="">{{ __('No') }}</option>

                                                </select>
                                            </div><br>
                                        @endif
                                        <div>
                                            <label> {{ __('Thumbnail') }}<span style="color:red;">(</span> 300 x 300 mm <span
                                                    style="color:red;">)</span></label>
                                            <input type="file" name="file" id="file" accept="image/*"
                                                class="form-control">
                                            <span id="file_error"></span>

                                        </div><br>
                                        <div>
                                            <label> {{ __('Status') }}</label>
                                            <select class="form-control selectric" name="status">
                                                <option value="1">{{ __('Active') }}</option>
                                                <option value="0" selected="">{{ __('Inactive') }}</option>

                                            </select>
                                        </div>


                                    </div>
                                </div>
                                <div class="modal-footer">

                                    <div class="import_area">

                                        <div>
                                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                                aria-label="Close" id='category_close'>
                                                {{ __('Close') }}
                                            </button>
                                            <button type="submit" class="btn btn-primary category_save"
                                                id="submit">{{ __('Save') }}</button>
                                        </div>

                                    </div>


                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        <form action="{{ route('seller.file.store') }}" class="basicform">
            @csrf
            <div class="modal fade" id="attribute_modal" data-backdrop="static" data-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">{{ __('Add New File') }}</h5>
                        </div>
                        <div class="modal-body">
    
                            <input type="hidden" name="term" value="{{ $info->id }}">
    
                            <div class="form-group">
                                <label>{{ __('Url') }}</label>
                                <input type="text" name="url" class="form-control" required="">
                            </div>
    
                        </div>
                        <div class="modal-footer">
    
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                {{ __('Close') }} 
                            </button>
                            <button type="submit" class="btn btn-primary basicbtn">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

@endsection
@section('page-script')
    <script src="./admin/js/scripts/forms/form-select2.js"></script>
    <script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-pickers.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/form.js?v=1.0') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/stock.js') }}"></script>
    <script type="text/javascript">
        $("a[href^='#']").click(function(e) {
            e.preventDefault();
            
            var position = $($(this).attr("href")).offset().top - 70;

            $("body, html").animate({
                scrollTop: position
            } /* speed */ );
        });
        $(".nav-link").hover(function () {
            $(this).toggleClass("btn btn-primary");
        });
        if ($('textarea[name="description"]').length > 0) {
            CKEDITOR.replace('content');
        }
        if($('#category-select').val() == ''){
            $('#category-select').val('');
        }
        $('#affiliate').on('change', function() {
            if (this.checked) {
                $('.order_link').show();
            } else {
                $('.order_link').hide();
            }

        });
        //Product Edit Brand Ajax Call
        $("#product_brand_creation").on('submit', function(e) {

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
                beforeSend: function() {

                    $('.brand_save').html("Please Wait....");
                    $('.brand_save').attr('disabled', '');
                    $('#brand_close').attr('disabled', '');

                },

                success: function(response) {

                    Sweet('success', response.message);
                    $('#add_brand').modal('hide');
                    if (response.status == 1) {
                        $('#brand-select').append("<option value=" + response.brand_id + ">" + response
                            .brand + "</option>");
                        $('#brand-select').val(response.brand_id);
                    }
                    $("#product_brand_creation")[0].reset();
                    $('.brand_save').attr('disabled', false);
                    $('#brand_close').attr('disabled', false);
                    $('.brand_save').html("Submit");


                },
                error: function(xhr, status, error) {
                    Sweet('error', "Maximum Brand limit exceeded");

                }

            })


        });

        //Product Edit Category Ajax Call
        $("#product_category_creation").on('submit', function(e) {

            var previous_category = $('#category-select').val();
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
                beforeSend: function() {

                    $('.category_save').html("Please Wait....");
                    $('.category_save').attr('disabled', '');
                    $('#category_close').attr('disabled', '');

                },

                success: function(response) {

                    Sweet('success', response.message);
                    $('#add_category').modal('hide');
                    if (response.status == 1) {
                        $('#category-select').append("<option value=" + response.category_id + ">" +
                            response
                            .category + "</option>");
                        previous_category.push(JSON.stringify(response.category_id));
                        $('#category-select').val(previous_category);
                    }


                    $("#product_category_creation")[0].reset();
                    $('.category_save').attr('disabled', false);
                    $('#category_close').attr('disabled', false);
                    $('.category_save').html("Submit");

                },
                error: function(xhr, status, error) {
                    Sweet('error', "Maximum category limit exceeded");

                }

            })


        });
        $("#seo_edit").click(function(e){
            $("#seo_edit_row").removeClass("d-none");
            $(".google").addClass("d-none");
        });            
    </script>
@endsection
