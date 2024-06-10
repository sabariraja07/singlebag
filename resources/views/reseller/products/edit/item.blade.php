@extends('layouts.seller')
@section('title', 'Edit Product')
@stack('css')
<style>
    @media screen and (min-width:1200px) {
        .brand_style {
            float: right;
        }

        .category_style {
            float: right;
        }

    }

</style>
@section('content')

    <div class="content-header row">
        @if (session()->has('flash_notification.message'))
            <div class="alert alert-{{ session()->get('flash_notification.level') }}">
                <ul style="margin-top: 1rem;">
                    <li>{{ session()->get('flash_notification.message') }}</li>
                </ul>
                {{-- {!! session()->get('flash_notification.message') !!} --}}
            </div>
        @endif

        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">{{ __('Item') }}</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ url('/seller/product') }}">{{ __('Products') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Item') }}
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
                    <div class="row">
                        <div class="col-sm-3">
                            @include('reseller.products.edit.tab')
                        </div>
                        <div class="col-sm-9">
                            <form method="post" action="{{ route('seller.products.update', $info->id) }}" id="productform">
                            @csrf
                            @method('PUT')
                                <div class="mb-2 pb-50">
                                    <h5>{{ __('Product Name') }} <strong>:</strong></h5> 
                                    <span>{{ $info->title }}</span>
                                </div>
                                <div class="mb-2 pb-50">
                                    <h5>{{ __('Supplier Price') }} <strong>:</strong></h5> 
                                    <span>{{ $info->price->regular_price }}</span>
                                </div>
                                <div class="form-group mb-1">
                                    <label class="mb-1">{{ __('Category') }}</label>
                                    <a href="javascript:void(0)" data-bs-target="#add_category" class="float-end" data-bs-toggle="modal">
                                        <i class="ph-plus-bold"></i> {{ __('Add Category') }}
                                    </a>
                                    <select class="form-control select2" style="width: 100% !important;"
                                        name="category_id" id="category-select">
                                        <option value="">None</option>
                                        {{ ConfigCategory('category', $info->reseller_product->category_id ?? null) }}
                                    </select>
                                </div>
                                <div class="form-group mb-1">
                                    <label>{{ __('Brand') }}</label>
                                    <a href="javascript:void(0)" data-bs-target="#add_brand" class="float-end" data-bs-toggle="modal">
                                        <i class="ph-plus-bold"></i> {{ __('Add Brand') }}
                                    </a>
                                    <select class="form-control mt-1" name="brand" id="brand-select">
                                        <option value="">None</option>
                                        {{ ConfigCategoryMulti('brand', $info->reseller_product->brand_id ?? null) }}
                                    </select>
                                </div>
                                @php
                                $featured = $info->reseller_product->featured ?? 0;
                                @endphp
                                <div class="form-group mb-1">
                                    <label>{{ __('Featured') }}</label>
                                    <select class="form-control" name="featured">
                                        <option value="0" @if ($featured == 0) selected="" @endif>
                                            {{ __('None') }}</option>
                                        <option value="1" @if ($featured == 1) selected="" @endif>
                                            {{ __('Trending products') }}</option>
                                        <option value="2" @if ($featured == 2) selected="" @endif>
                                            {{ __('Best selling products') }}</option>

                                    </select>
                                </div>
                                <div class="form-group mb-1">
                                    <button class="btn btn-primary basicbtn"
                                        type="submit">{{ __('Save Changes') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

    </div>

    </div>
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
                            <label>Name</label>
                            <div>
                                <input type="text" class="form-control" required="" name="name">
                            </div><br>
                            @if (current_shop_type() != 'supplier')
                                <div>
                                    <label>Featured</label>
                                    <select class="form-control selectric" name="featured">
                                        <option value="1">{{ __('Yes') }}</option>
                                        <option value="0" selected="">{{ __('No') }}</option>

                                    </select>
                                </div><br>
                            @endif
                            <div>
                                <label>Thumbnail<span style="color:red;">(</span> 250 x250 mm <span
                                        style="color:red;">)</span></label>
                                <input type="file" name="file" accept="image/*" id="file"
                                    class="form-control">
                                <span id="file_error"></span>
                            </div><br>
                            <div>
                                <label>Status</label>
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
                                    Close
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
                            <label>Name</label>
                            <div>
                                <input type="text" class="form-control" required="" name="name">

                            </div><br>
                            <div>
                                <label>Parent Category</label>
                                <select class="form-control selectric" name="p_id" id="p_id">
                                    <option value="">{{ __('None') }}</option>
                                    <?php echo ConfigCategory('category'); ?>
                                </select>
                            </div><br>
                            @if (current_shop_type() != 'supplier')
                                <div>
                                    <label>Featured</label>
                                    <select class="form-control selectric" name="featured">
                                        <option value="1">Yes</option>
                                        <option value="0" selected="">No</option>

                                    </select>
                                </div><br>
                                <div>
                                    <label>Assign To Menu</label>
                                    <select class="form-control selectric" name="menu_status">
                                        <option value="1">{{ __('Yes') }}</option>
                                        <option value="0" selected="">{{ __('No') }}</option>

                                    </select>
                                </div><br>
                            @endif
                            <div>
                                <label>Thumbnail<span style="color:red;">(</span> 300 x 300 mm <span
                                        style="color:red;">)</span></label>
                                <input type="file" name="file" id="file" accept="image/*"
                                    class="form-control">
                                <span id="file_error"></span>

                            </div><br>
                            <div>
                                <label>Status</label>
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
                                    Close
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
@endsection
@section('page-script')
    <script src="./admin/js/scripts/forms/form-select2.js"></script>
    <script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/form.js?v=1.0') }}"></script>
    <script type="text/javascript">
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

                    // previous_category.push(JSON.stringify(response.category_id));
                    // $('#category-select').val(previous_category);
                    // $.each(previous_category, function(key,value) {
                    // 	// $('#category-select').val(value);
                    // 	// console.log(value);
                    // });

                },
                error: function(xhr, status, error) {
                    Sweet('error', "Maximum category limit exceeded");

                }

            })


        });
    </script>
@endsection
