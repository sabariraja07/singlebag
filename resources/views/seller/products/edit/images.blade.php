@extends('layouts.seller')
@section('title', 'Edit Images')
@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.css') }}">
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">{{ __('Images') }}</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ url('/seller/products') }}">{{ __('Products') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Images') }}
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
                            @include('seller.products.edit.tab')
                        </div>
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
                                <lable>
                                    <span style="color:red;">(</span> 650 x 650 mm <span style="color:red;">)</span>
                                </label>
                                <form action="{{ route('seller.media.store') }}" enctype="multipart/form-data"
                                    class="dropzone" id="mydropzone">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $info->id }}">
                                </form>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form class="basicform" action="{{ route('seller.medias.destroy') }}">
        @csrf
        <input type="hidden" name="m_id" id="m_id">
    </form>
@endsection
@section('page-script')
    <script type="text/javascript" src="{{ asset('assets/js/dropzone.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/seller/product/images.js') }}"></script>
    <script>
        $(document).ready(function() {
            if (window.File && window.FileList && window.FileReader) {
                $("#product-img-upload").on("change", function(e) {
                    var files = e.target.files,
                        filesLength = files.length;
                    for (var i = 0; i < filesLength; i++) {
                        var f = files[i]
                        var fileReader = new FileReader();
                        fileReader.onload = (function(e) {
                            var file = e.target;
                            $("#product-img").attr('src', e.target.result);
                        });
                        fileReader.readAsDataURL(f);
                        let formData = new FormData();
                        formData.append('file',files[i]);
                        formData.append('product_id', '{{ $info->id }}');
                        $.ajax({
                            type:'POST',
                            url: "{{ route('seller.media.store') }}",
                            data: formData,
                            cache:false,
                            contentType: false,
                            processData: false,
                            success: (data) => {
                                Sweet('success', data.message);
                                setTimeout(function () {
                                    location.reload();
                                }, 3000);
                            },
                            error: function(data){
                                Sweet('error', data.responseJSON);
                                setTimeout(function () {
                                    location.reload();
                                }, 3000);
                            }
                        });
                    }
                });
            } else {
                Sweet('error', "Your browser doesn't support to File API");
            }
        });
    </script>
@endsection
