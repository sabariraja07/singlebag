@extends('layouts.agent')
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
    </style>
@endsection
@section('content')
    {{-- @if (Auth::user()->status == 2 || Auth::user()->status == 3)
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
    @endif --}}
    @php
        $plan = user_limit();
        $agent = Auth::guard('customer')->user()->id;
        $shops = \App\Models\Shop::where('status', 'active')
            ->where('id', current_shop_id())
            ->whereHas('subscription', function ($q) {
                $q->where('status', 1);
            })
            ->with('domain', 'user', 'subscription')
            ->first();
        
    @endphp
    @php
   $cus= Auth::guard('customer');
   $admon = request()->getHost() . '.admin';
  $val = request()->getHost();
//   $vall = getHost();
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
                    
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header flex-column align-items-start pb-0">
                                <div class="avatar bg-light-primary p-50 m-0">
                                    <div class="avatar-content">
                                        <i data-feather="briefcase" class="font-medium-5"></i>
                                    </div>
                                </div>
                                <h2 class="fw-bolder mt-1">{{ $pendings ?? 0 }}</h2>
                                <p class="card-text" style="margin-bottom: 10px !important;">{{ __('Upcoming Orders') }}</p>
                            </div>
                          
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header flex-column align-items-start pb-0">
                                <div class="avatar bg-light-success p-50 m-0">
                                    <div class="avatar-content">
                                        <i data-feather="box" class="font-medium-5"></i>
                                    </div>
                                </div>
                                <h2 class="fw-bolder mt-1">{{ $pickup ?? 0}}</h2>
                                <p class="card-text" style="margin-bottom: 10px !important;">{{ __('Processing Orders') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header flex-column align-items-start pb-0">
                                <div class="avatar bg-light-info p-50 m-0">
                                    <div class="avatar-content">
                                        <i data-feather="credit-card" class="font-medium-5"></i>
                                    </div>
                                </div>
                                <h2 class="fw-bolder mt-1">{{ $completed ?? 0 }}</h2>
                                <p class="card-text" style="margin-bottom: 10px !important;">{{ __('Completed Orders') }}</p>
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
    <input type="hidden" id="gif_url" value="{{ asset('uploads/loader.gif') }}">
 
    <!-- END: Content-->
@endsection
@section('page-script')
    <script src="{{ asset('assets/seller/dashboard.js') }}"></script>
@endsection
