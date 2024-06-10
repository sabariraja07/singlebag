@extends('layouts.seller')
@section('title', 'BannerAds')
@section('page-style')
    <link rel="stylesheet" type="text/css" href="./admin/css/pages/style.css">
    <style>
        .label_alignment {
            font-size: 15px !important;
            color: #5e5873 !important;
        }

        .form-group {
            margin-bottom: 25px;
        }
    </style>
@endsection
@section('content')
    <!-- BEGIN: Content-->
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
                <div class="alert alert-success">
                    <ul style="margin-top: 1rem;">
                        <li>{{ Session::get('error') }}</li>
                    </ul>
                </div>
            @endif
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Banner Ads') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item">{{ __('Online store') }}
                                </li>
                                <li class="breadcrumb-item active">{{ __('Banner Ads') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="d-flex flex-row justify-content-between mt-1">
                <div></div>
                <a href="javascript:void(0)" data-bs-target="#addbump" data-bs-toggle="modal">
                    <span class="btn btn-primary mb-1">{{ __('create') }}</span>
                </a>
            </div>
            <div class="row gy-2 mobile_view" id="mobile_view">
                @foreach ($posts as $row)
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body" style="padding: 0.5rem !important;">
                                <div class="position-relative rounded p-25">
                                    <div class="dropdown dropstart btn-pinned">
                                        <a class="btn btn-icon rounded-circle hide-arrow dropdown-toggle p-0"
                                            href="javascript:void(0)" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i data-feather="more-vertical" class="font-medium-4"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    href="{{ route('seller.banner-ads.edit', $row->id) }}">
                                                    <i data-feather="edit-2" class="me-50"></i><span>{{ __('Edit') }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    href="{{ route('seller.banner-ads.show', $row->id) }}">
                                                    <i data-feather="trash-2" class="me-50"></i><span>{{ __('Delete') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="d-flex">
                                        <a href="javascript:void(0)" class="me-2">
                                            <img class="rounded img"
                                                src="{{ $row->image }}"
                                                alt="" />
                                        </a>
                                        <div class="blog-info" style="max-width: 110px !important;">
                                            <h6 class="blog-recent-post-title">
                                                <span class="text-body-heading">{{ $row->title ?? '' }}</span>

                                            </h6>
                                            <div class="text-muted mb-0" style="margin-bottom: 7px !important;">
                                                {{ $row->description ?? '' }}</div>
                                            <span class="badge badge-light-primary mb-1">{{ __('Active') }}</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="card mobile_view desktop_view">
                <div class="card-body">


                    <div class="float-left mb-2">
                    </div>
                    <div class="table-responsive custom-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="am-title">{{ __('Image') }}</th>
                                    <th class="am-title">{{ __('Url') }}</th>
                                    <th class="am-title">{{ __('Title') }}</th>
                                    <th class="am-title">{{ __('Description') }}</th>
                                    <th class="am-title">{{ __('Position') }}</th>
                                    <th class="text-right">{{ __('CREATED AT') }}</th>
                                    <th class="text-right">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($posts as $row)
                                    <tr>
                                        <td class="text-left"><img src="{{ $row->image }}"
                                                height="100"></td>
                                        <td class="text-left">{{ $row->url }}</td>
                                        <td class="text-left">
                                            {{ $row->title }}
                                        </td>
                                        <td class="text-left">
                                            {{ $row->description }}
                                        </td>
                                        <td class="text-left">
                                            {{ $row->position }}
                                        </td>
                                        <td class="text-right">{{ $row->updated_at->diffForHumans() }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('seller.banner-ads.edit', $row->id) }}"
                                                class=" btn-warning" style="padding: 2px 4px 5px 4px;"><i
                                                    data-feather='edit'></i></a>
                                            <a href="{{ route('seller.banner-ads.show', $row->id) }}"
                                                class=" btn-danger  cancel" style="padding: 2px 4px 5px 4px;"><i
                                                    data-feather='trash-2'></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-row justify-content-end mt-2">
                {{ $posts->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
    <!-- END: Content-->
    <div class="modal fade" id="addbump" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">{{ __('Add New Banner') }}</h1>
                        <p>{{ __('Create_Banner _Description') }}</p>

                    </div>
                    <hr />
                    <form class="basicform_with_reload row gy-1 pt-75" action="{{ route('seller.banner-ads.store') }}"
                        method="post">

                        @csrf
                        @method('POST')

                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('Select Image') }}<span style="color:red;"> (</span> 550 x 220 mm <span
                                    style="color:red;">)</span></label>
                            <input type="file" accept="Image/*" name="file" id="file" required=""
                                class="form-control">
                            <span id="file_error"></span>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('Title') }}</label>
                            <input type="text" name="title" required="" class="form-control">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('Description') }}</label>
                            <input type="text" name="description" required="" class="form-control">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('Button Text') }}</label>
                            <input type="text" name="btn_text" required="" class="form-control">
                        </div>


                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('Position') }}</label>
                            <select class="form-select" name="position">
                                <option value="right" selected="">{{ __('Right') }}</option>
                                <option value="left">{{ __('Left') }}</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('Url') }}</label>
                            <input type="text" name="url" required="" value="#" class="form-control">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('Text Color') }}</label>
                            <input type="text" name="text_color" required="" class="form-control rgcolorpicker">
                        </div>


                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1 basicbtn" id="submit">{{ __('Save') }}</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                aria-label="Close">{{ __('Discard') }}
                                
                            </button>
                        </div>
                    </form>
                </div>
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
