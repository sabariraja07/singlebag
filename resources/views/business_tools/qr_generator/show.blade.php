<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>QR Code Generator</title>
    <!-- Template CSS -->
    <link href="{{ asset('frontend/fashionbag/css/style.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <script>
        window.singlebag = {
            baseUrl: '{{ url('/') }}',
            rtl: false,
            storeName: '{{ env('APP_NAME') }}',
            loggedIn: '{{ auth()->guard('customer')->check()? 1: 0 }}',
            csrfToken: '{{ csrf_token() }}',
            langs: {!! get_lang_trans() !!},
            currency: {!! json_encode(currency_info(), true) !!},
            theme_color: '{{ Cache::get(domain_info('shop_id') . 'theme_color', '#dc3545') }}'
        };
    </script>
    <style>
        .HeaderLogo {
            margin: 20px;
            text-align: center;
        }
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
        @include('layouts.partials.analytics')
</head>

<body>
    {{-- <div class="HeaderLogo"><a href="/">
            <img src="{{ admin_logo_url() }}" alt="Logo" width="150px"></a>
    </div> --}}
    {{-- <div class="section">
        <div class="breadcrumb-area bg-light">
            <div class="container-fluid">
                <div class="breadcrumb-content text-center">
                    <h1 class="title">{{ __('QR Code Generator') }}</h1>
                    <ul>
                        <li>
                            <a href="/">{{ __('Home') }}</a>
                        </li>
                        <li class="active">{{ __('QR Code Generator') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="section" style=" padding: 50px; ">
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
</body>

</html>

