@extends('layouts.seller')
@section('title', 'QR Code')
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
            </div>
            <div class="content-body">
                <!-- Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('QR Code') }}</h4>
                                    
                                </div>
                                <hr>
                                <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                {{-- <button class="btn btn-primary me-1">{{ __('Download') }}</button> --}}
                                                {{-- <br><br> --}}
                                            </div>
                                            <div class="col-md-12 mb-1">
                                                @if($company_info->data_type == 'website')
                                                {!! QrCode::size(200)->generate($company_info->website) !!}
                                                @elseif($company_info->data_type == 'phone')
                                                {!! QrCode::size(200)->generate('tel: '.$company_info->phone) !!}
                                                @elseif($company_info->data_type == 'sms')
                                                {!! QrCode::size(200)->generate('sms: '.$company_info->mobile_number.'?body='.$company_info->message) !!}
                                                @elseif($company_info->data_type == 'plain_text')
                                                {!! QrCode::size(200)->generate($company_info->plain_text) !!}
                                                @endif
                                            </div>
                                        </div>
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

