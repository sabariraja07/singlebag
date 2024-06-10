@extends('layouts.seller')
@section('title', 'Paystack')
@section('content')
    <div class="content-header row">
        @if (Session::has('success'))
            <div class="alert alert-success">
                <ul style="margin-top: 1rem;">
                    <li>{{ Session::get('success') }}</li>
                </ul>
            </div>
        @endif
    </div>
    {{-- <div class="row justify-content-center"> --}}
    <div class="content-wrapper container-xxl p-0">

        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Paystack Method') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{ url('/seller/settings/payment') }}">{{ __('Payment Options') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('Paystack Method') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
            </div>
        </div>
        <div class="col-12 col-md-12">

            <div class="card">
                <div class="card-header">
                    <div class="col">
                        <h5><a href="#" class="header-pretitle">{{ $info->name }} {{ __('Method') }}</a></h5>


                    </div>
                </div>
                <div class="card-body">
                    <form method="post" class="basicform"
                        action="{{ route('seller.payment.update', $info->slug) }}">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group mb-1">
                                <label>{{ __('Display name at checkout') }}</label>
                                <input class="form-control" autofocus="" required="" name="name" type="text"
                                    value="{{ $data['title'] ?? '' ?? '' }}">
                                <small class="form-text text-muted">{{ __('Customers will see this when checking out.') }}</small>
                            </div>


                            <div class="form-group mb-1">
                                <label>{{ __('Public Key') }}</label>
                                <input class="form-control" required="" value="{{ $data['public_key'] ?? '' }}"
                                    name="public_key" required="" type="text">
                            </div>

                            <div class="form-group mb-1">
                                <label>{{ __('Secret Key') }}</label>
                                <input class="form-control" required="" value="{{ $data['secret_key'] ?? '' }}"
                                    name="secret_key" required="" type="text">
                            </div>

                            {{-- <div class="form-group mb-1">
                                <label>{{ __('currency') }}</label>
                                <input class="form-control" value="{{ $currency->code ?? "" }}" name="currency"
                                    required="" type="text" required="">
                            </div> --}}
                            <div class="form-group mb-1">
                                <label>{{ __('Purpose') }}</label>
                                <input class="form-control" required="" value="{{ $data['description'] ?? '' }}"
                                    name="description" required="" type="text">
                            </div>



                            <div class="form-group mb-1">
                                <div class="custom-control custom-switch">
                                    <input id="enabled" class="custom-control-input" name="status" type="checkbox"
                                        value="1" @if ($info->gateway->status == 1) checked="" @endif>
                                    <label class="custom-control-label" for="enabled">{{ __('Enable') }}</label>
                                </div>
                            </div>
                            <div class="card-footer clearfix text-muted">

                                <div class="float-left clear-both">
                                    <a class="btn btn-white"
                                        href="{{ route('seller.settings.show', 'payment') }}">{{ __('Cancel') }}</a>
                                    <button type="submit" class="btn btn-primary basicbtn">{{ __('Save') }}</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('page-script')
    <script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
@endsection
