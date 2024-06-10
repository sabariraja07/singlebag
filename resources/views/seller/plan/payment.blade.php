@extends('layouts.seller')
@section('title', 'Make Payment')
@section('content')
    @php
        $stripe = false;
    @endphp
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">{{ __('Make Payment') }}</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Make Payment') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<!-- Desktop View Start -->
    <div class="container py-2 mobile_view desktop_view">

        <div class="row">
            <div class="col-lg-7 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="card-body">
                            <ul role="tablist" class="nav rounded nav-fill mb-3">
                                @foreach ($gateways as $key => $row)
                                    <li class="nav-item">
                                        <a data-toggle="pill" href="#{{ $row->slug }}"
                                            class="nav-link @if ($key == 0) active @endif">
                                            <img height="50" src="{{ asset($row->image) }}">
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                <table class="table table-hover table-borderlress col-12">
                                    <tr>
                                        <td>{{ __('Plan Name') }}</td>
                                        <td>{{ $info->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Plan Price') }}</td>
                                        <td>{{ amount_admin_format($info->price) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Discount') }}</td>
                                        <td>{{ amount_admin_format($discount) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Tax') }}</td>
                                        <td>{{ amount_admin_format($tax) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Total Amount') }}</td>
                                        <td>{{ amount_admin_format($price) }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Credit card form content -->
                            <div class="tab-content">
                                @php
                                    if (url('/') == env('APP_URL')) {
                                        $url = url('/merchant/make-charge/');
                                    } else {
                                        $url = url('/seller/make-charge/');
                                    }
                                    
                                @endphp
                                <!-- credit card info-->
                                @foreach ($gateways as $key => $row)
                                    <div id="{{ $row->slug }}"
                                        class="tab-pane fade @if ($key == 0) show active @endif pt-3">
                                        @if ($row->slug == 'stripe')
                                            @php
                                                $stripe = true;
                                            @endphp
                                            <script src="https://js.stripe.com/v3/"></script>
                                            <form action="{{ url($url . '/' . $info->id) }}" method="post"
                                                id="payment-form">
                                                @csrf
                                                @php
                                                    $credentials = $row->gateway->content ?? [];
                                                @endphp
                                                <input type="hidden" name="payment_method" value="{{ $row->slug }}">
                                                <input type="hidden" id="publishable_key"
                                                    value="{{ $credentials['publishable_key'] ?? '' }}">
                                                <div class="form-group">


                                                    <label for="card-element">
                                                        Credit or debit card
                                                    </label>
                                                    <div id="card-element">
                                                        <!-- A Stripe Element will be inserted here. -->
                                                    </div>

                                                    <!-- Used to display form errors. -->
                                                    <div id="card-errors" role="alert"></div>

                                                    @if ($info->price > 0)
                                                        <button type="submit"
                                                            class="subscribe btn btn-primary btn-block shadow-sm mt-2"> Make
                                                            Payment With {{ $row->name }} ({{ $price }})
                                                        </button>
                                                    @else
                                                        <a href="{{ url(env('APP_URL') . '/contact') }}" target="_blank"
                                                            class="subscribe btn btn-success btn-block shadow-sm text-white">{{ __('Please Contact With Us') }}</a>
                                                    @endif
                                                </div>
                                            </form>
                                        @else
                                            <form role="form" method="post" action="{{ url($url . '/' . $info->id) }}">
                                                @csrf
                                                <input type="hidden" name="payment_method" value="{{ $row->slug }}">
                                                <div class="form-group mb-1">
                                                    <label>
                                                        <h6>{{ __('Name') }}</h6>
                                                    </label>
                                                    <input type="text" name="name" readonly=""
                                                        value="{{ Auth::user()->fullname }}" class="form-control ">
                                                </div>
                                                <div class="form-group mb-1">
                                                    <label>
                                                        <h6>{{ __('Email') }}</h6>
                                                    </label>
                                                    <input type="text" name="email" readonly=""
                                                        value="{{ Auth::user()->email }}" class="form-control ">
                                                </div>
                                                <div class="form-group mb-1">
                                                    <label for="username">
                                                        <h6>{{ __('Phone Number') }}</h6>
                                                    </label>
                                                    <input type="number" name="phone"
                                                        placeholder="Enter Your Phone Number" required
                                                        class="form-control ">
                                                </div>
                                                @if ($info->price > 0)
                                                    <button type="submit"
                                                        class="subscribe btn btn-lg btn-primary btn-block shadow-sm mt-1 col-12">
                                                        {{ __('Make Payment With') }} {{ $row->name }}</button>
                                                @else
                                                    <a href="{{ url(env('APP_URL') . '/contact') }}" target="_blank"
                                                        class="subscribe btn btn-success btn-block shadow-sm text-white">{{ __('Please Contact With Us') }}</a>
                                                @endif

                                            </form>
                                        @endif
                                    </div>
                                @endforeach
                                <a href="{{ route('seller.plan.index') }}"
                                    class="btn btn-primary btn-block shadow-sm text-white mt-1 text-justify col-12">{{ __('Choose Plan') }}</a>
                                <!-- Paypal info -->


                                <!-- End -->
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
	<!-- Desktop View End -->

    <!-- Mobile View Start -->
    <div class="content-body mobile_view">
        <section id="pricing-plan">
            <!-- pricing plan cards -->
            <div class="row pricing-card">
                <div class="col-12 col-sm-offset-2 col-sm-12 col-md-12 col-lg-offset-2 col-lg-12 mx-auto">
                    <div class="row">
                        <!-- basic plan -->
                        <div class="col-12 col-md-4 popular_div mb-5 mt-2">
                            <div class="card standard-pricing text-center">
                                <div class="card-body">
                                    <!-- <img src="../../../app-assets/images/illustration/Pot1.svg" class="mb-2 mt-5" alt="svg img" /> -->
                                    <h3><b class="text-black">{{ $info->name }}</b></h3>
                                    {{-- <span class="card-text">{{ number_format($info->price,2)}}</span> --}}
                                    <ul class="list-group list-group-circle text-start">
                                        <li class="list-group-item">{{ __('Plan Price') }} : <b
                                                class="text-black">{{ number_format($info->price, 2) }}</b></li>

                                        <li class="list-group-item">{{ __('Discount') }} : <b
                                                class="text-black">{{ number_format($discount, 2) }}</b></li>

                                        <li class="list-group-item">{{ __('Tax') }} : <b
                                                class="text-black">{{ number_format($tax, 2) }}</b></li>
                                        <li class="list-group-item">{{ __('Total Amount') }} : <b
                                                class="text-black">{{ $price }}</b></li>

                                        <hr>

                                    </ul>
                                    <div class="tab-content">
                                        @php
                                            if (url('/') == env('APP_URL')) {
                                                $url = url('/merchant/make-charge/');
                                            } else {
                                                $url = url('/seller/make-charge/');
                                            }
                                            
                                        @endphp
                                        <!-- credit card info-->
                                        @foreach ($gateways as $key => $row)
                                            <div id="{{ $row->slug }}"
                                                class="tab-pane fade @if ($key == 0) show active @endif">
                                                @if ($row->slug == 'stripe')
                                                    @php
                                                        $stripe = true;
                                                    @endphp
                                                    <script src="https://js.stripe.com/v3/"></script>
                                                    <form action="{{ url($url . '/' . $info->id) }}" method="post"
                                                        id="payment-form">
                                                        @csrf
                                                        @php
                                                            $credentials = $row->gateway->content ?? [];
                                                        @endphp
                                                        <input type="hidden" name="payment_method"
                                                            value="{{ $row->slug }}">
                                                        <input type="hidden" id="publishable_key"
                                                            value="{{ $credentials['publishable_key'] ?? '' }}">
                                                        <div class="form-group">


                                                            <label for="card-element">
                                                                {{ __('Credit or debit card') }}
                                                            </label>
                                                            <div id="card-element">
                                                                <!-- A Stripe Element will be inserted here. -->
                                                            </div>

                                                            <!-- Used to display form errors. -->
                                                            <div id="card-errors" role="alert"></div>

                                                            @if ($info->price > 0)
                                                                <button type="submit"
                                                                    class="subscribe btn btn-primary btn-block shadow-sm mt-2">
                                                                    {{ __('Make Payment With') }} {{ $row->name }}
                                                                    ({{ $price }}) </button>
                                                            @else
                                                                <a href="{{ url(env('APP_URL') . '/contact') }}"
                                                                    target="_blank"
                                                                    class="subscribe btn btn-success btn-block shadow-sm text-white">{{ __('Please Contact With Us') }}</a>
                                                            @endif
                                                        </div>
                                                    </form>
                                                @else
                                                    <form role="form" method="post"
                                                        action="{{ url($url . '/' . $info->id) }}">
                                                        @csrf
                                                        <input type="hidden" name="payment_method"
                                                            value="{{ $row->slug }}">
                                                        <div class="form-group mb-1" style="text-align: left !important;">
                                                            <label>
                                                                <h6>{{ __('Name') }}</h6>
                                                            </label>
                                                            <input type="text" name="name" readonly=""
                                                                value="{{ Auth::user()->fullname }}"
                                                                class="form-control ">
                                                        </div>
                                                        <div class="form-group mb-1" style="text-align: left !important;">
                                                            <label>
                                                                <h6>{{ __('Email') }}</h6>
                                                            </label>
                                                            <input type="text" name="email" readonly=""
                                                                value="{{ Auth::user()->email }}" class="form-control ">
                                                        </div>
                                                        <div class="form-group mb-1" style="text-align: left !important;">
                                                            <label for="username">
                                                                <h6>{{ __('Phone Number') }}</h6>
                                                            </label>
                                                            <input type="number" name="phone"
                                                                placeholder="Enter Your Phone Number" required
                                                                class="form-control ">
                                                        </div>
                                                        @if ($info->price > 0)
                                                            <button type="submit"
                                                                class="subscribe btn w-100 btn-primary mt-2">
                                                                {{ __('Make Payment With') }} {{ $row->name }}</button>
                                                        @else
                                                            <a href="{{ url(env('APP_URL') . '/contact') }}"
                                                                target="_blank"
                                                                class="subscribe btn w-100 btn-primary mt-2">{{ __('Please Contact With Us') }}</a>
                                                        @endif

                                                    </form>
                                                @endif
                                            </div>
                                        @endforeach
                                        <a href="{{ route('seller.plan.index') }}"
                                            class="btn w-100 btn-primary mt-2">{{ __('Choose Plan') }}</a>

                                        <!-- End -->
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!--/ pricing plan cards -->
        </section>

    </div>
	<!-- Mobile View End -->
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>

@endsection
@section('page-script')
    @if ($stripe == true)
        <script src="{{ asset('assets/js/stripe.js') }}"></script>
    @endif
@endsection
