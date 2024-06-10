@extends('layouts.seller')
@section('title', 'Menu')
@section('page-style')
    <link rel="stylesheet" type="text/css" href="./admin/css/pages/style.css">
    <style>
        .text-right {
            text-align: right !important;
        }
    </style>
@endsection
@section('content')
    <!-- BEGIN: Content-->
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Menus') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item">{{ __('Online store') }}
                                </li>
                                <li class="breadcrumb-item active">{{ __('Menus') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section id="ApiKeyPage">
                <!-- Start: mobile view -->
                <div class="row gy-2 mobile_view" style="--bs-gutter-y: -0.5rem !important;" id="mobile_view">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body" style="padding: 0.5rem !important;">
                                <div class="position-relative rounded p-25">
                                    <div class="dropdown dropstart btn-pinned" style="top: 0.2rem !important;">
                                        <a class="btn btn-icon rounded-circle hide-arrow dropdown-toggle p-0"
                                            href="javascript:void(0)" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i data-feather="more-vertical" class="font-medium-4"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    href="{{ route('seller.menu.show', 'header') }}">
                                                    <i data-feather="edit-2" class="me-50"></i><span>{{ __('Edit') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="d-flex">
                                        <div class="blog-info">
                                            <h6 class="blog-recent-post-title">
                                                <span class="text-body-heading">{{ __('Header Menu') }}</span>

                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body" style="padding: 0.5rem !important;">
                                <div class="position-relative rounded p-25">
                                    <div class="dropdown dropstart btn-pinned" style="top: 0.2rem !important;">
                                        <a class="btn btn-icon rounded-circle hide-arrow dropdown-toggle p-0"
                                            href="javascript:void(0)" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i data-feather="more-vertical" class="font-medium-4"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    href="{{ route('seller.menu.show', 'left') }}">
                                                    <i data-feather="edit-2" class="me-50"></i><span>{{ __('Edit') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="d-flex">
                                        <div class="blog-info">
                                            <h6 class="blog-recent-post-title">
                                                <span class="text-body-heading">{{ __('Footer Left Menu') }}</span>

                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body" style="padding: 0.5rem !important;">
                                <div class="position-relative rounded p-25">
                                    <div class="dropdown dropstart btn-pinned" style="top: 0.2rem !important;">
                                        <a class="btn btn-icon rounded-circle hide-arrow dropdown-toggle p-0"
                                            href="javascript:void(0)" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i data-feather="more-vertical" class="font-medium-4"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    href="{{ route('seller.menu.show', 'center') }}">
                                                    <i data-feather="edit-2" class="me-50"></i><span>{{ __('Edit') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="d-flex">
                                        <div class="blog-info">
                                            <h6 class="blog-recent-post-title">
                                                <span class="text-body-heading">{{ __('Footer Center Menu') }}</span>

                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body" style="padding: 0.5rem !important;">
                                <div class="position-relative rounded p-25">
                                    <div class="dropdown dropstart btn-pinned" style="top: 0.2rem !important;">
                                        <a class="btn btn-icon rounded-circle hide-arrow dropdown-toggle p-0"
                                            href="javascript:void(0)" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i data-feather="more-vertical" class="font-medium-4"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    href="{{ route('seller.menu.show', 'right') }}">
                                                    <i data-feather="edit-2" class="me-50"></i><span>{{ __('Edit') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="d-flex">
                                        <div class="blog-info">
                                            <h6 class="blog-recent-post-title">
                                                <span class="text-body-heading">{{ __('Footer Right Menu') }}</span>

                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End: mobile view -->

                <!-- Start: desktop view -->

                <div class="row mobile_view desktop_view">
                    <div class="col-12 col-sm-12 col-lg-12">
                        <div class="card">

                            <div class="card-body">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('Menus') }}</h4>

                                </div>
                                <p style="margin-left: 21px;">{{ __('Click on the edit button to update the menu items and list order.') }}</p>
                                <hr>
                                <table class="table table-hover card-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('MENU POSITION') }}</th>
                                            <th class="text-right">{{ __('CUSTOMIZE') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ __('Header Menu') }}</td>
                                            <td class="text-right"><a href="{{ route('seller.menu.show', 'header') }}"
                                                    class="btn btn-primary btn-sm"><i data-feather="edit"></i></a></td>
                                        </tr>

                                        <tr>
                                            <td>{{ __('Footer Left Menu') }}</td>
                                            <td class="text-right"><a href="{{ route('seller.menu.show', 'left') }}"
                                                    class="btn btn-primary btn-sm"><i data-feather="edit"></i></a></td>
                                        </tr>

                                        <tr>
                                            <td>{{ __('Footer Center Menu') }}</td>
                                            <td class="text-right"><a href="{{ route('seller.menu.show', 'center') }}"
                                                    class="btn btn-primary btn-sm"><i data-feather="edit"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Footer Right Menu') }}</td>
                                            <td class="text-right"><a href="{{ route('seller.menu.show', 'right') }}"
                                                    class="btn btn-primary btn-sm"><i data-feather="edit"></i></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End: desktop view -->

            </section>

        </div>
    </div>
    <!-- END: Content-->
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>

@endsection
