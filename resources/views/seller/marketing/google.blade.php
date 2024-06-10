  
@extends('layouts.seller')
@section('title', 'Google Analytics')
@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="./admin/vendors/css/forms/wizard/bs-stepper.min.css">
  <link rel="stylesheet" type="text/css" href="./admin/css/core/menu/menu-types/vertical-menu.min.css">
  <link rel="stylesheet" type="text/css" href="./admin/css/plugins/forms/form-validation.css">
  <link rel="stylesheet" type="text/css" href="./admin/css/plugins/forms/form-wizard.min.css">
  <link rel="stylesheet" type="text/css" href="./admin/css/components.min.css">
@endsection
@section('content')
    <!-- BEGIN: Content-->
    {{-- <div class="app-content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div> --}}
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
                            <h2 class="content-header-title float-start mb-0">{{ __('Marketing Tools') }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">{{ __('Marketing Tools') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ __('Google Analytics') }}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-pills mb-2">
                            <li class="nav-item">
                                <a class="nav-link active" href="#">
                                    <i data-feather="activity" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">{{ __('Google Analytics') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/seller/marketing/tag-manager') }}">
                                    <i data-feather="tag" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">{{ __('Google Tag Manager') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/seller/marketing/facebook-pixel') }}">
                                    <i data-feather="facebook" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">&nbsp;&nbsp;{{ __('Facebook Pixel') }}&nbsp;&nbsp;</span>
                                </a>
                            </li>
                            @if(current_shop_type() == 'seller')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/seller/marketing/whatsapp') }}">
                                    <i data-feather="message-circle" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">&nbsp;&nbsp;{{ __('Whatsapp API') }}&nbsp;&nbsp;</span>
                                </a>
                            </li>
                            @endif
                        </ul>

                        <!-- security -->

                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">{{ __('Google Analytics') }}</h4>
                            </div>
                            <div class="card-body pt-1">
                                <!-- form -->
                                <form class="basicform" enctype="multipart/form-data" action="{{ route('seller.marketing.store') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="type" value="google-analytics">
                                    <div class="row">
                                        <div class="col-12 col-sm-6 mb-1">
                                            <label class="col-form-label" > <a href="https://developers.google.com/analytics/devguides/collection/gtagjs" target="_blank">{{ __('Ga_measurement_id.') }}</a></label>
                                            <input type="text" class="form-control" required="" name="ga_measurement_id" placeholder="UA-123456789" value="{{ $info->ga_measurement_id ?? '' }}">
                                        </div>
                                        <div class="col-12 col-sm-6 mb-1">
                                            <label class="col-form-label" >{{ __('ANALYTICS VIEW ID') }}</label>
                                            <input type="text" class="form-control" required="" name="analytics_view_id" placeholder="12345678" value="{{ $info->analytics_view_id ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-6 mb-1">
                                            <label class="col-form-label" >{{ __('service-account-credentials.json') }}</label>
                                            <input type="file" name="file" class="form-control" accept=".json">
                                        </div>
                                        <div class="col-12 col-sm-6 mb-1">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
                                        <select class="form-control selectric" name="status">
                                                @if(!empty($google))
                                                <option value="1" @if($google->status  == 1) selected="" @endif>{{ __('Enable') }}</option>
                                                <option value="0"  @if($google->status  == 0) selected="" @endif>{{ __('Disable') }}</option>
                                                @else
                                                <option value="1">{{ __('Enable') }}</option>
                                                <option value="0" >{{ __('Disable') }}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <div class="alert alert-danger" role="alert">
                                                <div class="alert-body"><strong>{{ __('Note:') }}</strong><small>{{ __('After You Update Settings The Action Will Work After 5 Minutes') }}</small></div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary me-1 mt-1">{{ __('Save Changes') }}</button>
                                        </div>
                                    </div>
                                </form>
                                <!--/ form -->
                            </div>
                        </div>
                        <!--/ security -->
                    </div>
                </div>
            </div>
        </div>
    {{-- </div> --}}
    <!-- END: Content-->
@endsection
@section('vendor-script')
    <script src="./admin/vendors/js/forms/wizard/bs-stepper.min.js"></script>
    <script src="./admin/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="./admin/js/scripts/forms/form-wizard.min.js"></script>
    <script src="./admin/js/core/app-menu.min.js"></script>
    {{-- <script src="./admin/js/core/app.min.js"></script> --}}
    <script src="./admin/js/scripts/customizer.min.js"></script>
@endsection
