@extends('layouts.seller')
@section('page-style')
    <style>
        @media screen and (max-width: 900px) {
            .connect{
            margin-left: -38px;
            margin-top: -8px;
            }
           
        }
    </style>

@endsection
@section('title', 'Payment Options')
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
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Payment Options') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{ __('Settings') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('Payment Options') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger" role="alert">
                    <div class="alert-body"><strong>{{ __('Note') }}:
                        </strong>{{ __('before activating the payment gateway make sure your currency is supported with your currency') }}
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-12">

                        <!-- security -->
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">{{ __('Payment settings') }}</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="card-body pt-1">
                                        <!-- payment methods -->
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="added-cards">
                                                    <div class="cardMaster rounded p-2 mb-1">
                                                        <b>{{ __('Payment Mode Details') }}</b>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- / payment methods -->
                                    </div>
                                </div>
                                <div class="col-md-8 col-12">
                                    <div class="card-body pt-1">
                                        <!-- payment methods -->
                                        <div class="card">
                                            <div class="card-body" style=" text-align: right !important; ">
                                                <div class="added-cards">
                                                    @foreach ($posts as $row)
                                                        <div class="cardMaster rounded border p-2 mb-1">
                                                            <div class="row">
                                                                <!-- <div class="col-4" style="text-align: center !important;margin: auto;display: block;"> -->
                                                                <!-- <img class="mb-1 img-fluid" src="../../../app-assets/images/icons/payments/mastercard.png" alt="Master Card" />
                                                                    <img class="mb-1 img-fluid" src="https://app.leaf24.in/uploads/toyyibpay.jpg" alt="" height="120" width="120"> -->

                                                                <!-- </div> -->
                                                                <div class="col-7" style=" text-align: left !important; ">
                                                                    <div>
                                                                        <h6 class="mb-0">{{ $row->name }}</h6><br>
                                                                        {{-- <span
                                                                            class="card-number">{{ $row->description }}</span> --}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-5 connect">
                                                                    <div>
                                                                        @if (isset($row->gateway))
                                                                            @if ($row->gateway->status != 1)
                                                                                <a
                                                                                    href="{{ route('seller.payment.show', $row->slug) }}">
                                                                                    <button type="submit"
                                                                                        class="btn btn-warning"
                                                                                        data-id="{{ $row->slug }}">{{ __('Connect') }}</button>
                                                                                </a>
                                                                            @else
                                                                                <a
                                                                                    href="{{ route('seller.payment.show', $row->slug) }}">
                                                                                    <button type="submit"
                                                                                        class="btn btn-success"
                                                                                        data-id="{{ $row->slug }}">{{ __('Connected') }}</button>
                                                                                </a>
                                                                            @endif
                                                                        @else
                                                                            <form method="POST"
                                                                                action="{{ route('seller.payment.store') }}"
                                                                                class="basicform{{ $row->id }}">
                                                                                @csrf
                                                                                <input type="hidden" name="payment_method"
                                                                                    value="{{ $row->slug }}">
                                                                                <br>
                                                                                <button type="submit"
                                                                                    class="btn btn-danger"
                                                                                    data-id="{{ $row->id }}">{{ __('Install') }}</button>
                                                                            </form>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <!-- / payment methods -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ security -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->
    
@endsection
