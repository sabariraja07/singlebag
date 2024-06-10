@extends('layouts.seller')
@section('title', 'Dashboard')
@section('page-style')
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}"> --}}
    <style>
    .statistic-details {
        display: flex;
        flex-wrap: wrap;
    }
    .statistic-details .statistic-details-item {
    flex: 1;
    padding: 17px 10px;
    text-align: center;
}
.margin_alignment{
    margin-right: 0.5rem !important;
}
/* @media (max-width:768px) {
        .col-md-3 {
        flex: 0 0 auto;
        width:33%;
        padding-top: 9px;
        }
    } */

    /* Desktop View */
    @media screen and (min-width: 992px) {
            .qr_set {
             padding: 1px 1px 1px 8px !important;
            }

        }
         /* Mobile View */
        @media screen and (max-width: 900px) {
            .qr_set {
                padding: 1.5rem 4.5rem !important;
            }
            .pie_set{
                padding: 1.5rem 5.5rem !important;
            }
            
        }
    </style>
@endsection
@section('content')
    @if (Auth::user()->status == 2 || Auth::user()->status == 3)
        <div class="row mt-3">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <p>
                            {{ __('Dear,') }} <b>{{ Auth::user()->fullname }}</b>{{ __(' Your Account Currently') }} <b
                                class="text-danger">
                                @if (Auth::user()->status == 2)
                                    {{ __('Suspened') }}
                                @elseif(Auth::user()->status == 3)
                                    {{ __('Pending') }}
                                @endif
                            </b>
                            {{ __('Mode And Also Disabled All Functionality If You Are Not Complete Your Payment Please Complete Your Payment From') }}
                            <a href="{{ route('merchant.plan') }}">{{ __('Here') }}</a>
                            {{ __('Or Also Contact With Support Team') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @php
        $plan = user_limit();
        $shops = \App\Models\Shop::where('status', 'active')
            ->where('id', current_shop_id())
            ->whereHas('subscription', function ($q) {
                $q->where('status', 1);
            })
            ->with('domain', 'user', 'subscription')
            ->first();
        
    @endphp
    <!-- BEGIN: Content-->
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="float-start mb-0">{{ __('Dashboard') }}</h2>

                    </div>
                </div>
            </div>

        </div>
        <div class="content-body">
            <!-- Statistics card section -->
            <section id="statistics-card">
                <!-- Miscellaneous Charts -->
                <div class="row match-height">
                    <div class="col-lg-6">
                        <div class="card card-statistics">
                            <div class="card-body statistics-body" style="padding: 0rem 2.4rem !important;">
                                <div class="row mb-2">
                                    <div class="col-md-6 col-sm-6 col-6 mb-2 mb-md-0 mt-2">
                                        <h4 class="card-title">{{ __('Order Statistics') }}</h4>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-6 mb-2 mb-md-0 mt-2">
                                        <div class="d-flex align-items-center">
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-outline-primary btn-sm dropdown-toggle budget-dropdown"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                    id="orders-month" id="orders-month">
                                                    {{ Date('F') }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item month" href="javascript:void(0);" data-month="January"
                                                        @if (Date('F') == 'January') active @endif">{{ __('January') }}</a>
                                                    <a class="dropdown-item month" href="javascript:void(0);" data-month="February"
                                                        @if (Date('F') == 'February') active @endif">{{ __('February') }}</a>
                                                    <a class="dropdown-item month" href="javascript:void(0);" data-month="March"
                                                        @if (Date('F') == 'March') active @endif">{{ __('March') }}</a>
                                                    <a class="dropdown-item month" href="javascript:void(0);" data-month="April"
                                                        @if (Date('F') == 'April') active @endif">{{ __('April') }}</a>
                                                    <a class="dropdown-item month" href="javascript:void(0);" data-month="May"
                                                        @if (Date('F') == 'May') active @endif">{{ __('May') }}</a>
                                                    <a class="dropdown-item month" href="javascript:void(0);" data-month="June"
                                                        @if (Date('F') == 'June') active @endif">{{ __('June') }}</a>
                                                    <a class="dropdown-item month" href="javascript:void(0);" data-month="July"
                                                        @if (Date('F') == 'July') active @endif">{{ __('July') }}</a>
                                                    <a class="dropdown-item month" href="javascript:void(0);" data-month="August"
                                                        @if (Date('F') == 'August') active @endif">{{ __('August') }}</a>
                                                    <a class="dropdown-item month" href="javascript:void(0);" data-month="September"
                                                        @if (Date('F') == 'September') active @endif">{{ __('September') }}</a>
                                                    <a class="dropdown-item month" href="javascript:void(0);" data-month="October"
                                                        @if (Date('F') == 'October') active @endif">{{ __('October') }}</a>
                                                    <a class="dropdown-item month" href="javascript:void(0);" data-month="November"
                                                        @if (Date('F') == 'November') active @endif">{{ __('November') }}</a>
                                                    <a class="dropdown-item month" href="javascript:void(0);" data-month="December"
                                                        @if (Date('F') == 'December') active @endif">{{ __('December') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12 mb-2 mb-md-0">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-primary margin_alignment">
                                                <div class="avatar-content">
                                                    <i data-feather="trending-up" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h4 class="fw-bolder mb-0" id="pending_order"></h4>
                                                <p class="card-text font-small-3 mb-0" style="font-size: 10px !important;">{{ __('Pending') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12 mb-2 mb-md-0">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-info margin_alignment">
                                                <div class="avatar-content">
                                                    <i data-feather="user" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h4 class="fw-bolder mb-0" id="completed_order"></h4>
                                                <p class="card-text font-small-3 mb-0" style="font-size: 10px !important;">{{ __('Completed') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-12 mb-2 mb-sm-0">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-danger margin_alignment">
                                                <div class="avatar-content">
                                                    <i data-feather="box" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h4 class="fw-bolder mb-0" id="shipping_order"></h4>
                                                <p class="card-text font-small-3 mb-0" style="font-size: 10px !important;">{{ __('Processing') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12 mb-2 mb-sm-0">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-success margin_alignment">
                                                <div class="avatar-content">
                                                    <i data-feather="dollar-sign" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h4 class="fw-bolder mb-0" id="total_order"></h4>
                                                <p class="card-text font-small-3 mb-0" style="font-size: 10px !important;">{{ __('Orders') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header flex-column align-items-start pb-0">
                                <div class="avatar bg-light-primary p-50 m-0">
                                    <div class="avatar-content">
                                        <i data-feather="dollar-sign" class="font-medium-5"></i>
                                    </div>
                                </div>
                                <h2 class="fw-bolder mt-1" id="sales_of_earnings""></h2>
                                <p class="card-text">{{ __('Total Sales Of Earnings') }} - {{ date('Y') }}</p>
                            </div>
                            <div>
                                <canvas id="sales_of_earnings_chart" height="80"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header flex-column align-items-start pb-0">
                                <div class="avatar bg-light-success p-50 m-0">
                                    <div class="avatar-content">
                                        <i data-feather="credit-card" class="font-medium-5"></i>
                                    </div>
                                </div>
                                <h2 class="fw-bolder mt-1" id="total_sales"></h2>
                                <p class="card-text">{{ __('Total Sales') }} - {{ date('Y') }}</p>
                            </div>
                            <div>
                                <canvas id="total-sales-chart" height="80"></canvas>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row match-height"> --}}
                    <!-- User Timeline Card -->
                    <div class="col-lg-6 col-12">
                        <div class="card card-user-timeline">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <i data-feather="list" class="user-timeline-title-icon"></i>
                                    <h4 class="card-title">{{ __('Product') }}</h4>
                                </div>
                                {{-- <i data-feather="more-vertical" class="font-medium-3 cursor-pointer"></i> --}}
                            </div>
                            <div class="card-body">
                                <ul class="timeline ms-50">
                                    <li class="timeline-item">
                                        <span class="timeline-point timeline-point-indicator"></span>
                                        <div class="timeline-event">
                                            <div
                                                class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                <h6>{{ __('Add Categories') }}</h6>
                                            </div>
                                            <p>{{ __('Add new category with name and a thumbnail image. This can be assigned to your products.') }}</p>
                                            <div class="d-flex flex-row align-items-center">
                                                <a href="{{ route('seller.category.create') }}"> <span
                                                        class="badge badge-light-primary profile-badge">{{ __('Add +') }}</span></a>

                                            </div>
                                        </div>
                                    </li>
                                    <li class="timeline-item">
                                        <span
                                            class="timeline-point timeline-point-warning timeline-point-indicator"></span>
                                        <div class="timeline-event">
                                            <div
                                                class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                <h6>{{ __('Add Brands') }}</h6>
                                            </div>
                                            <p>{{ __('Add Brands with name and a thumbnail image. This would help to recognize your products with their brand.') }}</p>
                                            <div class="d-flex flex-row align-items-center">
                                                <a href="{{ route('seller.brand.create') }}"> <span
                                                        class="badge badge-light-primary profile-badge">{{ __('Add +') }}</span></a>
                                            </div>
                                        </div>
                                    </li>
                                    @if(current_shop_type() != 'reseller')
                                    <li class="timeline-item">
                                        <span class="timeline-point timeline-point-info timeline-point-indicator"></span>
                                        <div class="timeline-event">
                                            <div
                                                class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                <h6>{{ __('Add Attributes') }}</h6>
                                            </div>
                                            <p>{{ __('Add Attributes with values and assign to your products. This would help your user to filter all your products while searching.') }}</p>
                                            <div class="d-flex flex-row align-items-center">
                                                <a href="{{ route('seller.attribute.create') }}"> <span
                                                        class="badge badge-light-primary profile-badge">{{ __('Add +') }}</span></a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="timeline-item">
                                        <span class="timeline-point timeline-point-danger timeline-point-indicator"></span>
                                        <div class="timeline-event">
                                            <div
                                                class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                <h6>{{ __('Add Products') }}</h6>

                                            </div>
                                            <p>{{ __('While adding products, do not ignore to fill in all the required fields and follow all the adding rules.') }}</p>
                                            <div class="d-flex flex-row align-items-center">
                                                <a href="{{ route('seller.products.create') }}"> <span
                                                        class="badge badge-light-primary profile-badge">{{ __('Add +') }}</span></a>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if(current_shop_type() == 'reseller')
                                    <li class="timeline-item">
                                        <span class="timeline-point timeline-point-danger timeline-point-indicator"></span>
                                        <div class="timeline-event">
                                            <div
                                                class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                <h6>{{ __('Add Products') }}</h6>

                                            </div>
                                            <p>{{ __('While adding products, do not ignore to fill in all the required fields and follow all the adding rules.') }}</p>
                                            <div class="d-flex flex-row align-items-center">
                                                <a href="{{ route('seller.supplier.products') }}"> <span
                                                        class="badge badge-light-primary profile-badge">{{ __('Add +') }}</span></a>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="card card-app-design">
                            <div class="card-body">
                                {{-- <span class="badge badge-light-primary">03 Sep, 20</span> --}}
                                @if(current_shop_type() != 'supplier')
                                <h4 class="card-title mt-1 mb-75">{{ __('Storefront Customization') }}</h4>
                                <p class="card-text font-small-2 mb-2">{{ __('Customization settings allow you to add/edit the sliders and featured banners in your storefront.') }}
                                    
                                </p>
                                <div class="design-group">
                                    <a href="{{ route('seller.theme.index') }}"> <span
                                            class="badge badge-light-primary">{{ __('Theme') }}</span></a>
                                    <a href="{{ route('seller.banner-ads.index') }}"> <span
                                            class="badge badge-light-primary">{{ __('Banners') }}</span></a>
                                    <a href="{{ route('seller.bump-ads.index') }}"> <span
                                            class="badge badge-light-primary">{{ __('Bumps') }}</span></a>
                                    <a href="{{ route('seller.slider.index') }}"> <span
                                            class="badge badge-light-primary">{{ __('Sliders') }}</span></a>

                                </div>
                                @endif
                                <h4 class="card-title mt-1 mb-75">{{ __('Store Setup') }}</h4>
                                <p class="card-text font-small-2 mb-2">{{ __('Setup your store with general details, we have options to add/edit logo & favicon , pages , menus in your storefront.') }}
                                    
                                </p>
                                <div class="design-group">
                                    <a href="{{ route('seller.settings.show', 'shop-settings') }}"> <span
                                            class="badge badge-light-primary">{{ __('Store Details') }}</span></a>
                                    <a href="{{ route('seller.settings.show', 'logo') }}"> <span
                                            class="badge badge-light-primary">{{ __('Logo & Favicon') }}</span></a>
                                    @if(current_shop_type() != 'supplier')
                                    <a href="{{ route('seller.menu.index') }}"> <span
                                            class="badge badge-light-primary">{{ __('Menus') }}</span></a>
                                    <a href="{{ route('seller.page.index') }}"> <span
                                            class="badge badge-light-primary">{{ __('Pages') }}</span></a>
                                    @endif

                                </div>
                                @if(current_shop_type() != 'reseller')
                                <h4 class="card-title mt-1 mb-75">{{ __('Shipping Pricings') }}</h4>
                                <p class="card-text font-small-2 mb-2">{{ __('Setup specific shipping charges for your product on basis of your delivery locations.') }}
                                    
                                </p>
                                <div class="design-group">
                                    {{-- <a href="{{ route('seller.location.index') }}"> <span
                                            class="badge badge-light-primary">Location</span></a> --}}
                                    <a href="{{ route('seller.shipping-methods.index') }}"> <span
                                            class="badge badge-light-primary">{{ __('Shipping Method & Location') }}</span></a>
                                </div>
                                @endif
                                @if(current_shop_type() != 'supplier')
                                <h4 class="card-title mt-1 mb-75">{{ __('User Terms') }}</h4>
                                <p class="card-text font-small-2 mb-2">{{ __('Update your Terms of service, that would be the legal agreements with the user who wants to use your service. The user must agree to abide by the terms while signup.') }}
                                   
                                </p>
                                <div class="design-group">
                                    <a href="{{ route('seller.user.term') }}"> <span
                                            class="badge badge-light-primary">{{ __('User Terms') }}</span></a>

                                </div>
                                @endif
                                @if(current_shop_type() == 'seller')
                                <h4 class="card-title mt-1 mb-75">{{ __('Payment settings') }}</h4>
                                <p class="card-text font-small-2 mb-2">{{ __('Setup your preferred payment gateway, on which your customers will be transferring payments to you.') }}
                                    
                                </p>
                                <div class="design-group">
                                    <a href="{{ route('seller.settings.show', 'payment') }}"> <span
                                            class="badge badge-light-primary">{{ __('Payment Options') }}</span></a>

                                </div>
                                @endif



                            </div>
                        </div>
                    </div>

                    {{-- </div> --}}

                    <div class="col-12 col-xl-9">

                        <div class="row">
                            <div class="col-12">

                                <div class="card mt-4">
                                    <div class="card-header">

                                        <h4 class="card-header-title">{{ __('Earnings performance') }} <img
                                                src="{{ asset('uploads/loader.gif') }}" height="20"
                                                id="earning_performance">
                                        </h4>
                                        <div class="card-header-action" style="width: 148.33px !important;">

                                            <select class="form-select" id="perfomace">
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
                        <div class="row">
                            <div class="col-12 col-md-6">

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col">

                                                <h6 class="text-uppercase text-muted mb-2">
                                                    {{ __('Today\'s Total Sales') }}</h6>

                                                <span class="h2 mb-0" id="today_total_sales"><img
                                                        src="{{ asset('uploads/loader.gif') }}"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col">

                                                <h6 class="text-uppercase text-muted mb-2">{{ __('Today\'s Orders') }}
                                                </h6>

                                                <span class="h2 mb-0" id="today_order"><img
                                                        src="{{ asset('uploads/loader.gif') }}"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col">

                                                <h6 class="text-uppercase text-muted mb-2">{{ __('YESTERDAY') }}</h6>

                                                <span class="h2 mb-0" id="yesterday_total_sales"><img
                                                        src="{{ asset('uploads/loader.gif') }}"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col">

                                                <h6 class="text-uppercase text-muted mb-2">{{ __('7 DAYS') }}</h6>

                                                <span class="h2 mb-0" id="last_seven_days_total_sales"><img
                                                        src="{{ asset('uploads/loader.gif') }}"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col">

                                                <h6 class="text-uppercase text-muted mb-2">{{ __('THIS MONTH') }}</h6>

                                                <span class="h2 mb-0" id="monthly_total_sales"><img
                                                        src="{{ asset('uploads/loader.gif') }}"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col">

                                                <h6 class="text-uppercase text-muted mb-2">{{ __('LAST MONTH') }}</h6>

                                                <span class="h2 mb-0" id="last_month_total_sales"><img
                                                        src="{{ asset('uploads/loader.gif') }}"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(current_shop_type() != 'supplier')
                                @if (filter_var($plan['google_analytics']) == true)
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>{{ __('Site Analytics') }}</h4>
                                                <div class="card-header-action" style="width: 148.33px !important;">
                                                    <select class="form-select" id="days">
                                                        <option value="7">{{ __('Last 7 Days') }}</option>
                                                        <option value="15">{{ __('Last 15 Days') }}</option>
                                                        <option value="30">{{ __('Last 30 Days') }}</option>
                                                        <option value="180">{{ __('Last 180 Days') }}</option>
                                                        <option value="365">{{ __('Last 365 Days') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <canvas id="google_analytics" height="80"></canvas>
                                                <div class="statistic-details mt-sm-4">
                                                    <div class="statistic-details-item">

                                                        <div class="detail-value" id="total_visitors"></div>
                                                        <div class="detail-name">{{ __('Total Vistors') }}</div>
                                                    </div>
                                                    <div class="statistic-details-item">

                                                        <div class="detail-value" id="total_page_views"></div>
                                                        <div class="detail-name">{{ __('Total Page Views') }}</div>
                                                    </div>

                                                    <div class="statistic-details-item">

                                                        <div class="detail-value" id="new_vistors"></div>
                                                        <div class="detail-name">{{ __('New Visitor') }}</div>
                                                    </div>

                                                    <div class="statistic-details-item">

                                                        <div class="detail-value" id="returning_visitor"></div>
                                                        <div class="detail-name">{{ __('Returning Visitor') }}</div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-xl-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="card mt-4">
                                @if(current_shop_type() != 'supplier')
                                    <div class="card-header">

                                        <h4 class="card-header-title plan_name"></h4>

                                        <span class="badge  plan_expire" style="color:#676d7d;"></span>
                                        <img src="{{ asset('uploads/loader.gif') }}" class="plan_load">
                                    </div>
                                @endif
                                    <div class="card-header">

                                        <h4 class="card-header-title">{{ __('Storage usage') }}</h4>

                                        <span class="badge " id="storage_used" style="color:#676d7d;"><img
                                                src="{{ asset('uploads/loader.gif') }}" class="storrage_used"></span>
                                    </div>
                                    <div class="card-header">

                                        <h4 class="card-header-title">{{ __('Products') }}</h4>

                                        <span class="badge  posts_used" style="color:#676d7d;"><img
                                                src="{{ asset('uploads/loader.gif') }}" class="product_used"></span>
                                    </div>
                                    <div class="card-header">

                                        <h4 class="card-header-title">{{ __('Pages') }}</h4>

                                        <span class="badge  pages" style="color:#676d7d;"> <img
                                                src="{{ asset('uploads/loader.gif') }}" class="product_used"></span>
                                    </div>

                                </div>
                                @if(current_shop_type() != 'supplier')
                                @if (Auth::user()->status == 1)
                                    @if (filter_var($plan['qr_code']) == true)
                                        <div class="card">
                                            <div class="card-header">

                                                <h4 class="card-header-title">{{ __('Scan your site') }}</h4>
                                            </div>
                                            <div class="card-body qr-code-main qr_set">

                                                {!! QrCode::size(200)->generate(url('/')) !!}
                                            </div>
                                            <div class="card-footer">
                                                <button class="btn btn-primary col-12" onclick="downloadPng()"
                                                    type="button">{{ __('Download') }}</button>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                                @endif
                                <div class="card">
                                    <div class="card-header">

                                        <h4 class="card-header-title" style="font-size: 14px;">{{ __('Products Limit') }}
                                            <span><span class="text-danger posts_created"></span>/<span
                                                    class="product_capacity">
                                                </span></span>
                                        </h4>
                                    </div>
                                    <div class="card-body pie_set">
                                        {{-- <div id="support-trackers-chart"></div> --}}
                                        <div class="sparkline-pie-product d-inline"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">

                                        <h4 class="card-header-title" style="font-size: 14px;">{{ __('Storage Uses') }}
                                            <span><span class="text-danger storage_used"></span>/<span
                                                    class="storage_capacity">
                                                </span></span>
                                        </h4>
                                    </div>
                                    <div class="card-body pie_set">
                                        {{-- <div id="support-trackers-chart"></div> --}}
                                        <div class="sparkline-pie-storage d-inline" height="50"></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <!--/ Miscellaneous Charts -->


            </section>




        </div>
    </div>
    </div>
    <input type="hidden" id="base_url" value="{{ url('/') }}">
    <input type="hidden" id="site_url" value="{{ url('/') }}">
    <input type="hidden" id="dashboard_static" value="{{ route('seller.dashboard.static') }}">
    <input type="hidden" id="dashboard_perfomance" value="{{ url('/seller/dashboard/perfomance') }}">
    <input type="hidden" id="dashboard_order_statics" value="{{ url('/seller/dashboard/order_statics') }}">
    <input type="hidden" id="gif_url" value="{{ asset('uploads/loader.gif') }}">
    <input type="hidden" id="month" value="{{ date('F') }}">
    @php
        $shop_id = current_shop_id();
        $category_check = \App\Models\Category::where('shop_id', $shop_id)
            ->where('type', 'category')
            ->count();
        $brand_check = \App\Models\Category::where('shop_id', $shop_id)
            ->where('type', 'brand')
            ->count();
        $attibute_check = \App\Models\Category::where('shop_id', $shop_id)
            ->where('type', 'parent_attribute')
            ->count();
        $product_check = \App\Models\Product::where('shop_id', $shop_id)
            ->where('type', 'product')
            ->count();
    @endphp
    <!-- END: Content-->
    
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/seller/dashboard.js') }}"></script>
    {{-- <script src="{{ asset('admin/js/core/apexcharts.min.js')}}"></script> --}}
    {{-- <script src="{{ asset('admin/js/core/dashboard-analytics.js')}}"></script> --}}
    @if (Auth::user()->status == 1)
        @if (filter_var($plan['qr_code']) == true)
            <script type="text/javascript">
                "use strict";

                function downloadPng() {
                    var img = new Image();
                    img.onload = function() {
                        var canvas = document.createElement("canvas");
                        canvas.width = img.naturalWidth;
                        canvas.height = img.naturalHeight;
                        var ctxt = canvas.getContext("2d");
                        ctxt.fillStyle = "#fff";
                        ctxt.fillRect(0, 0, canvas.width, canvas.height);
                        ctxt.drawImage(img, 0, 0);
                        var a = document.createElement("a");
                        a.href = canvas.toDataURL("image/png");
                        a.download = "qrcode.png"
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    };
                    var innerSvg = document.querySelector(".qr-code-main svg");
                    var svgText = (new XMLSerializer()).serializeToString(innerSvg);
                    img.src = "data:image/svg+xml;utf8," + encodeURIComponent(svgText);
                } {
                    // "mode"=> "full",
                    // "isActive": false
                }
            </script>
            <style>
                @keyframes progress {
                    0% {
                        --percentage: 0;
                    }

                    100% {
                        --percentage: var(--value);
                    }
                }

                @property --percentage {
                    syntax: '<number>';
                    inherits: true;
                    initial-value: 0;
                }

                [role="progressbar"] {
                    --percentage: var(--value);
                    --primary: #7367f0 ;
                    --secondary: #555181;
                    --size: 180px;
                    animation: progress 2s 0.5s forwards;
                    width: var(--size);
                    aspect-ratio: 1;
                    border-radius: 50%;
                    position: relative;
                    overflow: hidden;
                    display: grid;
                    place-items: center;
                }

                [role="progressbar"]::before {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: conic-gradient(var(--primary) calc(var(--percentage) * 1%), var(--secondary) 0);
                    mask: radial-gradient(white 55%, transparent 0);
                    mask-mode: alpha;
                    -webkit-mask: radial-gradient(#0000 55%, #000 0);
                    -webkit-mask-mode: alpha;
                }

                [role="progressbar"]::after {
                    counter-reset: percentage var(--value);
                    content: 'Unlimited';
                    font-family: Helvetica, Arial, sans-serif;
                    font-size: calc(var(--size) / 10);
                    color: var(--primary);
                }
            </style>
        @endif
    @endif
@endsection
