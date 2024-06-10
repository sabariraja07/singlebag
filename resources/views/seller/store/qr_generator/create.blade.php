@extends('layouts.seller')
@section('title', 'QR Code Generator')
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

                <div class="content-header-left col-md-12 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">{{ __('QR Code Generator') }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item">{{ __('Business Tools') }}
                                    </li>
                                    <li class="breadcrumb-item active">{{ __('QR Code Generator') }}
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
                                    <h4 class="card-title">{{ __('Create your QR Code') }}</h4>
                                    
                                </div>
                                {{-- <p class="seo_set">{{ __('Enter your company information.') }}</p> --}}
                                <hr>
                                <div class="card-body">
                                    <form action="{{ route('seller.qr-generator.store') }}" class="basicform_with_redirect" method="post">
                                        @csrf
                                        <input type="hidden" name="shop_id" id="shop_id" required="" class="form-control"
                                        value="{{ current_shop_id() }}">
                                        <div class="row">
                                            <div class="col-md-12 mb-2 data_type">
                                                <h4>{{ __('Select Data Type') }}</h4><br>
                                                <input type="radio" class="data_type" name="data_type" value="website" {{ old('data_type')=='website' ? 'checked' : '' }}> Website &nbsp;
                                                <input type="radio" class="data_type" name="data_type" value="phone" {{ old('data_type')=='phone' ? 'checked' : '' }}> Phone# &nbsp;
                                                <input type="radio" class="data_type" name="data_type" value="sms" {{ old('data_type')=='sms' ? 'checked' : '' }}> SMS &nbsp;
                                                <input type="radio" class="data_type" name="data_type" value="plain_text" {{ old('data_type')=='plain_text' ? 'checked' : '' }}> Plain Text &nbsp;
                                            </div>                                            
                                            <div class="col-md-6 mb-1 website">
                                                <label class="form-label">{{ __('Website') }}</label>
                                                <input class="form-control" name="website"
                                                    value="{{ $user->website ?? old('website') }}" type="text" placeholder="http://test.com">
                                                @if ($errors->has('website'))
                                                    <span id="signup-website-error" class="error">{{ $errors->first('website') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-1 phone d-none">
                                                <label class="form-label">{{ __('Phone') }}</label>
                                                <input class="form-control" name="phone"
                                                    value="{{ $user->phone ?? old('phone') }}" type="text" >
                                                @if ($errors->has('phone'))
                                                    <span id="signup-phone-error" class="error">{{ $errors->first('phone') }}</span>
                                                @endif           
                                            </div>
                                            <div class="col-md-6 mb-1 sms d-none">
                                                <label class="form-label">{{ __('Mobile Number') }}</label>
                                                <input class="form-control" name="mobile_number"
                                                    value="{{ $user->mobile_number ?? old('mobile_number') }}" type="text" >
                                                @if ($errors->has('mobile_number'))
                                                    <span id="signup-mobile-number-error" class="error">{{ $errors->first('mobile_number') }}</span>
                                                @endif
                                                <br>                                                
                                                <label class="form-label">{{ __('Message') }}</label>
                                                <input class="form-control" name="message"
                                                    value="{{ $user->message ?? old('message') }}" type="text" >
                                                @if ($errors->has('message'))
                                                    <span id="signup-message-error" class="error">{{ $errors->first('message') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-1 plain_text d-none">
                                                <label class="form-label">{{ __('Plain Text') }}</label>
                                                <input class="form-control" name="plain_text"
                                                    value="{{ $user->plain_text ?? old('plain_text') }}" type="text" >
                                                @if ($errors->has('plain_text'))
                                                    <span id="signup-plain-text-error" class="error">{{ $errors->first('plain_text') }}</span>
                                                @endif
                                            </div>
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
@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script>
        $(document).on('change', '.data_type', function() {
            var data_type = $('input[name="data_type"]:checked').val();
            if(data_type == 'website'){
                $(".website").removeClass('d-none');
                $(".mobile_number").addClass('d-none');
                $(".plain_text").addClass('d-none');
                $(".sms").addClass('d-none');
            }
            else if(data_type == 'phone'){
                $(".website").addClass('d-none');
                $(".phone").removeClass('d-none');
                $(".plain_text").addClass('d-none');
                $(".sms").addClass('d-none');
            } 
            else if(data_type == 'sms'){
                $(".website").addClass('d-none');
                $(".phone").addClass('d-none');
                $(".plain_text").addClass('d-none');
                $(".sms").removeClass('d-none');
            } 
            else if(data_type == 'plain_text'){
                $(".website").addClass('d-none');
                $(".phone").addClass('d-none');
                $(".plain_text").removeClass('d-none');
                $(".sms").addClass('d-none');
            }
        });
    </script>
@endsection