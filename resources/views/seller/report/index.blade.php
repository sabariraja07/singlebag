@extends('layouts.seller')
@section('title', 'Analytics')
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/form-pickadate.css') }}">
    <style>
        @media screen and (min-width:500px) {
            .data_format {
                margin-top: -41px !important;
                padding-left: 1725px !important;
            }
        }

        @media screen and (max-width:1500px) {
            .data_format {
                margin-top: -41px !important;
                padding-left: 804px !important;
            }
        }

        @media screen and (max-width:2000px) {
            .data_format {
                margin-top: -41px !important;
                padding-left: 574px !important;
            }
        }

        .card-icon .fas {
            line-height: 3.5;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Analytics') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('Analytics') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
            </div>
        </div><br>
        <div>
            <h4>{{ __('Orders') }}</h4>
            <form class="card-header-form">
                <div class="d-flex data_format">
                    <input type="text"  name="start"
                        class="form-control flatpickr-basic" placeholder="{{ \Carbon\Carbon::now()->toDateString(); }}" value="{{ $start }}" />

                    <input type="text"  name="end"
                        class="form-control flatpickr-basic" placeholder="{{ \Carbon\Carbon::now()->toDateString(); }}" value="{{ $end }}" />


                    <button class="btn btn-outline-primary waves-effect" type="submit">
                        <i class="ph-magnifying-glass-bold"></i>
                    </button>
                </div>
            </form>
        </div><br>
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0"> {{ number_format($total) }}</h2>
                            <p class="card-text">{{ __('Total Orders') }}</p>
                        </div>
                        <div class="avatar bg-light-primary p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="cpu" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0">{{ number_format($canceled) }}</h2>
                            <p class="card-text">{{ __('Order Cancelled') }}</p>
                        </div>
                        <div class="avatar bg-light-success p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="server" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(current_shop_type() != 'reseller')
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0">{{ number_format($proccess) }}</h2>
                            <p class="card-text">{{ __('In Processing') }}</p>
                        </div>
                        <div class="avatar bg-light-danger p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="activity" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0">{{ number_format($picked_up) }}</h2>
                            <p class="card-text">{{ __('Order Picked Up') }}</p>
                        </div>
                        <div class="avatar bg-light-danger p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="activity" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0">{{ number_format($completed) }}</h2>
                            <p class="card-text">{{ __('Order Complete') }}</p>
                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="alert-octagon" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0">{{ amount_format($amounts) }}</h2>
                            <p class="card-text">{{ __('Total Amount') }}</p>
                        </div>
                        <div class="avatar bg-light-success p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="dollar-sign" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0"> {{ amount_format($amount_cancel) }}</h2>
                            <p class="card-text">{{ __('Cancelled Amount') }}</p>
                        </div>
                        <div class="avatar bg-light-danger p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="box" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0"> {{ amount_format($amount_proccess) }}</h2>
                            <p class="card-text">{{ __('Pending Amount') }}</p>
                        </div>
                        <div class="avatar bg-light-info p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="server" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0"> {{ amount_format($amount_completed) }}</h2>
                            <p class="card-text">{{ __('Earnings Amount') }}</p>
                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="life-buoy" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(current_shop_type() != 'reseller')
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0">{{ amount_format($amount_revenue) }}</h2>
                            <p class="card-text">{{ __('Revenue') }}</p>
                        </div>
                        <div class="avatar bg-light-primary p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="grid" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0">{{ number_format($register_order) }}</h2>
                            <p class="card-text">{{ __('Register Customers') }}</p>
                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="user" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0"> {{ number_format($guest_order) }}</h2>
                            <p class="card-text">{{ __('Guest Customers') }}</p>
                        </div>
                        <div class="avatar bg-light-success p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="user" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-sm-12">
                <div class="card card-primary">
                    <div class="card-body">


                        <div class="table-responsive">

                            <div class="row">
                                <div class="col-12">

                                    <div class="card mt-4">
                                        <div class="card-header">

                                            <h4 class="card-header-title">{{ __('Revenue') }} <img height="20"
                                                    id="revenue_performance">
                                            </h4>
                                            <div class="card-header-action">

                                                <select class="form-select" id="revenue_perfomace_days" style="width:154px !important;">
                                                    <option value="7">{{ __('Last 7 Days') }}</option>
                                                    <option value="15">{{ __('Last 15 Days') }}</option>
                                                    <option value="30">{{ __('Last 30 Days') }}</option>
                                                    <option value="365">{{ __('Last 365 Days') }}</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="myChart" height="150"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="revenue_perfomance" value="{{ url('/seller/revenue/perfomance') }}">

@endsection
@section('page-script')
    <script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-pickers.js') }}"></script>
@endsection
