@extends('layouts.seller')
@section('title', 'Privacy Policy Generator')
@section('page-style')
<style>
    hr {
            width: 93% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            margin-top: -5px !important;
        }
          /* Mobile View */
	  @media screen and (max-width: 900px) {
            .seo_set{
				margin-left: 14px !important;
            }
        }
        @media screen and (min-width: 992px) {
            .seo_set{
				margin-left: 21px !important;
            }

        }
        
</style>
@endsection
@section('content')
@php
	$url=domain_info('full_domain');
@endphp
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
                            <h2 class="content-header-title float-start mb-0">{{ __('Privacy Policy Generator') }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item">{{ __('Business Tools') }}
                                    </li>
                                    <li class="breadcrumb-item active">{{ __('Privacy Policy Generator') }}
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
                                    <h4 class="card-title">{{ __('Enter your company information.') }}</h4>
                                    
                                </div>
                                {{-- <p class="seo_set">{{ __('Enter your company information.') }}</p> --}}
                                <hr>
                                <div class="card-body">
                                    <form id="basicform" action="{{ route('seller.privacy-policy-generator.store') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="shop_id" id="shop_id" required="" class="form-control"
                                        value="{{ current_shop_id() }}">
                                        <div class="row">
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label"
                                                    for="company_name">{{ __('Store Name') }}</label>
                                                <input class="form-control" name="store_name"
                                                    value="{{ $user->email ?? old('store_name') }}" type="text"
                                                    required="">
                                                @if ($errors->has('store_name'))
                                                    <span id="signup-store-name-error" class="error">{{ $errors->first('store_name') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label" for="email">{{ __('Email') }}</label>
                                                <input class="form-control @if($errors->has('email')) is-invalid @endif" name="email" type="email"
                                                 value="{{ $user->email ?? old('email') }}" required="">
                                                @if ($errors->has('email'))
                                                    <span id="signup-email-error" class="error">{{ $errors->first('email') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label">{{ __('Mobile Number') }}</label>
                                                <input class="form-control @if($errors->has('mobile_number')) is-invalid @endif" name="mobile_number" type="text"
                                                required="" value="{{ $user->mobile_number ?? old('mobile_number') }}">
                                                @if ($errors->has('mobile_number'))
                                                    <span id="signup-mobile-number-error" class="error">{{ $errors->first('mobile_number') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label" for="address">{{ __('Address') }}</label>
                                                <input class="form-control" name="address"
                                                     type="text" value="{{ $user->address ?? old('address') }}"
                                                    required="">
                                                @if ($errors->has('address'))
                                                    <span id="signup-address-error" class="error">{{ $errors->first('address') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label" for="city">{{ __('City') }}</label>
                                                <input class="form-control" name="city"
                                                    type="text" value="{{ $user->city ?? old('city') }}"
                                                    required="">
                                                @if ($errors->has('city'))
                                                    <span id="signup-city-error" class="error">{{ $errors->first('city') }}</span>
                                                @endif                                                
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label">{{ __('State') }}</label>
                                                <input class="form-control" name="state"
                                                    type="text" value="{{ $user->state ?? old('state') }}" required="">
                                                @if ($errors->has('state'))
                                                    <span id="signup-state-error" class="error">{{ $errors->first('state') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label">{{ __('Country') }}</label>
                                                <input class="form-control" name="country"
                                                     type="text" value="{{ $user->country ?? old('country') }}" required="">
                                                @if ($errors->has('country'))
                                                     <span id="signup-country-error" class="error">{{ $errors->first('country') }}</span>
                                                 @endif
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label"
                                                    >{{ __('Postal / Zip Code') }}</label>
                                                <input class="form-control" name="zip_code"
                                                    type="text" value="{{ $user->zip_code ?? old('zip_code') }}"
                                                    placeholder="1234" required="">
                                                @if ($errors->has('zip_code'))
                                                    <span id="signup-zip-code-error" class="error">{{ $errors->first('zip_code') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-header" style=" padding-left: 0px; ">
                                            <h4 class="card-title">{{ __('Enter your website information.') }}</h4>
                                        </div>
                                        <hr><br>
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label">{{ __('Website') }}</label>
                                            <input class="form-control" name="website"
                                                value="{{ $user->website ?? old('website') }}" type="text" required="">
                                        </div>
                                        <br>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary me-1 basicbtn">{{ __('Generate') }}</button>
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
    <!-- END: Content-->
@endsection