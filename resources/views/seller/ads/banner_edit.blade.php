@extends('layouts.seller')
@section('title', 'Edit BannerAds')
@section('page-style')

    <style>
        .label_alignment {
            font-size: 15px !important;
            color: #5e5873 !important;
            /* padding-left: 151px !important;
                                        padding-top: 79px !important; */
        }

        .form-group {
            margin-bottom: 25px;
        }

        hr {
            width: 93% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            margin-top: -5px !important;
        }
    </style>
@endsection
@section('content')

    <!-- BEGIN: Content-->
    {{-- <div class="app-content content "> --}}

    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            @if (Session::has('success'))
                <div class="alert alert-success">
                    <ul style="margin-top: 1rem;">
                        <li>{{ Session::get('success') }}</li>
                    </ul>
                </div>
            @endif

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Edit BannerAds') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{ url('/seller/banner-ads') }}">{{ __('Banner Ads') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('Edit BannerAds') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="content-body">

            <!-- Basic multiple Column Form section start -->
            <section id="multiple-column-form">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('Edit BannerAds') }}</h4>

                            </div>
                            <p style="margin-left: 21px;">{{ __('Create_Banner _Description') }}</p>
                            <hr>
                            <div class="card-body">
                                <form class="basicform" action="{{ route('seller.banner-ads.update', $info->id) }}"
                                    method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">{{ __('Thumbnail') }}<span style="color:red;">(</span> 550 x
                                                    220 mm <span style="color:red;">)</span></label>
                                                <input type="file" class="form-control" accept="Image/*" name="file"
                                                    id="file" value="{{ $info->name ?? '' }}">
                                                <span id="file_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">{{ __('Title') }}</label>
                                                <input type="text" name="title" class="form-control" required
                                                    value="{{ $info->title ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">{{ __('Description') }}</label>
                                                <textarea name="description" class="form-control" style="height:40px;" required>{{ $info->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">{{ __('Button Text') }}</label>
                                                <input type="text" name="btn_text" class="form-control" required
                                                    value="{{ $info->btn_text ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">{{ __('Position') }}</label>
                                                <select class="form-select" name="position">
                                                    <option value="left"
                                                        {{ $info->position == 'left' ? 'selected' : '' }}>
                                                        {{ __('Left') }}</option>
                                                    <option value="right"
                                                        {{ $info->position == 'right' ? 'selected' : '' }}>
                                                        {{ __('Right') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">{{ __('Url') }}</label>
                                                <input type="text" name="url" required=""
                                                    value="{{ $info->url ?? '' }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">{{ __('Text Color') }}</label>
                                                <input type="text" name="text_color" required=""
                                                    value="{{ $info->text_color ?? '' }}" class="form-control rgcolorpicker">
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <button type="submit" id="submit"
                                                class="btn btn-primary me-1 basicbtn">{{ __('Update') }}</button>

                                        </div>


                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Basic Floating Label Form section end -->

        </div>
    </div>
    </div>
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/color.js') }}"></script>
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script>
        $('#file').bind('change', function() {
            $("#file_error").html("");
            $(".alertbox").css("border-color", "#F0F0F0");
            var file_size = $('#file')[0].files[0].size;
            if (file_size >= 1048576) {
                $("#file_error").html(" Recommended file size is less than 1 mb");
                $("#file_error").css("color", "red");
                $(".alertbox").css("border-color", "#FF0000");
                $('#submit').hide();
                return false;
            } else {
                $('#submit').show();
            }
            return true;

        });
    </script>
@endsection
